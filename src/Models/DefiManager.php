<?php

namespace MVC\Models;

class DefiManager
{
    private $bdd;

    /**
     * Correspondance entre les postes de l'empreinte (calculateur)
     * et les types de défi (table types_defi).
     */
    private const POSTE_VERS_TYPE = [
        'empreinte_transport'       => 3, // transport
        'empreinte_logement'        => 1, // logement
        'empreinte_alimentation'    => 2, // nourriture
        'empreinte_achat_numerique' => 4, // numerique
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

    /**
     * Dernière empreinte enregistrée pour l'utilisateur (ou null).
     */
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

    /**
     * Tous les défis actifs, groupés par type_defi_id, triés par CO2 décroissant.
     */
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

    /**
     * Défis du jour : une sélection qui change chaque jour tout en ciblant
     * en priorité les postes les plus émetteurs du dernier calcul.
     *
     * Pour chaque catégorie prioritaire, on choisit un défi en faisant
     * tourner la difficulté selon le jour, ce qui donne de nouveaux défis
     * chaque jour sans perdre le ciblage sur l'empreinte de l'utilisateur.
     */
    public function getDailyDefis(int $userId, int $count = 3): array
    {
        $empreinte = $this->getLatestEmpreinte($userId);
        $allDefis  = $this->getActiveDefis();

        // Ordonner les types de défi selon l'impact du poste correspondant.
        $impacts = [];
        foreach (self::POSTE_VERS_TYPE as $poste => $typeId) {
            $impacts[$typeId] = $empreinte ? (int) $empreinte[$poste] : 0;
        }
        arsort($impacts); // poste le plus émetteur en premier
        $ordreTypes = array_keys($impacts);

        // Numéro du jour, base de la rotation quotidienne.
        $jour = (int) (new \DateTime('today'))->format('z');

        // Sans empreinte, on fait aussi tourner l'ordre des catégories
        // pour que tous les postes soient proposés au fil des jours.
        if (!$empreinte) {
            $offset = $jour % max(1, count($ordreTypes));
            $ordreTypes = array_merge(
                array_slice($ordreTypes, $offset),
                array_slice($ordreTypes, 0, $offset)
            );
        }

        $daily = [];
        foreach ($ordreTypes as $typeId) {
            $pool = $allDefis[$typeId] ?? [];
            if (!$pool) {
                continue;
            }
            // Rotation du défi choisi dans la catégorie selon le jour.
            $daily[] = $pool[$jour % count($pool)];
            if (count($daily) >= $count) {
                break;
            }
        }

        return $daily;
    }

    /**
     * Libellé du poste le plus émetteur, pour expliquer la recommandation.
     */
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

    /**
     * Identifiants des défis validés aujourd'hui par l'utilisateur.
     */
    public function getCompletedTodayIds(int $userId): array
    {
        $query = $this->bdd->prepare('
            SELECT defi_id FROM defis_completes
            WHERE user_id = :uid AND completed_on = :today
        ');
        $query->execute([':uid' => $userId, ':today' => date('Y-m-d')]);

        return array_map('intval', array_column($query->fetchAll(), 'defi_id'));
    }

    /**
     * Statistiques affichées en haut de la page (cartes).
     */
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

    /**
     * Valide un défi pour l'utilisateur (idempotent grâce à la clé unique).
     */
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

    /**
     * Nombre de jours consécutifs (jusqu'à aujourd'hui ou hier) avec au moins un défi validé.
     */
    private function computeStreak(array $jours): int
    {
        if (!$jours) {
            return 0;
        }

        $set = array_flip($jours);
        $cursor = new \DateTime('today');

        // La série reste valable si rien aujourd'hui mais quelque chose hier.
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
