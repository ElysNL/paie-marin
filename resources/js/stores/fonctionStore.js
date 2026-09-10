import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useFonctionStore = defineStore('fonctions', {
    state: () => ({
        fonctions: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchFonctions(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/fonctions?page=${page}`);
                this.fonctions = response.data.data;
                this.pagination = {
                    total: response.data.total,
                    per_page: response.data.per_page,
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                };
            } finally {
                this.loading = false;
            }
        },
        async createFonction(data) {
            const response = await apiClient.post('/fonctions', data);
            this.fonctions.push(response.data);
            return response.data;
        },
        async updateFonction(id, data) {
            const response = await apiClient.put(`/fonctions/${id}`, data);
            const index = this.fonctions.findIndex(f => f.id === id);
            if (index !== -1) this.fonctions[index] = response.data;
            return response.data;
        },
        async deleteFonction(id) {
            await apiClient.delete(`/fonctions/${id}`);
            this.fonctions = this.fonctions.filter(f => f.id !== id);
        },
    },
});
