"use strict";

document.addEventListener("DOMContentLoaded", () => {
    const menuButton = document.querySelector(".menu-toggle");
    const navigation = document.querySelector(".site-nav");

    if (!menuButton || !navigation) {
        return;
    }

    const closeMenu = () => {
        menuButton.classList.remove("is-active");
        navigation.classList.remove("is-open");
        document.body.classList.remove("nav-open");

        menuButton.setAttribute("aria-expanded", "false");
        menuButton.setAttribute("aria-label", "Open navigatie");
    };

    const openMenu = () => {
        menuButton.classList.add("is-active");
        navigation.classList.add("is-open");
        document.body.classList.add("nav-open");

        menuButton.setAttribute("aria-expanded", "true");
        menuButton.setAttribute("aria-label", "Sluit navigatie");
    };

    menuButton.addEventListener("click", () => {
        const isOpen =
            menuButton.getAttribute("aria-expanded") === "true";

        if (isOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    navigation.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", closeMenu);
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeMenu();
        }
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth > 900) {
            closeMenu();
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const filterButtons =
        document.querySelectorAll(".car-filter");

    const carCards =
        document.querySelectorAll(".car-card");

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const selectedFilter =
                button.dataset.filter;

            filterButtons.forEach((filterButton) => {
                filterButton.classList.remove("is-active");
            });

            button.classList.add("is-active");

            carCards.forEach((card) => {
                const category = card.dataset.category;

                const shouldShow =
                    selectedFilter === "all" ||
                    selectedFilter === category;

                card.classList.toggle(
                    "is-hidden",
                    !shouldShow
                );
            });
        });
    });

    const favouriteButtons =
        document.querySelectorAll(
            ".car-card__favourite"
        );

    favouriteButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const isActive =
                button.classList.toggle("is-active");

            button.setAttribute(
                "aria-pressed",
                String(isActive)
            );

            button.setAttribute(
                "aria-label",
                isActive
                    ? "Verwijder uit favorieten"
                    : "Voeg toe aan favorieten"
            );
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const revealElements =
        document.querySelectorAll(".reveal");

    if (!revealElements.length) {
        return;
    }

    const prefersReducedMotion =
        window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;

    if (prefersReducedMotion) {
        revealElements.forEach((element) => {
            element.classList.add("is-visible");
        });

        return;
    }

    const observer = new IntersectionObserver(
        (entries, revealObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add("is-visible");
                revealObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.14,
            rootMargin: "0px 0px -70px 0px",
        }
    );

    revealElements.forEach((element) => {
        observer.observe(element);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const hoverImages = document.querySelectorAll(
        ".car-hover-image[data-car-images]"
    );

    hoverImages.forEach((image) => {
        let images;

        try {
            images = JSON.parse(image.dataset.carImages);
        } catch (error) {
            console.error("De auto-afbeeldingen konden niet worden geladen.", error);
            return;
        }

        // Cars with only one image do not need the hover effect.
        if (!Array.isArray(images) || images.length < 2) {
            return;
        }

        let currentIndex = 0;
        let imageInterval = null;
        let fadeTimeout = null;

        // Load all angles beforehand to prevent flashing.
        images.forEach((imageUrl) => {
            const preloadedImage = new Image();
            preloadedImage.src = imageUrl;
        });

        const showImage = (index) => {
            window.clearTimeout(fadeTimeout);

            image.classList.add("is-changing");

            fadeTimeout = window.setTimeout(() => {
                image.src = images[index];
                image.classList.remove("is-changing");
            }, 160);
        };

        const showNextImage = () => {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
        };

        image.addEventListener("mouseenter", () => {
            if (imageInterval !== null) {
                return;
            }

            showNextImage();

            imageInterval = window.setInterval(() => {
                showNextImage();
            }, 950);
        });

        image.addEventListener("mouseleave", () => {
            window.clearInterval(imageInterval);
            window.clearTimeout(fadeTimeout);

            imageInterval = null;
            currentIndex = 0;

            showImage(0);
        });
    });
});