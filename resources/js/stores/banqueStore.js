import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useBanqueStore = defineStore('banques', {
    state: () => ({
        banques: [],
        loading: false,
    }),
    actions: {
        async fetchBanques() {
            this.loading = true;
            try {
                const response = await apiClient.get('/banques');
                this.banques = response.data;
            } finally {
                this.loading = false;
            }
        },
    },
});