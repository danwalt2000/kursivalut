<script>
    (function () {
        const storageKey = 'kursivalut-theme';
        const root = document.documentElement;
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        let storedTheme = null;

        try {
            const value = window.localStorage.getItem(storageKey);
            storedTheme = value === 'dark' || value === 'light' ? value : null;
        } catch (error) {
            storedTheme = null;
        }

        const resolvedTheme = storedTheme || systemTheme;
        root.classList.toggle('dark', resolvedTheme === 'dark');
        root.dataset.theme = resolvedTheme;
    })();
</script>
