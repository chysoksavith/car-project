import { ref, onMounted } from 'vue';

// # Use Theme
export function useTheme() {
    const isDark = ref(false);

    // # Init Theme
    const initTheme = () => {
        const theme = localStorage.getItem('theme');
        if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            isDark.value = true;
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            isDark.value = false;
            document.documentElement.setAttribute('data-theme', 'light');
        }
    };

    // # Toggle Theme
    const toggleTheme = () => {
        isDark.value = !isDark.value;
        const newTheme = isDark.value ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    };

    onMounted(() => {
        initTheme();
    });

    return {
        isDark,
        toggleTheme,
    };
}
