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

});