/* Enhances the existing homepage preview without changing front-page.php. */
(function () {
  "use strict";
  document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector(".finance-section .finance-calculator");
    const finance = window.KlabenFinance;
    const cars = Array.isArray(window.klabenFinanceHomeCars) ? window.klabenFinanceHomeCars : [];
    if (!root || !finance || !cars.length || root.dataset.enhanced === "true") return;

    const rows = [...root.querySelectorAll(".finance-calculator__row")];
    const result = root.querySelector(".finance-calculator__result");
    const notice = root.querySelector(".finance-calculator__notice");
    if (rows.length < 3 || !result || !notice) return;

    const featured = cars[0];
    const state = { car: featured, price: featured.price, down: Math.min(5000, featured.price), months: 60 };
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

    // Insert accessible native controls; the server-rendered static values are our no-JS fallback.
    const controls = document.createElement("div");
    controls.className = "klaben-finance-home-controls";
    controls.innerHTML = `
      <label class="klaben-finance-home-label" for="klaben-home-car">Bereken voor</label>
      <select id="klaben-home-car" class="klaben-finance-home-select" aria-label="Kies een auto voor de berekening"></select>
      <div class="klaben-finance-home-down-label">
        <label for="klaben-home-down">Aanbetaling aanpassen</label>
        <output id="klaben-home-down-output" for="klaben-home-down"></output>
      </div>
      <input id="klaben-home-down" type="range" min="0" max="20000" step="250" value="5000" aria-describedby="klaben-home-rate">
      <div class="klaben-finance-home-range-ends" aria-hidden="true"><span>€ 0</span><span data-down-max>€ 20.000</span></div>
      <fieldset class="klaben-finance-home-terms">
        <legend>Looptijd</legend>
        <div class="klaben-finance-home-term-options">
          ${[36, 48, 60, 72].map(months => `<label><input type="radio" name="klaben-home-term" value="${months}" ${months === 60 ? 'checked' : ''}><span>${months} mnd</span></label>`).join("")}
        </div>
      </fieldset>
      <p id="klaben-home-rate" class="klaben-finance-home-rate">Fictieve nominale rente: 7,9% per jaar</p>
    `;
    const carSelect = controls.querySelector("select");
    cars.forEach(car => {
      const option = document.createElement("option");
      option.value = car.slug;
      option.textContent = car.label;
      carSelect.append(option);
    });
    const downRange = controls.querySelector("#klaben-home-down");
    const downOutput = controls.querySelector("#klaben-home-down-output");
    const downMaxLabel = controls.querySelector("[data-down-max]");
    const termInputs = [...controls.querySelectorAll('input[name="klaben-home-term"]')];
    root.querySelector(".finance-calculator__header").after(controls);
    rows[2].classList.add("klaben-finance-home-static-term");

    // Amount is decorative during animation, updated accessibly only once per change.
    result.querySelector("p")?.replaceChildren();
    const number = document.createElement("span");
    number.className = "klaben-finance-home-number";
    number.setAttribute("aria-hidden", "true");
    const suffix = document.createElement("small");
    suffix.textContent = "per maand";
    const visual = result.querySelector("p");
    if (!visual) return;
    visual.append(number, suffix);
    const announcement = document.createElement("span");
    announcement.className = "klaben-finance-home-sr-only";
    announcement.setAttribute("aria-live", "polite");
    announcement.setAttribute("aria-atomic", "true");
    result.append(announcement);

    notice.textContent = "Illustratieve berekening met een fictieve nominale rente van 7,9% per jaar. Geen kredietaanbod; werkelijke voorwaarden en kosten kunnen afwijken.";
    const cta = root.closest(".finance-panel")?.querySelector('.finance-panel__content a[href*="financiering"]');
    const originalHref = cta?.getAttribute("href") || "";
    let animationId = 0;
    let displayed = NaN;

    function animateMonthly(target) {
      window.cancelAnimationFrame(animationId);
      const start = Number.isFinite(displayed) ? displayed : target;
      if (reducedMotion.matches || Math.abs(start - target) < 0.01) {
        displayed = target;
        number.textContent = finance.euro(target);
        return;
      }
      const startTime = performance.now();
      const duration = 360;
      const tick = (now) => {
        const p = Math.min(1, (now - startTime) / duration);
        const eased = 1 - Math.pow(1 - p, 3);
        displayed = start + (target - start) * eased;
        number.textContent = finance.euro(displayed);
        if (p < 1) animationId = window.requestAnimationFrame(tick);
        else displayed = target;
      };
      animationId = window.requestAnimationFrame(tick);
    }

    function render() {
      const outcome = finance.calculate(state);
      if (!outcome) return;
      rows[0].querySelector("strong").textContent = finance.euro(outcome.price);
      rows[1].querySelector("strong").textContent = finance.euro(outcome.down);
      rows[2].querySelector("strong").textContent = `${outcome.months} maanden`;
      downOutput.textContent = finance.euro(outcome.down);
      downRange.setAttribute("aria-valuetext", finance.euro(outcome.down));
      animateMonthly(outcome.monthly);
      announcement.textContent = `Geschat maandbedrag: ${finance.euro(outcome.monthly, 2)} per maand`;
      if (cta && originalHref) {
        // The chosen car and its price are read from our existing theme data.
        const target = new URL(originalHref, document.baseURI);
        target.searchParams.set("auto", state.car.slug);
        cta.href = target.href;
      }
    }

    function setCar(car) {
      state.car = car;
      state.price = car.price;
      const maxDown = Math.min(20000, car.price);
      downRange.max = String(maxDown);
      state.down = Math.min(state.down, maxDown);
      downRange.value = String(state.down);
      downMaxLabel.textContent = finance.euro(maxDown);
      render();
    }

    carSelect.addEventListener("change", () => {
      const chosen = cars.find(c => c.slug === carSelect.value);
      if (chosen) setCar(chosen);
    });
    downRange.addEventListener("input", () => {
      state.down = Number(downRange.value);
      render();
    });
    termInputs.forEach(input => input.addEventListener("change", () => {
      if (input.checked) {
        state.months = Number(input.value);
        render();
      }
    }));
    root.dataset.enhanced = "true";
    setCar(featured);
  });
})();
