"use strict";

// Enhance the sort form without removing its ordinary submit-button fallback.
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-autosubmit]").forEach((select) => {
        select.addEventListener("change", () => {
            if (select.form) select.form.requestSubmit();
        });
    });

    const main = document.querySelector("[data-gallery-main]");
    const thumbs = [...document.querySelectorAll("[data-gallery-thumb]")];
    if (!main || !thumbs.length) return;

    const counter = document.querySelector("[data-gallery-counter]");
    thumbs.forEach((thumb) => {
        thumb.addEventListener("click", () => {
            if (!thumb.dataset.image) return;
            main.src = thumb.dataset.image;
            thumbs.forEach((button) => {
                const active = button === thumb;
                button.classList.toggle("is-active", active);
                button.setAttribute("aria-pressed", String(active));
            });
            if (counter) {
                counter.textContent = `${String(thumb.dataset.galleryThumb).padStart(2, "0")} / ${String(thumbs.length).padStart(2, "0")}`;
            }
        });
    });
});
