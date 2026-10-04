document.addEventListener('DOMContentLoaded', () => {

    /* =========================
       MOBILE NAVIGATION
    ========================= */

    const toggle = document.querySelector(
        '.menu-toggle'
    );

    const navigation = document.querySelector(
        '.mobile-navigation'
    );

    if (toggle && navigation) {

        toggle.addEventListener('click', () => {

            const isOpen =
                navigation.classList.toggle(
                    'is-open'
                );

            toggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );

            toggle.setAttribute(
                'aria-label',
                isOpen
                    ? 'Close navigation menu'
                    : 'Open navigation menu'
            );

        });

    }


    /* =========================
       BACKGROUND MUSIC
    ========================= */

    const musicButton = document.querySelector(
        '.floating-music-button'
    );

    const audio = document.querySelector(
        '#dessert-affairs-music'
    );

    if (musicButton && audio) {

        musicButton.addEventListener(
            'click',
            async () => {

                const isPlaying =
                    !audio.paused;

                if (isPlaying) {

                    audio.pause();

                    musicButton.classList.remove(
                        'is-playing'
                    );

                    musicButton.setAttribute(
                        'aria-pressed',
                        'false'
                    );

                    musicButton.setAttribute(
                        'aria-label',
                        'Play background music'
                    );

                    const label =
                        musicButton.querySelector(
                            '.music-label'
                        );

                    if (label) {
                        label.textContent =
                            'Play Music';
                    }

                    return;
                }

                try {

                    await audio.play();

                    musicButton.classList.add(
                        'is-playing'
                    );

                    musicButton.setAttribute(
                        'aria-pressed',
                        'true'
                    );

                    musicButton.setAttribute(
                        'aria-label',
                        'Pause background music'
                    );

                    const label =
                        musicButton.querySelector(
                            '.music-label'
                        );

                    if (label) {
                        label.textContent =
                            'Pause Music';
                    }

                } catch (error) {

                    console.error(
                        'Audio could not be played.',
                        error
                    );

                }

            }
        );

    }

    /* =========================
        SHOP CATEGORY REVEAL
    ========================= */

    const categorySection = document.querySelector('.home-shop-categories');

    if (categorySection) {
        const categoryHeading = categorySection.querySelector(
            '.category-heading-reveal'
        );

        const categoryCards = categorySection.querySelectorAll(
            'li.product-category'
        );

        const revealItems = [];

        if (categoryHeading) {
            categoryHeading.classList.add('reveal-ready');
            revealItems.push(categoryHeading);
        }

        categoryCards.forEach((card, index) => {
            card.classList.add('reveal-ready');

            card.style.setProperty(
            '--reveal-delay',
            `${index * 300}ms`
            );

            revealItems.push(card);
        });

        const categoryObserver = new IntersectionObserver(
            (entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
            },
            {
            threshold: 0.15
            }
        );

        revealItems.forEach((item) => {
            categoryObserver.observe(item);
        });
    }

    /* =========================
        FEATURED PRODUCTS REVEAL
    ========================= */

    const featuredSection = document.querySelector('.featured-products');

    if (featuredSection) {
        const featuredHeading = featuredSection.querySelector(
            '.featured-heading-reveal'
        );

        const featuredProducts = featuredSection.querySelectorAll(
            'li.product'
        );

        const featuredRevealItems = [];

        if (featuredHeading) {
            featuredHeading.classList.add('reveal-ready');
            featuredRevealItems.push(featuredHeading);
        }

        featuredProducts.forEach((product, index) => {
            product.classList.add('reveal-ready');

            product.style.setProperty(
                '--reveal-delay',
                `${index * 120}ms`
            );

            featuredRevealItems.push(product);
        });

        const featuredObserver = new IntersectionObserver(
            (entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.15
        }
    );

    featuredRevealItems.forEach((item) => {
        featuredObserver.observe(item);
    });
    }

    /* =========================
        ABOUT CARD REVEAL
    ========================= */

    const aboutCard = document.querySelector('.about-card-reveal');

    if (aboutCard) {
        aboutCard.classList.add('reveal-ready');

        const aboutObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.2
            }
        );

        aboutObserver.observe(aboutCard);
    }

    /* =========================
        CATERING REVEAL
    ========================= */

    const cateringSection = document.querySelector('.catering-section');

    if (cateringSection) {
        const cateringImage = cateringSection.querySelector(
            '.catering-image-reveal'
        );

        const cateringContent = cateringSection.querySelector(
            '.catering-content-reveal'
        );

        const cateringItems = [
            cateringImage,
            cateringContent
        ].filter(Boolean);

        cateringItems.forEach((item) => {
            item.classList.add('reveal-ready');
        });

        const cateringObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.2
            }
        );

        cateringItems.forEach((item) => {
            cateringObserver.observe(item);
        });
    }

    /* =========================
        TESTIMONIAL CAROUSEL
    ========================= */

    const testimonialGrid = document.querySelector('.testimonials-grid');

    if (testimonialGrid) {
        const testimonialCards = testimonialGrid.querySelectorAll(
            '.testimonial-card'
        );

        if (testimonialCards.length > 1) {
            const dotsContainer = document.createElement('div');

            dotsContainer.classList.add('testimonial-dots');

            testimonialCards.forEach((card, index) => {
            const dot = document.createElement('button');

            dot.classList.add('testimonial-dot');

            dot.setAttribute(
                'aria-label',
                `View testimonial ${index + 1}`
            );

            if (index === 0) {
                dot.classList.add('is-active');
            }

            dot.addEventListener('click', () => {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            });

            dotsContainer.appendChild(dot);
        });

        testimonialGrid.after(dotsContainer);

        const dots = dotsContainer.querySelectorAll(
            '.testimonial-dot'
        );

        const testimonialObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const index = Array.from(
                    testimonialCards
                ).indexOf(entry.target);

                dots.forEach((dot) => {
                    dot.classList.remove('is-active');
                });

                if (dots[index]) {
                    dots[index].classList.add('is-active');
                }
            });
        },
        {
            root: testimonialGrid,
            threshold: 0.6
        }
    );

    testimonialCards.forEach((card) => {
      testimonialObserver.observe(card);
    });
  }
}

/* =========================
   GALLERY STAGGERED REVEAL
========================= */

const gallerySection = document.querySelector('.gallery-section');

if (gallerySection) {
  const galleryItems = gallerySection.querySelectorAll(
    '.gallery-item'
  );

  const revealDirections = [
    'reveal-left',
    'reveal-top',
    'reveal-right',
    'reveal-bottom'
  ];

  galleryItems.forEach((item, index) => {
    item.classList.add(
      'reveal-ready',
      revealDirections[index % revealDirections.length]
    );

    item.style.setProperty(
      '--reveal-delay',
      `${(index % 4) * 120}ms`
    );
  });

  const galleryObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    },
    {
      threshold: 0.15
    }
  );

  galleryItems.forEach((item) => {
    galleryObserver.observe(item);
  });
}

});