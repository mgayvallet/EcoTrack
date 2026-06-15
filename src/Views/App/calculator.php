<link rel="stylesheet" href="./style/calculator.css">
<hgroup>
  <h1>Calculez votre empreinte carbone</h1>
  <p>Répondez à quelques questions pour estimer votre impact environnemental annuel</p>
</hgroup>
<section>
  <div class="step-div">
    <hgroup>
      <h2>Calculateur d'empreinte carbone</h2>
      <p>Etape <span>1</span> sur 3</p>
    </hgroup>
    <div class="progress-container">
      <div class="progress-bar"></div>
    </div>
  </div>
  <form>
    <div>
      <h3>Transport</h3>
      <p>Vos déplacements hebdomadaires</p>
    </div>
    <div class="label-container">
      <label>
        Distance en voiture par ans (km)
        <input type="number" name="" id="" max="1000000" min="1" placeholder="1">
      </label>
      <label>
        Type de véhicule
        <select>
          <option value="Essence">Essence</option>
        </select>
      </label>
      <label>
        Vols courts par an
        <input type="number" name="" id="" max="25" min="1" placeholder="1">
      </label>
      <label>
        Vols longs par an
        <input type="number" name="" id="" max="25" min="1" placeholder="1">
      </label>
      <label>
        Distance en train par ans (km)
        <input type="number" name="" id="" max="1000000" min="1" placeholder="1">
      </label>
    </div>
  </form>
  <a href="/calculator2" class="link-calculator">
    Suivant
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
      <path d="M3 8h10M9 4l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
  </a>
</section>