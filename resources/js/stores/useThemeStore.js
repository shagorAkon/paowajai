import { defineStore } from 'pinia';

export const useThemeStore = defineStore('theme', {
  state: () => ({
    isDark: false,
  }),
  actions: {
    initTheme() {
      // Check local storage or system preference
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        this.setTheme('dark');
      } else if (savedTheme === 'light') {
        this.setTheme('light');
      } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        this.setTheme('dark');
      } else {
        this.setTheme('light');
      }
    },
    setTheme(mode) {
      this.isDark = mode === 'dark';
      localStorage.setItem('theme', mode);
      if (this.isDark) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    },
    toggleTheme() {
      this.setTheme(this.isDark ? 'light' : 'dark');
    }
  }
});
