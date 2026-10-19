/* Shared illustrative finance math — KLABEN Autos (no external requests). */
(function (global) {
  "use strict";
  const ANNUAL_NOMINAL_RATE = 0.079;
  const formatter = new Intl.NumberFormat("nl-NL", { style: "currency", currency: "EUR", maximumFractionDigits: 0 });
  const decimalFormatter = new Intl.NumberFormat("nl-NL", { style: "currency", currency: "EUR", minimumFractionDigits: 2, maximumFractionDigits: 2 });

  function calculate({ price, down, months, rate = ANNUAL_NOMINAL_RATE }) {
    const valid = [price, down, months, rate].every(Number.isFinite) &&
      price >= 0 && down >= 0 && down <= price && months > 0 &&
      Number.isInteger(months) && rate >= 0;
    if (!valid) return null;
    const principal = price - down;
    const monthlyRate = rate / 12;
    const monthly = principal === 0 ? 0 : monthlyRate === 0 ? principal / months :
      principal * monthlyRate / (1 - Math.pow(1 + monthlyRate, -months));
    const total = monthly * months;
    return { price, down, principal, months, monthly, total, interest: Math.max(0, total - principal), rate };
  }
  global.KlabenFinance = Object.freeze({
    ANNUAL_NOMINAL_RATE,
    calculate,
    euro: (value, digits = 0) => (digits === 2 ? decimalFormatter : formatter).format(value)
  });
})(window);
