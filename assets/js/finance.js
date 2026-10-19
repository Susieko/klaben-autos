/* KLABEN Autos — illustrative finance calculations, no data is sent anywhere. */
"use strict";
document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector("[data-finance-calculator]");
  if (!root) return;

  const car = root.querySelector("[data-finance-car]");
  const priceInput = root.querySelector("#finance-price");
  const downInput = root.querySelector("#finance-down");
  const termInputs = [...root.querySelectorAll('input[name="finance-term"]')];
  const heroImage = document.querySelector("[data-finance-hero-image]");
  const error = document.querySelector("[data-finance-error]");
  const fields = {
    name: root.querySelector("[data-finance-car-label]"),
    monthly: root.querySelector("[data-finance-monthly]"),
    price: root.querySelector("[data-finance-price]"),
    down: root.querySelector("[data-finance-down]"),
    principal: root.querySelector("[data-finance-principal]"),
    months: root.querySelector("[data-finance-months]"),
    interest: root.querySelector("[data-finance-interest]"),
    total: root.querySelector("[data-finance-total]"),
    barDown: root.querySelector("[data-finance-bar-down]"),
    barLoan: root.querySelector("[data-finance-bar-loan]")
  };

  // Same shared formula as the homepage mini calculator.
  const finance = window.KlabenFinance;
  if (!finance) return;
  const euro = finance.euro;

  function update() {
    const price = Number(priceInput.value);
    const down = Number(downInput.value);
    const months = Number(termInputs.find(input => input.checked)?.value || 60);
    const valid = priceInput.value !== "" && downInput.value !== "" &&
      Number.isFinite(price) && Number.isFinite(down) &&
      price >= 1000 && price <= 250000 && down >= 0 && down <= price;

    priceInput.setAttribute("aria-invalid", String(!valid && (priceInput.value === "" || price < 1000 || price > 250000)));
    downInput.setAttribute("aria-invalid", String(!valid && (downInput.value === "" || down < 0 || down > price)));
    error.hidden = valid;

    if (!valid) {
      fields.monthly.textContent = "—";
      for (const name of ["price", "down", "principal", "months", "interest", "total"]) fields[name].textContent = "—";
      fields.barDown.style.width = "0%";
      fields.barLoan.style.width = "0%";
      return;
    }

    const { principal, monthly, total, interest } = finance.calculate({ price, down, months });
    fields.monthly.textContent = euro(monthly, 2);
    fields.price.textContent = euro(price);
    fields.down.textContent = euro(down);
    fields.principal.textContent = euro(principal);
    fields.months.textContent = `${months} maanden`;
    fields.interest.textContent = euro(interest, 2);
    fields.total.textContent = euro(total, 2);
    fields.barDown.style.width = `${down / price * 100}%`;
    fields.barLoan.style.width = `${principal / price * 100}%`;
  }

  car.addEventListener("change", () => {
    if (car.value === "custom") {
      fields.name.textContent = "Eigen rekenbedrag";
      update();
      return;
    }
    const option = car.selectedOptions[0];
    const chosenPrice = Number(option.dataset.price);
    priceInput.value = chosenPrice;
    downInput.max = String(chosenPrice);
    downInput.value = Math.min(Number(downInput.value) || 0, chosenPrice);
    fields.name.textContent = option.textContent.trim();
    // Only theme-provided URLs exist in these option attributes.
    if (heroImage && option.dataset.image) heroImage.src = option.dataset.image;
    update();
  });

  priceInput.addEventListener("input", () => {
    // A modified price is no longer the original catalogue price.
    if (car.value !== "custom") {
      car.value = "custom";
      fields.name.textContent = "Eigen rekenbedrag";
    }
    downInput.max = String(Math.max(0, Number(priceInput.value) || 0));
    update();
  });
  downInput.addEventListener("input", update);
  termInputs.forEach(input => input.addEventListener("change", update));
  // No form submission: scenario calculations happen entirely on the visitor's device.
  root.querySelector("form")?.addEventListener("submit", e => e.preventDefault());
  update();
});
