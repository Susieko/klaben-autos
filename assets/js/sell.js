
"use strict";

// Existing vehicle form and live preview
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#klaben-fluent-sell form");
  if (!form) return;

  const fields = {
    brand: form.elements.namedItem("brand"),
    model: form.elements.namedItem("model"),
    year: form.elements.namedItem("year"),
    mileage: form.elements.namedItem("mileage"),
    fuel: form.elements.namedItem("fuel"),
    transmission: form.elements.namedItem("transmission"),
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
    targets.title.textContent =
      [text("brand"), text("model")].filter(Boolean).join(" ") ||
      "Merk & model";

    targets.year.textContent = text("year") || "—";

    targets.mileage.textContent =
      text("mileage") !== "" &&
      Number.isFinite(Number(text("mileage")))
        ? `${formatter.format(Number(text("mileage")))} km`
        : "—";

    targets.fuel.textContent = text("fuel") || "—";
    targets.transmission.textContent = text("transmission") || "—";

    const complete = validKeys.filter(
      (key) => fields[key]?.checkValidity() && text(key) !== ""
    ).length;

    targets.completed.textContent =
      `${complete} / ${validKeys.length}`;

    targets.progress.setAttribute(
      "aria-valuenow",
      String(complete)
    );

    targets.progress.querySelector("span").style.width =
      `${(complete / validKeys.length) * 100}%`;
  }

  form.addEventListener("input", updatePreview);
  form.addEventListener("change", updatePreview);
  updatePreview();

  const status = document.getElementById("form-status");
  if (status) status.focus({ preventScroll: true });
});


// Kenteken hero — format-only check, no RDW lookup
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("sell-plate-form");
  const plate = document.getElementById("sell-license");
  const feedback = document.getElementById(
    "sell-license-feedback"
  );

  if (!form || !plate || !feedback) return;

  plate.addEventListener("input", () => {
    plate.value = plate.value
      .toUpperCase()
      .replace(/[^A-Z0-9-]/g, "");

    feedback.replaceChildren();
    feedback.classList.remove("is-error");
    plate.removeAttribute("aria-invalid");
  });

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const compact = plate.value
      .toUpperCase()
      .replace(/-/g, "");

    const looksPlausible =
      /^[A-Z0-9]{6}$/.test(compact) &&
      /[A-Z]/.test(compact) &&
      /[0-9]/.test(compact);

    feedback.replaceChildren();

    if (!looksPlausible) {
      plate.setAttribute("aria-invalid", "true");
      feedback.classList.add("is-error");

      const message = document.createElement("p");
      message.textContent =
        "Vul zes letters/cijfers in, bijvoorbeeld AB-123-C. Dit is alleen een opmaakcontrole.";

      feedback.append(message);
      plate.focus();
      return;
    }

    plate.removeAttribute("aria-invalid");
    feedback.classList.remove("is-error");

    const message = document.createElement("p");
    message.textContent =
      "Kentekenopmaak herkend (demo). Dit bevestigt niet dat het kenteken bestaat; er is geen RDW-controle.";

    feedback.append(message);

    document.dispatchEvent(
      new CustomEvent("klaben:plate-ready", {
        detail: { plate: compact },
      })
    );

    document
      .getElementById("waardebepaling")
      ?.scrollIntoView({
        behavior: window.matchMedia(
          "(prefers-reduced-motion: reduce)"
        ).matches
          ? "auto"
          : "smooth",
        block: "start",
      });
  });
});


// Auto verkopen V3 — guided demo valuation
// No data is stored or sent by this wizard.
document.addEventListener("DOMContentLoaded", () => {
  const section = document.getElementById("waardebepaling");
  const form = document.getElementById(
    "klaben-valuation-form"
  );
  const application = document.querySelector("#klaben-fluent-sell form");

  if (!section || !form || !application) return;

  const panes = [
    ...form.querySelectorAll("[data-journey-step]"),
  ];

  const marks = [
    ...section.querySelectorAll("[data-journey-mark]"),
  ];

  const next = form.querySelector("[data-journey-next]");
  const back = form.querySelector("[data-journey-back]");

  const continueButton = form.querySelector(
    "[data-journey-continue]"
  );

  const title = section.querySelector(
    "[data-journey-panel-title]"
  );

  const progress = section.querySelector(
    "[data-journey-progress]"
  );

  const counter = section.querySelector(
    "[data-journey-counter]"
  );

  const plateNote = section.querySelector(
    "[data-journey-plate]"
  );

  const error = form.querySelector("[data-journey-error]");
  const range = form.querySelector("[data-journey-range]");
  const summary = form.querySelector(
    "[data-journey-summary]"
  );

  const names = [
    "Vertel ons over je auto",
    "Wat maakt jouw auto bijzonder?",
    "Jouw fictieve voorbeeldberekening",
  ];

  let currentStep = 0;
  let plate = "";

  const existingNotes =
    application.elements.namedItem("notes");

  existingNotes?.addEventListener("input", (event) => {
    if (event.isTrusted) {
      delete existingNotes.dataset.klabenAutoFilled;
    }
  });

  const money = (n) =>
    new Intl.NumberFormat("nl-NL", {
      style: "currency",
      currency: "EUR",
      maximumFractionDigits: 0,
    }).format(n);

  const value = (name) =>
    form.elements.namedItem(name)?.value?.trim() ?? "";

  function errorClear() {
    error.hidden = true;
    error.textContent = "";
  }

  function presentStep(step, { focus = true } = {}) {
    currentStep = step;

    panes.forEach((pane, index) => {
      pane.hidden = index !== step;
      pane.disabled = index !== step;
    });

    marks.forEach((mark, index) => {
      mark.classList.toggle("is-current", index === step);
      mark.classList.toggle("is-complete", index < step);

      if (index === step) {
        mark.setAttribute("aria-current", "step");
      } else {
        mark.removeAttribute("aria-current");
      }
    });

    counter.textContent = `0${step + 1} / 03`;
    title.textContent = names[step];

    progress.style.width =
      `${((step + 1) / 3) * 100}%`;

    back.hidden = step === 0;
    next.hidden = step === 2;
    continueButton.hidden = step !== 2;

    errorClear();

    if (step === 2) {
      renderResult();
    }

    if (focus) {
      title.setAttribute("tabindex", "-1");
      title.focus({ preventScroll: true });
    }
  }

  function validateStep() {
    const controls = [
      ...panes[currentStep].querySelectorAll(
        "input, select"
      ),
    ];

    const invalid = controls.find(
      (field) => !field.checkValidity()
    );

    if (invalid) {
      invalid.setAttribute("aria-invalid", "true");

      error.textContent =
        invalid.type === "radio"
          ? "Selecteer eerst de staat van je auto."
          : "Vul de verplichte velden geldig in om door te gaan.";

      error.hidden = false;
      invalid.focus();
      return false;
    }

    return true;
  }

  function renderResult() {
    // Fictional demonstration only.
    // This is NOT a real vehicle valuation.

    const year = Number(value("year"));
    const kilometers = Number(value("mileage"));

    const age = Math.max(
      0,
      Math.min(40, new Date().getFullYear() - year)
    );

    const conditionWeight = {
      excellent: 1.08,
      good: 1,
      attention: 0.78,
    };

    const state = value("condition");
    const ageFactor = Math.pow(0.88, age);

    const kmFactor = Math.max(
      0.55,
      1 -
        Math.max(0, kilometers - age * 12000) /
          350000
    );

    const simulatedCenter = Math.max(
      1000,
      Math.round(
        (28000 *
          ageFactor *
          kmFactor *
          (conditionWeight[state] ?? 1)) /
          100
      ) * 100
    );

    const low =
      Math.round((simulatedCenter * 0.9) / 100) *
      100;

    const high =
      Math.round((simulatedCenter * 1.1) / 100) *
      100;

    range.textContent =
      `${money(low)} – ${money(high)}`;

    const conditionText = {
      excellent: "Uitstekend",
      good: "Goed",
      attention: "Aandacht nodig",
    }[state] ?? "Onbekend";

    const rows = [
      [
        "Voertuig",
        `${value("brand")} ${value("model")}`,
      ],
      ["Bouwjaar", value("year")],
      [
        "Kilometers",
        `${new Intl.NumberFormat("nl-NL").format(
          kilometers
        )} km`,
      ],
      ["Staat", conditionText],
    ];

    summary.replaceChildren();

    rows.forEach(([label, val]) => {
      const row = document.createElement("div");
      const dt = document.createElement("span");
      const dd = document.createElement("strong");

      dt.textContent = label;
      dd.textContent = val;

      row.append(dt, dd);
      summary.append(row);
    });
  }

  form.addEventListener("input", (event) => {
    if (event.target instanceof HTMLElement) {
      event.target.removeAttribute("aria-invalid");
    }

    errorClear();
  });

  form.addEventListener("submit", (event) => {
    event.preventDefault();
  });

  next.addEventListener("click", () => {
    if (!validateStep()) return;

    presentStep(
      Math.min(currentStep + 1, 2)
    );
  });

  back.addEventListener("click", () => {
    presentStep(
      Math.max(currentStep - 1, 0)
    );
  });

  continueButton.addEventListener("click", () => {
    // Transfer values to the existing form.
    // This does not submit anything.

    [
      "brand",
      "model",
      "year",
      "mileage",
      "fuel",
      "transmission",
    ].forEach((key) => {
      const destination =
        application.elements.namedItem(key);

      if (!destination) return;

      destination.value = value(key);

      destination.dispatchEvent(
        new Event("input", { bubbles: true })
      );

      destination.dispatchEvent(
        new Event("change", { bubbles: true })
      );
    });

    const noteField =
      application.elements.namedItem("notes");

    if (noteField) {
      const condition =
        {
          excellent: "Uitstekend",
          good: "Goed",
          attention: "Aandacht nodig",
        }[value("condition")] || "Onbekend";

      const maintenance =
        {
          known: "bekend",
          partial: "gedeeltelijk bekend",
          unknown: "onbekend",
        }[value("maintenance")] || "onbekend";

      const timing =
        {
          soon: "zo snel mogelijk",
          month: "binnen een maand",
          exploring: "oriënterend",
        }[value("timing")] || "oriënterend";

      const additions =
        `Voertuigstaat: ${condition}. ` +
        `Onderhoud: ${maintenance}. ` +
        `Verkoopmoment: ${timing}.`;

      // Preserve notes written manually.
      if (
        !noteField.value.trim() ||
        noteField.dataset.klabenAutoFilled === "1"
      ) {
        noteField.value = additions.slice(
          0,
          noteField.maxLength > 0
            ? noteField.maxLength
            : 800
        );

        noteField.dataset.klabenAutoFilled = "1";

        noteField.dispatchEvent(
          new Event("input", { bubbles: true })
        );
      }
    }

    const target =
      document.getElementById("verkoopformulier");

    target?.scrollIntoView({
      behavior: window.matchMedia(
        "(prefers-reduced-motion: reduce)"
      ).matches
        ? "auto"
        : "smooth",
      block: "start",
    });

    application.elements
      .namedItem("brand")
      ?.focus({ preventScroll: true });
  });

  document.addEventListener(
    "klaben:plate-ready",
    (event) => {
      plate = String(event.detail?.plate ?? "");

      plateNote.textContent =
        `Kenteken ${plate} herkend op basis van ` +
        `opmaak (demo). Vul zelf de voertuiggegevens ` +
        `in; er is geen RDW-opvraging.`;

      presentStep(0, { focus: false });
    }
  );

  section.hidden = false;
  presentStep(0, { focus: false });
});
