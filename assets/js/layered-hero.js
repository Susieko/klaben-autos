"use strict";

(() => {
    const hero = document.querySelector('.hero--layered');
    if (!hero) return;

    const finePointer = window.matchMedia('(min-width: 901px) and (hover: hover) and (pointer: fine)');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let frame = 0;

    function reset() {
        if (frame) cancelAnimationFrame(frame);
        frame = 0;
        hero.style.setProperty('--klaben-shift-x', '0px');
        hero.style.setProperty('--klaben-shift-y', '0px');
        hero.style.setProperty('--klaben-light-x', '75%');
        hero.style.setProperty('--klaben-light-y', '47%');
    }

    hero.addEventListener('pointermove', (event) => {
        if (!finePointer.matches || reducedMotion.matches) return;
        const rect = hero.getBoundingClientRect();
        const x = Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width));
        const y = Math.max(0, Math.min(1, (event.clientY - rect.top) / rect.height));
        if (frame) cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
            // Less than 8 px at the extremes: depth without a floating vehicle.
            hero.style.setProperty('--klaben-shift-x', `${((x - .5) * -12).toFixed(1)}px`);
            hero.style.setProperty('--klaben-shift-y', `${((y - .5) * -7).toFixed(1)}px`);
            hero.style.setProperty('--klaben-light-x', `${(x * 100).toFixed(1)}%`);
            hero.style.setProperty('--klaben-light-y', `${(y * 100).toFixed(1)}%`);
            frame = 0;
        });
    }, { passive: true });
    hero.addEventListener('pointerleave', reset);
    finePointer.addEventListener('change', reset);
    reducedMotion.addEventListener('change', reset);
})();
