import { defineStore } from 'pinia';
import api from '../utils/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
        loading: false,
    }),
    
    getters: {
        isAuthenticated: (state) => !!state.token,
    },
    
    actions: {
        async login(credentials) {
            this.loading = true;
            try {
                // Ensure CSRF is set before login
                await api.get('/sanctum/csrf-cookie', { baseURL: '' });
                const { data } = await api.post('/auth/login', credentials);
                this.token = data.access_token;
                this.user = data.user;
                localStorage.setItem('auth_token', data.access_token);
                return data;
            } finally {
                this.loading = false;
            }
        },
        
        async fetchUser() {
            if (!this.token) return;
            try {
                const { data } = await api.get('/user');
                this.user = data;
            } catch (error) {
                this.logout();
            }
        },
        
        async logout() {
            try {
                if (this.token) {
                    await api.post('/auth/logout');
                }
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('auth_token');
            }
        }
    }
});
