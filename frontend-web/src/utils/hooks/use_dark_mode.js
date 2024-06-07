export const useDarkMode = () => {
        const isDarkMode = localStorage.getItem('theme') === 'dark';
        return isDarkMode;
}