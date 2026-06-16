<link rel="stylesheet" href="./style/challenge.css">
<?php
/**
 * Variables fournies par HomeController@showChallengePage :
 * @var array|null $empreinte    Dernière empreinte de l'utilisateur
 * @var string|null $posteFocus  Poste le plus émetteur (libellé)
 * @var array $defis             Défis recommandés (max 3)
 * @var array $stats             completed, total, points, co2, streak, completedToday
 * @var int[] $completedIds      Identifiants des défis déjà validés
 */

// difficulte_id => [classe CSS, libellé]
$difficulteMap = [
    1 => ['easy', 'Facile'],
    2 => ['mid', 'Intermédiaire'],
    3 => ['hard', 'Difficile'],
];

// type_defi_id => icône (assets/icons)
$iconMap = [
    1 => 'defi-home.svg', // logement
    2 => 'defi-food.svg', // nourriture
    3 => 'defi-velo.svg', // transport
    4 => 'logo.svg',      // numerique (pas d'icône dédiée)
];

$totalDefis     = max(1, (int) $stats['total']);
$progressPct    = round($stats['completed'] / $totalDefis * 100);
$dailyDone      = min(3, (int) $stats['completedToday']);
$dailyPct       = round($dailyDone / 3 * 100);
?>
<hgroup>
    <h1>Défis écologiques</h1>
    <p>Relevez des défis quotidiens pour réduire votre empreinte carbone et gagner des points</p>
</hgroup>
<section>
    <div class="container">
        <div class="card progress-card">
            <h2>Défis complétés</h2>
            <div>
                <p><span><?= (int) $stats['completed'] ?></span>sur <?= (int) $stats['total'] ?> défis</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: <?= $progressPct ?>%"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <h2>Points gagnés</h2>
            <div>
                <p><span><?= (int) $stats['points'] ?></span>points au total</p>
            </div>
        </div>
        <div class="card">
            <h2>CO2 économisé</h2>
            <div>
                <p><span><?= number_format($stats['co2'], 1, ',', ' ') ?></span>kg de CO2</p>
            </div>
        </div>
        <div class="card">
            <h2>Série actuelle</h2>
            <div>
                <p><span><?= (int) $stats['streak'] ?></span>jours consécutifs</p>
            </div>
        </div>
    </div>

    <?php if (!$empreinte): ?>
        <div class="challenge-banner">
            <p>Vous n'avez pas encore calculé votre empreinte carbone.
               <a href="/calculator">Faites le calculateur</a> pour obtenir des défis personnalisés.</p>
        </div>
    <?php else: ?>
        <div class="challenge-banner">
            <p>D'après votre dernier calcul, votre poste le plus émetteur est
               <strong><?= htmlspecialchars($posteFocus) ?></strong>.
               Voici les défis prioritaires pour réduire votre empreinte.</p>
        </div>
    <?php endif; ?>

    <div class="challenge-container">
        <div>
            <h3>Défis du jour</h3>
            <p>Compléter 3 défis pour obtenir une récompense</p>
        </div>
        <div class="progress-container">
            <div class="progress-bar" style="width: <?= $dailyPct ?>%"></div>
        </div>

        <?php if (empty($defis)): ?>
            <p>Bravo, vous avez relevé tous les défis disponibles ! 🎉</p>
        <?php else: ?>
            <div class="cards-challenge">
                <?php foreach ($defis as $defi): ?>
                    <?php
                    [$diffClass, $diffLabel] = $difficulteMap[(int) $defi['difficulte_id']] ?? ['mid', 'Intermédiaire'];
                    $icon = $iconMap[(int) $defi['type_defi_id']] ?? 'logo.svg';
                    $co2 = number_format((float) $defi['co2_economise'], 1, ',', ' ');
                    $estValide = in_array((int) $defi['id'], $completedIds, true);
                    ?>
                    <div class="card-challenge">
                        <div class="header-card">
                            <div class="logo-div-card <?= $diffClass ?>">
                                <img src="/assets/icons/<?= $icon ?>" alt="" width="24" height="24">
                            </div>
                            <span><?= htmlspecialchars($diffLabel) ?></span>
                        </div>
                        <div class="para-card">
                            <h4><?= htmlspecialchars($defi['titre']) ?></h4>
                            <p><?= htmlspecialchars($defi['description']) ?></p>
                        </div>
                        <div class="mid-card">
                            <p><?= $co2 ?> kg de CO2</p>
                            <span><?= (int) $defi['points'] ?> points</span>
                        </div>
                        <div class="footer-card">
                            <a href="/calculator" class="conseil">Voir les conseils</a>
                            <?php if ($estValide): ?>
                                <span class="validate validated">Validé ✓</span>
                            <?php else: ?>
                                <a href="/challenge/validate/<?= (int) $defi['id'] ?>" class="validate">Validez</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
