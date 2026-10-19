"use strict";

/* Klaben visual refinement: small enhancements only; no dependency or new data model. */
document.addEventListener("DOMContentLoaded", () => {
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const finePointer = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

  // A very faint light source tracks across the hero on desktop.
  const hero = document.querySelector(".hero");
  if (hero && finePointer && !reduceMotion) {
    let scheduled = false;
    let lastEvent = null;
    hero.addEventListener("pointermove", (event) => {
      lastEvent = event;
      if (scheduled) return;
      scheduled = true;
      window.requestAnimationFrame(() => {
        scheduled = false;
        if (!lastEvent) return;
        const bounds = hero.getBoundingClientRect();
        const x = Math.max(0, Math.min(100, (lastEvent.clientX - bounds.left) * 100 / bounds.width));
        const y = Math.max(0, Math.min(100, (lastEvent.clientY - bounds.top) * 100 / bounds.height));
        hero.style.setProperty("--klaben-glow-x", `${x.toFixed(1)}%`);
        hero.style.setProperty("--klaben-glow-y", `${y.toFixed(1)}%`);
      });
    }, { passive: true });
    hero.addEventListener("pointerleave", () => {
      hero.style.removeProperty("--klaben-glow-x");
      hero.style.removeProperty("--klaben-glow-y");
    });
  }

  // Stagger the inventory/news cards only when the viewer actually reaches them.
  // Skip hidden/filter-excluded cards and leave content fully visible without JS.
  if (!reduceMotion && "IntersectionObserver" in window) {
    const cards = [...document.querySelectorAll(".car-grid .car-card, .inventory-grid .car-card, .news-list .news-card")];
    const observer = new IntersectionObserver((entries, watcher) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;
        const el = entry.target;
        const peers = [...(el.parentElement?.children || [])];
        el.style.setProperty("--klaben-delay", `${Math.min(peers.indexOf(el) % 3, 2) * 85}ms`);
        el.classList.add("klaben-in-view");
        watcher.unobserve(el);
      }
    }, { threshold: 0.07, rootMargin: "0px 0px -18px 0px" });
    for (const card of cards) {
      if (card.classList.contains("is-hidden")) continue;
      card.classList.add("klaben-can-reveal");
      observer.observe(card);
    }
  }

  // Gallery controls: mouse, keyboard arrow keys when a thumbnail has focus, and touch swipe.
  const gallery = document.querySelector(".vehicle-gallery");
  const thumbs = [...document.querySelectorAll("[data-gallery-thumb]")];
  if (gallery && thumbs.length > 1) {
    gallery.setAttribute("aria-label", "Autofoto's, veeg voor de volgende foto");
    const step = (direction, moveFocus = false) => {
      const current = thumbs.findIndex((thumb) => thumb.classList.contains("is-active"));
      const next = (Math.max(current, 0) + direction + thumbs.length) % thumbs.length;
      thumbs[next].click();
      if (moveFocus) thumbs[next].focus();
    };
    for (const thumb of thumbs) {
      thumb.addEventListener("keydown", (event) => {
        if (event.key !== "ArrowLeft" && event.key !== "ArrowRight") return;
        event.preventDefault();
        step(event.key === "ArrowRight" ? 1 : -1, true);
      });
    }
    let start = null;
    gallery.addEventListener("touchstart", (event) => {
      if (event.touches.length !== 1) return;
      start = { x: event.touches[0].clientX, y: event.touches[0].clientY };
    }, { passive: true });
    gallery.addEventListener("touchend", (event) => {
      if (!start || !event.changedTouches.length) return;
      const dx = event.changedTouches[0].clientX - start.x;
      const dy = event.changedTouches[0].clientY - start.y;
      if (Math.abs(dx) > 55 && Math.abs(dx) > Math.abs(dy) * 1.35) step(dx < 0 ? 1 : -1);
      start = null;
    }, { passive: true });
  }
});
