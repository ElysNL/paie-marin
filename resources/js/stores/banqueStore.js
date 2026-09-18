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
                this.banques = response.data.data;
            } finally {
                this.loading = false;
            }
        },
        async createBanque(data) {
            const response = await apiClient.post('/banques', data);
            this.banques.push(response.data);
            return response.data;
        },
        async updateBanque(id, data) {
            const response = await apiClient.put(`/banques/${id}`, data);
            const index = this.banques.findIndex(b => b.id === id);
            if (index !== -1) this.banques[index] = response.data;
            return response.data;
        },
        async deleteBanque(id) {
            await apiClient.delete(`/banques/${id}`);
            this.banques = this.banques.filter(b => b.id !== id);
        },
    },
});
