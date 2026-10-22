"use strict";

/** KLABEN selling wizard -> Fluent Form #4 review enhancement.
 * Fluent Forms handles submit/validation; this script never submits or stores data.
 */
document.addEventListener("DOMContentLoaded", () => {
  const section = document.getElementById("verkoopformulier");
  const application = document.querySelector("#klaben-fluent-sell form");
  const review = section?.querySelector("[data-sell-review]");
  const journey = document.getElementById("klaben-valuation-form");
  const handoff = journey?.querySelector("[data-journey-continue]");
  const heading = section?.querySelector("#sell-application-title");
  const intro = section?.querySelector(".sell-application__intro");
  const edit = review?.querySelector("[data-sell-review-edit]");
  const reviewHeading = review?.querySelector("#sell-review-title");

  if (!section || !application || !review || !journey || !handoff ||
      !heading || !intro || !edit || !reviewHeading) return;

  const originalHeading = heading.innerHTML;
  const originalIntro = intro.textContent;
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const number = new Intl.NumberFormat("nl-NL");
  const field = (name) => application.elements.namedItem(name);
  const fieldValue = (name) => String(field(name)?.value ?? "").trim();
  const wizardValue = (name) =>
    String(journey.elements.namedItem(name)?.value ?? "").trim();
  const labels = {
    condition: { excellent: "Uitstekend", good: "Goed", attention: "Aandacht nodig" },
    maintenance: { known: "Bekend", partial: "Gedeeltelijk bekend", unknown: "Onbekend" },
    timing: { soon: "Zo snel mogelijk", month: "Binnen een maand", exploring: "Oriënterend" },
  };

  function updateReview() {
    const values = {
      brand: fieldValue("brand"),
      model: fieldValue("model"),
      year: fieldValue("year"),
      mileage: fieldValue("mileage") ? `${number.format(Number(fieldValue("mileage")))} km` : "",
      fuel: fieldValue("fuel"),
      transmission: fieldValue("transmission"),
      condition: labels.condition[wizardValue("condition")],
      maintenance: labels.maintenance[wizardValue("maintenance")],
      timing: labels.timing[wizardValue("timing")],
    };
    review.querySelectorAll("[data-sell-review-value]").forEach((element) => {
      element.textContent = values[element.dataset.sellReviewValue] || "—";
    });
  }

  function directEntry() {
    review.hidden = true;
    section.classList.remove("sell-application--review");
    heading.innerHTML = originalHeading;
    intro.textContent = originalIntro;
  }

  function reviewEntry() {
    const required = ["brand", "model", "year", "mileage", "fuel"];
    const ready = required.every((name) => field(name) && fieldValue(name) !== "");
    if (!ready) {
      directEntry();
      return;
    }

    section.classList.add("sell-application--review");
    review.hidden = false;
    heading.innerHTML = "Controleer je<br><span>gegevens.</span>";
    intro.textContent = "Je gegevens zijn overgenomen uit de waardebepaling. Controleer ze hieronder; de formuliergegevens kun je nog aanpassen.";
    updateReview();
    section.scrollIntoView({
      behavior: reducedMotion.matches ? "auto" : "smooth",
      block: "start",
    });
    reviewHeading.focus({ preventScroll: true });
  }

  // The existing sell.js wizard fills form values in its click listener first.
  handoff.addEventListener("click", () => requestAnimationFrame(reviewEntry));
  document.querySelectorAll('a[href="#verkoopformulier"]').forEach((link) => {
    link.addEventListener("click", directEntry);
  });
  edit.addEventListener("click", () => {
    field("brand")?.focus({ preventScroll: false });
    application.scrollIntoView({
      behavior: reducedMotion.matches ? "auto" : "smooth",
      block: "center",
    });
  });
  application.addEventListener("input", updateReview);
  application.addEventListener("change", updateReview);
  directEntry();
});
