<script>
    (function () {
        const storageKey = 'kursivalut-theme';
        const root = document.documentElement;
        const toggles = Array.from(document.querySelectorAll('[data-theme-toggle]'));
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

        if (!toggles.length) {
            return;
        }

        const readStoredTheme = function () {
            try {
                const value = window.localStorage.getItem(storageKey);
                return value === 'dark' || value === 'light' ? value : null;
            } catch (error) {
                return null;
            }
        };

        const getSystemTheme = function () {
            return mediaQuery.matches ? 'dark' : 'light';
        };

        const getResolvedTheme = function () {
            return readStoredTheme() || getSystemTheme();
        };

        const syncButtons = function (theme) {
            toggles.forEach(function (toggle) {
                const state = toggle.querySelector('[data-theme-toggle-state]');
                const nextTheme = theme === 'dark' ? 'светлую' : 'темную';

                toggle.dataset.theme = theme;
                toggle.setAttribute('aria-pressed', String(theme === 'dark'));
                toggle.setAttribute('aria-label', 'Переключить на ' + nextTheme + ' тему');

                if (state) {
                    state.textContent = theme === 'dark' ? 'Темная' : 'Светлая';
                }
            });
        };

        const applyTheme = function (theme, persist) {
            root.classList.toggle('dark', theme === 'dark');
            root.dataset.theme = theme;
            syncButtons(theme);

            if (!persist) {
                return;
            }

            try {
                window.localStorage.setItem(storageKey, theme);
            } catch (error) {
                // Ignore storage write errors and keep the in-memory theme.
            }
        };

        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const currentTheme = root.classList.contains('dark') ? 'dark' : 'light';
                const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyTheme(nextTheme, true);
            });
        });

        mediaQuery.addEventListener('change', function () {
            if (readStoredTheme()) {
                return;
            }

            applyTheme(getSystemTheme(), false);
        });

        window.addEventListener('storage', function (event) {
            if (event.key !== storageKey) {
                return;
            }

            applyTheme(getResolvedTheme(), false);
        });

        syncButtons(getResolvedTheme());
    })();
</script>
