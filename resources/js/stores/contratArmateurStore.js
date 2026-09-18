import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useContratArmateurStore = defineStore('contratsArmateur', {
    state: () => ({
        contrats: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchContrats(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/contrats-armateur?page=${page}`);
                this.contrats = response.data.data;
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
        async createContrat(data) {
            const response = await apiClient.post('/contrats-armateur', data);
            this.contrats.push(response.data);
            return response.data;
        },
        async updateContrat(id, data) {
            const response = await apiClient.put(`/contrats-armateur/${id}`, data);
            const index = this.contrats.findIndex(c => c.id === id);
            if (index !== -1) this.contrats[index] = response.data;
            return response.data;
        },
        async deleteContrat(id) {
            await apiClient.delete(`/contrats-armateur/${id}`);
            this.contrats = this.contrats.filter(c => c.id !== id);
        },
    },
});
