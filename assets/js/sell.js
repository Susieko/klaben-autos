"use strict";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("klaben-sell-form");
  if (!form) return;

  const fields = {
    brand: form.querySelector("#sell-brand"),
    model: form.querySelector("#sell-model"),
    year: form.querySelector("#sell-year"),
    mileage: form.querySelector("#sell-mileage"),
    fuel: form.querySelector("#sell-fuel"),
    transmission: form.querySelector("#sell-transmission"),
  };
  const targets = {
    title: document.querySelector("[data-preview-title]"),
    year: document.querySelector("[data-preview-year]"),
    mileage: document.querySelector("[data-preview-mileage]"),
    fuel: document.querySelector("[data-preview-fuel]"),
    transmission: document.querySelector("[data-preview-transmission]"),
    completed: document.querySelector("[data-preview-completed]"),
    progress: document.querySelector("[data-preview-progress]"),
  };
  const validKeys = ["brand", "model", "year", "mileage", "fuel"];
  const formatter = new Intl.NumberFormat("nl-NL");
  const text = (key) => fields[key]?.value.trim() ?? "";

  function updatePreview() {
    targets.title.textContent = [text("brand"), text("model")].filter(Boolean).join(" ") || "Merk & model";
    targets.year.textContent = text("year") || "—";
    targets.mileage.textContent = text("mileage") !== "" && Number.isFinite(Number(text("mileage")))
      ? `${formatter.format(Number(text("mileage")))} km` : "—";
    targets.fuel.textContent = text("fuel") || "—";
    targets.transmission.textContent = text("transmission") || "—";
    const complete = validKeys.filter((key) => fields[key].checkValidity() && text(key) !== "").length;
    targets.completed.textContent = `${complete} / ${validKeys.length}`;
    targets.progress.setAttribute("aria-valuenow", String(complete));
    targets.progress.querySelector("span").style.width = `${(complete / validKeys.length) * 100}%`;
  }
  form.addEventListener("input", updatePreview);
  form.addEventListener("change", updatePreview);
  updatePreview();

  const status = document.getElementById("form-status");
  if (status) status.focus({ preventScroll: true });
});

/* Auto verkopen V2 — basic format-only demonstration.
   Does NOT contact the RDW, request a valuation, or store plate numbers. */
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("sell-plate-form");
  const plate = document.getElementById("sell-license");
  const feedback = document.getElementById("sell-license-feedback");
  if (!form || !plate || !feedback) return;
  plate.addEventListener("input", () => {
    plate.value = plate.value.toUpperCase().replace(/[^A-Z0-9-]/g, "");
    feedback.replaceChildren();
    feedback.classList.remove("is-error");
    plate.removeAttribute("aria-invalid");
  });
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const compact = plate.value.toUpperCase().replace(/-/g, "");
    const looksPlausible = /^[A-Z0-9]{6}$/.test(compact) && /[A-Z]/.test(compact) && /[0-9]/.test(compact);
    feedback.replaceChildren();
    if (!looksPlausible) {
      plate.setAttribute("aria-invalid", "true");
      feedback.classList.add("is-error");
      const message = document.createElement("p");
      message.textContent = "Vul zes letters/cijfers in, bijvoorbeeld AB-123-C. Dit is alleen een opmaakcontrole.";
      feedback.append(message);
      plate.focus();
      return;
    }
    plate.removeAttribute("aria-invalid");
    feedback.classList.remove("is-error");
    const message = document.createElement("p");
    message.textContent = "De basisopmaak is herkend (demo). Dit bevestigt niet dat het kenteken bestaat; RDW-gegevens ophalen volgt in een latere versie.";
    const link = document.createElement("a");
    link.href = "#verkoopformulier";
    link.textContent = "Vul je voertuiggegevens in →";
    feedback.append(message, link);
  });
});
