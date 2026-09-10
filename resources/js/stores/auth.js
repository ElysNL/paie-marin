import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        ready: false,
    }),
    getters: {
        isAuthenticated: (state) => !!state.user,
    },
    actions: {
        async init() {
            try {
                const response = await apiClient.get('/auth/me');
                this.user = response.data.data.user;
            } catch (e) {
                this.user = null;
            } finally {
                this.ready = true;
            }
        },
        async login(email, password) {
            await apiClient.get('/sanctum/csrf-cookie', { baseURL: '/' });
            const response = await apiClient.post('/auth/login', { email, password });
            this.user = response.data.data.user;
            return this.user;
        },
        async logout() {
            try {
                await apiClient.post('/auth/logout');
            } finally {
                this.user = null;
            }
        },
    },
});