<link rel="stylesheet" href="./style/calculator.css">

<?php
$labels = [
    'transport'        => 'Transport',
    'logement'         => 'Logement',
    'alimentation'     => 'Alimentation',
    'achats_numerique' => 'Achats & numérique',
];

$total = $result['total'];
$totalTonnes = number_format($total / 1000, 2, ',', ' ');
$moyenneFr = 9900;
$objectif = 2000;
$max = max($result['breakdown']) ?: 1;

if ($total <= $objectif) {
    $verdict = 'Excellent ! Votre empreinte est proche de l\'objectif mondial.';
} elseif ($total <= $moyenneFr) {
    $verdict = 'Bien : vous êtes en dessous de la moyenne française.';
} else {
    $verdict = 'Au-dessus de la moyenne française : il y a des leviers à actionner.';
}
?>

<hgroup>
  <h1>Votre empreinte carbone</h1>
  <p>Estimation de votre impact environnemental annuel</p>
</hgroup>

<section>
  <div class="result-total">
    <span class="result-value"><?= $totalTonnes ?></span>
    <span class="result-unit">tonnes de CO<sub>2</sub> / an</span>
  </div>
  <p class="result-verdict"><?= htmlspecialchars($verdict) ?></p>

  <div class="result-compare">
    <span>Moyenne française : <strong><?= number_format($moyenneFr / 1000, 1, ',', ' ') ?> t</strong></span>
    <span>Objectif 2050 : <strong><?= number_format($objectif / 1000, 1, ',', ' ') ?> t</strong></span>
  </div>

  <div class="result-breakdown">
    <?php foreach ($result['breakdown'] as $key => $value): ?>
      <div class="result-row">
        <div class="result-row-head">
          <span><?= htmlspecialchars($labels[$key] ?? $key) ?></span>
          <span><?= number_format($value, 0, ',', ' ') ?> kg</span>
        </div>
        <div class="result-track">
          <div class="result-fill" style="width: <?= round($value / $max * 100) ?>%"></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <a class="link-calculator" href="/calculator">Recommencer le calcul</a>
</section>
