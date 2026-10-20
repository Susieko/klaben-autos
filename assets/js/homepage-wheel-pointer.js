"use strict";

/**
 * KLABEN — restrained alloy-wheel pointer accent.
 * Only visible over the homepage hero car area and featured car photos.
 * It does not hide or replace the native cursor.
 */
(() => {
  const init = () => {
    const hero = document.querySelector(".hero--layered");
    const images = Array.from(document.querySelectorAll(".featured-cars .car-card__image-link"));
    if (!hero && !images.length) return;

    const desktop = window.matchMedia("(min-width: 1000px) and (hover: hover) and (pointer: fine)");
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const enabled = () => desktop.matches && !reducedMotion.matches;

    const wheel = document.createElement("div");
    wheel.className = "klaben-wheel-cursor";
    wheel.setAttribute("aria-hidden", "true");
    // Static, author-controlled SVG; no input values are inserted into this markup.
    wheel.innerHTML = `
      <svg class="klaben-wheel-cursor__svg" viewBox="0 0 40 40" fill="none" aria-hidden="true">
        <circle cx="20" cy="20" r="18" fill="#101A25" stroke="#A9C0D8" stroke-width="2.6" />
        <circle cx="20" cy="20" r="14.7" stroke="#4E6F8F" stroke-width="1" />
        <g class="klaben-wheel-cursor__spokes">
          <path d="M18 16 16.5 6.8 23.5 6.8 22 16 20 19Z" fill="#B4C9DE" />
          <path d="M18 16 16.5 6.8 23.5 6.8 22 16 20 19Z" fill="#B4C9DE" transform="rotate(72 20 20)" />
          <path d="M18 16 16.5 6.8 23.5 6.8 22 16 20 19Z" fill="#B4C9DE" transform="rotate(144 20 20)" />
          <path d="M18 16 16.5 6.8 23.5 6.8 22 16 20 19Z" fill="#B4C9DE" transform="rotate(216 20 20)" />
          <path d="M18 16 16.5 6.8 23.5 6.8 22 16 20 19Z" fill="#B4C9DE" transform="rotate(288 20 20)" />
        </g>
        <circle cx="20" cy="20" r="4.2" fill="#496784" stroke="#E1EFFB" stroke-width="1.6" />
        <circle cx="20" cy="20" r="1" fill="#E1EFFB" />
      </svg>
      <span class="klaben-wheel-cursor__caption">VERKEN</span>
    `;
    document.body.append(wheel);

    let active = null;
    let frame = 0;
    let lastX = null;
    let lastY = null;
    let rotation = 0;

    function hide() {
      if (frame) cancelAnimationFrame(frame);
      frame = 0;
      active = null;
      lastX = null;
      lastY = null;
      wheel.classList.remove("is-visible");
    }

    function track(event, region) {
      if (!enabled() || event.pointerType === "touch") {
        hide();
        return;
      }

      if (region === hero) {
        const bounds = hero.getBoundingClientRect();
        const relativeX = (event.clientX - bounds.left) / bounds.width;
        const overUi = event.target instanceof Element && Boolean(
          event.target.closest("a, button, input, select, textarea, .hero-search-wrap, .hero__cards, .hero__content")
        );
        // The car occupies the right-hand side; leave menus, text and form alone.
        if (relativeX < 0.54 || overUi) {
          hide();
          return;
        }
      }

      active = region;
      if (lastX !== null && lastY !== null) {
        const travel = Math.hypot(event.clientX - lastX, event.clientY - lastY);
        rotation += Math.min(travel * 0.85, 28);
      }
      lastX = event.clientX;
      lastY = event.clientY;
      const x = Math.min(window.innerWidth - 95, event.clientX + 17);
      const y = Math.min(window.innerHeight - 55, event.clientY + 18);

      if (frame) cancelAnimationFrame(frame);
      frame = requestAnimationFrame(() => {
        wheel.style.setProperty("--klaben-mouse-x", `${Math.max(0, x)}px`);
        wheel.style.setProperty("--klaben-mouse-y", `${Math.max(0, y)}px`);
        wheel.style.setProperty("--klaben-mouse-spin", `${rotation.toFixed(1)}deg`);
        wheel.classList.add("is-visible");
        frame = 0;
      });
    }

    const regions = [...(hero ? [hero] : []), ...images];
    for (const region of regions) {
      region.addEventListener("pointermove", (event) => track(event, region), { passive: true });
      region.addEventListener("pointerleave", hide, { passive: true });
      region.addEventListener("pointercancel", hide, { passive: true });
    }

    window.addEventListener("blur", hide);
    window.addEventListener("scroll", hide, { passive: true });
    const disableIfNeeded = () => { if (!enabled()) hide(); };
    desktop.addEventListener("change", disableIfNeeded);
    reducedMotion.addEventListener("change", disableIfNeeded);
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
