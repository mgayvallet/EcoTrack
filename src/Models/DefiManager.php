<?php

namespace MVC\Models;

// Manager des défis : propose 3 défis/jour selon la catégorie la plus polluante
class DefiManager
{
    private $bdd;

    // Mappe entre les postes du calculateur et les types de défis
    private const POSTE_VERS_TYPE = [
        'empreinte_transport'       => 3,
        'empreinte_logement'        => 1,
        'empreinte_alimentation'    => 2,
        'empreinte_achat_numerique' => 4,
    ];

    public function __construct()
    {
        try {
            $this->bdd = new \PDO(
                'mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8mb4',
                USER,
                PASSWORD,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function getLatestEmpreinte(int $userId): ?array
    {
        $query = $this->bdd->prepare('
            SELECT * FROM empreintes
            WHERE user_id = :uid
            ORDER BY created_at DESC, id DESC
            LIMIT 1
        ');
        $query->execute([':uid' => $userId]);
        $row = $query->fetch();

        return $row ?: null;
    }

    public function getActiveDefis(): array
    {
        $rows = $this->bdd->query('
            SELECT d.*, di.libelle AS difficulte, t.libelle AS type
            FROM defis d
            JOIN difficultes di ON di.id = d.difficulte_id
            JOIN types_defi t   ON t.id  = d.type_defi_id
            WHERE d.actif = 1
            ORDER BY d.co2_economise DESC
        ')->fetchAll();

        $grouped = [];
        foreach ($rows as $defi) {
            $grouped[(int) $defi['type_defi_id']][] = $defi;
        }

        return $grouped;
    }

    // Sélectionne 3 défis qui changent chaque jour avec rotation de difficulté
    // Cible en priorité la catégorie la plus polluante
    public function getDailyDefis(int $userId, int $count = 3): array
    {
        $empreinte = $this->getLatestEmpreinte($userId);
        $allDefis  = $this->getActiveDefis();

        // Ordonne les catégories par impact décroissant
        $impacts = [];
        foreach (self::POSTE_VERS_TYPE as $poste => $typeId) {
            $impacts[$typeId] = $empreinte ? (int) $empreinte[$poste] : 0;
        }
        arsort($impacts);
        $ordreTypes = array_keys($impacts);

        // Numéro du jour (jour de l'année) pour rotation quotidienne
        $jour = (int) (new \DateTime('today'))->format('z');

        // Sans empreinte, on rotate les catégories
        if (!$empreinte) {
            $offset = $jour % max(1, count($ordreTypes));
            $ordreTypes = array_merge(
                array_slice($ordreTypes, $offset),
                array_slice($ordreTypes, 0, $offset)
            );
        }

        // Sélectionne les défis avec rotation de difficulté
        $daily = [];
        foreach ($ordreTypes as $typeId) {
            $pool = $allDefis[$typeId] ?? [];
            if (!$pool) {
                continue;
            }
            $daily[] = $pool[$jour % count($pool)];
            if (count($daily) >= $count) {
                break;
            }
        }

        return $daily;
    }

    // Retourne la catégorie la plus polluante
    public function getPosteFocus(int $userId): ?string
    {
        $empreinte = $this->getLatestEmpreinte($userId);
        if (!$empreinte) {
            return null;
        }

        $labels = [
            'empreinte_transport'       => 'Transport',
            'empreinte_logement'        => 'Logement',
            'empreinte_alimentation'    => 'Alimentation',
            'empreinte_achat_numerique' => 'Achats & numérique',
        ];

        $postes = [];
        foreach ($labels as $poste => $label) {
            $postes[$label] = (int) $empreinte[$poste];
        }
        arsort($postes);

        return array_key_first($postes);
    }

    // IDs des défis validés aujourd'hui
    public function getCompletedTodayIds(int $userId): array
    {
        $query = $this->bdd->prepare('
            SELECT defi_id FROM defis_completes
            WHERE user_id = :uid AND completed_on = :today
        ');
        $query->execute([':uid' => $userId, ':today' => date('Y-m-d')]);

        return array_map('intval', array_column($query->fetchAll(), 'defi_id'));
    }

    // Récupère les statistiques : points, CO2, streak, défis complétés
    public function getStats(int $userId): array
    {
        $query = $this->bdd->prepare('
            SELECT d.points, d.co2_economise, dc.completed_on
            FROM defis_completes dc
            JOIN defis d ON d.id = dc.defi_id
            WHERE dc.user_id = :uid
            ORDER BY dc.completed_on DESC
        ');
        $query->execute([':uid' => $userId]);
        $rows = $query->fetchAll();

        $points = 0;
        $co2 = 0.0;
        $jours = [];
        $today = date('Y-m-d');
        $completedToday = 0;

        foreach ($rows as $row) {
            $points += (int) $row['points'];
            $co2    += (float) $row['co2_economise'];
            $jour = $row['completed_on'];
            $jours[$jour] = true;
            if ($jour === $today) {
                $completedToday++;
            }
        }

        $totalActifs = (int) $this->bdd->query('SELECT COUNT(*) FROM defis WHERE actif = 1')->fetchColumn();

        return [
            'completed'      => count($rows),
            'total'          => $totalActifs,
            'points'         => $points,
            'co2'            => $co2,
            'streak'         => $this->computeStreak(array_keys($jours)),
            'completedToday' => $completedToday,
        ];
    }

    // Marque le défi comme complété (INSERT IGNORE bloque les doublons du même jour)
    public function validateDefi(int $userId, int $defiId): void
    {
        $query = $this->bdd->prepare('
            INSERT IGNORE INTO defis_completes (user_id, defi_id, completed_on, completed_at)
            VALUES (:uid, :did, :today, NOW())
        ');
        $query->execute([
            ':uid'   => $userId,
            ':did'   => $defiId,
            ':today' => date('Y-m-d'),
        ]);
    }

    // Calcule les jours consécutifs avec au moins un défi complété (grace period de 1 jour)
    private function computeStreak(array $jours): int
    {
        if (!$jours) {
            return 0;
        }

        $set = array_flip($jours);
        $cursor = new \DateTime('today');

        if (!isset($set[$cursor->format('Y-m-d')])) {
            $cursor->modify('-1 day');
            if (!isset($set[$cursor->format('Y-m-d')])) {
                return 0;
            }
        }

        $streak = 0;
        while (isset($set[$cursor->format('Y-m-d')])) {
            $streak++;
            $cursor->modify('-1 day');
        }

        return $streak;
    }
}
