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
