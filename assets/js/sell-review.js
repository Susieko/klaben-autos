"use strict";

// KLABEN Autos — connect the existing wizard to an editable review.
// This file never submits, stores or sends user-entered vehicle information.
document.addEventListener("DOMContentLoaded", () => {
  const section = document.getElementById("verkoopformulier");
  const form = document.getElementById("klaben-sell-form");
  const review = document.querySelector("[data-sell-review]");
  const journey = document.getElementById("klaben-valuation-form");
  const handoff = journey?.querySelector("[data-journey-continue]");

  if (!section || !form || !review || !journey || !handoff) return;

  const vehicleFields = form.querySelector(".sell-form__group");
  const heading = section.querySelector("#sell-application-title");
  const intro = section.querySelector(".sell-application__intro");
  const edit = review.querySelector("[data-sell-review-edit]");
  const reviewHeading = review.querySelector("#sell-review-title");

  if (!vehicleFields || !heading || !intro || !edit || !reviewHeading) return;

  // Used only while reviewing. The original form remains the canonical
  // source for submission and its server-side validation is unchanged.
  vehicleFields.id = "sell-vehicle-fields";
  const originalHeading = heading.innerHTML;
  const originalIntro = intro.textContent;
  const integer = new Intl.NumberFormat("nl-NL");
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const fieldValue = (name) =>
    String(form.elements.namedItem(name)?.value ?? "").trim();
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
      mileage: fieldValue("mileage")
        ? `${integer.format(Number(fieldValue("mileage")))} km` : "",
      fuel: fieldValue("fuel"),
      transmission: fieldValue("transmission"),
      condition: labels.condition[wizardValue("condition")],
      maintenance: labels.maintenance[wizardValue("maintenance")],
      timing: labels.timing[wizardValue("timing")],
    };
    review.querySelectorAll("[data-sell-review-value]").forEach((el) => {
      el.textContent = values[el.dataset.sellReviewValue] || "—";
    });
  }

  function showApplication() {
    section.hidden = false;
  }

  function directEntry() {
    showApplication();
    section.classList.remove("sell-application--review");
    review.hidden = true;
    vehicleFields.hidden = false;
    edit.setAttribute("aria-expanded", "false");
    edit.lastChild.textContent = " Gegevens aanpassen";
    heading.innerHTML = originalHeading;
    intro.textContent = originalIntro;
  }

  function reviewEntry() {
    const required = ["brand", "model", "year", "mileage", "fuel"];
    const ready = required.every((name) => {
      const input = form.elements.namedItem(name);
      return input && input.checkValidity() && fieldValue(name) !== "";
    });
    if (!ready) {
      directEntry();
      return;
    }

    showApplication();
    section.classList.add("sell-application--review");
    review.hidden = false;
    vehicleFields.hidden = true;
    edit.setAttribute("aria-expanded", "false");
    edit.lastChild.textContent = " Gegevens aanpassen";
    heading.innerHTML = "Controleer je<br><span>gegevens.</span>";
    intro.textContent = "Je gegevens zijn overgenomen uit de waardebepaling. Controleer ze hieronder, voeg eventueel een opmerking toe en rond de demo af.";
    updateReview();

    section.scrollIntoView({
      behavior: reducedMotion.matches ? "auto" : "smooth",
      block: "start",
    });
    reviewHeading.focus({ preventScroll: true });
  }

  // Handoff executes in the earlier V3 handler first. Wait until the browser
  // frame so all original inputs have received their values before reviewing.
  handoff.addEventListener("click", () => {
    window.requestAnimationFrame(reviewEntry);
  });

  // An intentional alternative for people who skip the valuation wizard.
  document.querySelectorAll('a[href="#verkoopformulier"]').forEach((link) => {
    link.addEventListener("click", directEntry);
  });

  // Keep the original form reachable after demo redirect or by deep-link.
  window.addEventListener("hashchange", () => {
    if (window.location.hash === "#verkoopformulier") {
      directEntry();
    }
  });

  edit.addEventListener("click", () => {
    const opening = vehicleFields.hidden;
    vehicleFields.hidden = !opening;
    edit.setAttribute("aria-expanded", String(opening));
    edit.lastChild.textContent = opening ? " Bewerken sluiten" : " Gegevens aanpassen";
    if (opening) {
      form.elements.namedItem("brand")?.focus({ preventScroll: false });
    } else {
      updateReview();
    }
  });

  form.addEventListener("input", updateReview);
  form.addEventListener("change", updateReview);

  // If validation ever fails for a field hidden in review mode, reveal it
  // before the browser attempts to focus it.
  form.addEventListener("invalid", (event) => {
    if (vehicleFields.hidden && vehicleFields.contains(event.target)) {
      vehicleFields.hidden = false;
      edit.setAttribute("aria-expanded", "true");
      edit.lastChild.textContent = " Bewerken sluiten";
    }
  }, true);

  const params = new URLSearchParams(window.location.search);
  if (window.location.hash === "#verkoopformulier" || params.has("klaben_status")) {
    directEntry();
  } else {
    // With JS disabled, the original application remains fully accessible.
    section.hidden = true;
  }
});