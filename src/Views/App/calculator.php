<link rel="stylesheet" href="./style/calculator.css">
<hgroup>
  <h1>Calculez votre empreinte carbone</h1>
  <p>Répondez à quelques questions pour estimer votre impact environnemental annuel</p>
</hgroup>

<?php
$sections = [
    'transport'        => ['title' => 'Transport',          'subtitle' => 'Vos déplacements au quotidien'],
    'logement'         => ['title' => 'Logement',           'subtitle' => 'Votre consommation domestique'],
    'alimentation'     => ['title' => 'Alimentation',       'subtitle' => 'Vos habitudes alimentaires'],
    'achats_numerique' => ['title' => 'Achats & numérique', 'subtitle' => 'Vos achats et usages numériques'],
];

$steps = [];
foreach (array_keys($sections) as $key) {
    if (!empty($categories[$key])) {
        $steps[$key] = $categories[$key];
    }
}
$totalSteps = count($steps);
$firstKey = array_key_first($steps);
?>

<section>
  <div class="step-div">
    <hgroup>
      <h2 id="step-title"><?= htmlspecialchars($sections[$firstKey]['title']) ?></h2>
      <p>Etape <span id="current-step">1</span> sur <span id="total-steps"><?= $totalSteps ?></span></p>
    </hgroup>
    <div class="progress-container">
      <div class="progress-bar" id="progress-bar"></div>
    </div>
  </div>

  <form id="calculator-form" method="post" action="/calculator">
    <?php $index = 0; foreach ($steps as $key => $questions): $index++; ?>
      <div class="form-step" data-step="<?= $index ?>" data-title="<?= htmlspecialchars($sections[$key]['title']) ?>" <?= $index > 1 ? 'hidden' : '' ?>>
        <div>
          <h3><?= htmlspecialchars($sections[$key]['title']) ?></h3>
          <p><?= htmlspecialchars($sections[$key]['subtitle']) ?></p>
        </div>
        <div class="label-container">
          <?php foreach ($questions as $question): ?>
            <label>
              <span class="question-libelle"><?= htmlspecialchars($question['libelle']) ?></span>
              <?php if (!empty($question['aide'])): ?>
                <small class="question-aide"><?= htmlspecialchars($question['aide']) ?></small>
              <?php endif; ?>

              <?php if ($question['type'] === 'choix'): ?>
                <select name="<?= htmlspecialchars($question['code']) ?>" required>
                  <option value="" disabled selected>Choisir...</option>
                  <?php foreach ($question['options'] as $option): ?>
                    <option value="<?= htmlspecialchars($option['valeur']) ?>"><?= htmlspecialchars($option['libelle']) ?></option>
                  <?php endforeach; ?>
                </select>
              <?php else: ?>
                <div class="slider-row">
                  <input
                    type="range"
                    name="<?= htmlspecialchars($question['code']) ?>"
                    min="<?= htmlspecialchars($question['valeur_min']) ?>"
                    max="<?= htmlspecialchars($question['valeur_max']) ?>"
                    step="<?= htmlspecialchars($question['pas']) ?>"
                    value="<?= htmlspecialchars($question['valeur_defaut']) ?>"
                    data-unit="<?= htmlspecialchars($question['unite'] ?? '') ?>">
                  <span class="slider-value"></span>
                </div>
              <?php endif; ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </form>

  <div class="form-nav">
    <button type="button" class="link-calculator link-prev" id="prev-btn" hidden>
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      Précédent
    </button>
    <button type="button" class="link-calculator link-prev" id="reset-btn" hidden>
      Réinitialiser
    </button>
    <button type="button" class="link-calculator" id="action-btn"></button>
  </div>
</section>

<script>
  const form = document.getElementById("calculator-form");
  const stepEls = Array.from(form.querySelectorAll(".form-step"));
  const totalSteps = stepEls.length;
  let currentStep = 1;

  const progressBar = document.getElementById("progress-bar");
  const currentStepEl = document.getElementById("current-step");
  const stepTitle = document.getElementById("step-title");
  const prevBtn = document.getElementById("prev-btn");
  const resetBtn = document.getElementById("reset-btn");
  const actionBtn = document.getElementById("action-btn");

  const NEXT_LABEL =
    'Suivant <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>';

  function isLastStep() {
    return currentStep === totalSteps;
  }

  function updateActionState() {
    if (isLastStep()) {
      actionBtn.disabled = !form.checkValidity();
    } else {
      actionBtn.disabled = false;
    }
  }

  function render() {
    stepEls.forEach((el) => {
      el.hidden = Number(el.dataset.step) !== currentStep;
    });

    const active = stepEls.find((el) => Number(el.dataset.step) === currentStep);
    stepTitle.textContent = active.dataset.title;
    currentStepEl.textContent = currentStep;
    progressBar.style.width = (currentStep / totalSteps) * 100 + "%";

    prevBtn.hidden = currentStep === 1;
    resetBtn.hidden = !isLastStep();
    actionBtn.innerHTML = isLastStep() ? "Calculer mon empreinte" : NEXT_LABEL;

    updateActionState();
  }

  actionBtn.addEventListener("click", () => {
    if (isLastStep()) {
      if (form.reportValidity()) {
        form.submit();
      }
      return;
    }

    const active = stepEls.find((el) => Number(el.dataset.step) === currentStep);
    if (!active.querySelectorAll("input, select").length || isStepValid(active)) {
      currentStep++;
      render();
    }
  });

  function isStepValid(stepEl) {
    return Array.from(stepEl.querySelectorAll("input, select")).every((field) => {
      if (!field.checkValidity()) {
        field.reportValidity();
        return false;
      }
      return true;
    });
  }

  prevBtn.addEventListener("click", () => {
    if (currentStep > 1) {
      currentStep--;
      render();
    }
  });

  resetBtn.addEventListener("click", () => {
    form.reset();
    syncSliders();
    currentStep = 1;
    render();
  });

  form.addEventListener("input", updateActionState);
  form.addEventListener("change", updateActionState);

  function syncSliders() {
    form.querySelectorAll('input[type="range"]').forEach((range) => {
      const output = range.parentElement.querySelector(".slider-value");
      const unit = range.dataset.unit;
      output.textContent = range.value + (unit ? " " + unit : "");
    });
  }

  form.querySelectorAll('input[type="range"]').forEach((range) => {
    range.addEventListener("input", () => {
      const output = range.parentElement.querySelector(".slider-value");
      const unit = range.dataset.unit;
      output.textContent = range.value + (unit ? " " + unit : "");
    });
  });

  syncSliders();
  render();
</script>
