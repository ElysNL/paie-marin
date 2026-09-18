import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useAffectationStore = defineStore('affectations', {
    state: () => ({
        affectations: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchAffectations(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/affectations?page=${page}`);
                this.affectations = response.data.data;
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
        async createAffectation(data) {
            const response = await apiClient.post('/affectations', data);
            this.affectations.push(response.data);
            return response.data;
        },
        async updateAffectation(id, data) {
            const response = await apiClient.put(`/affectations/${id}`, data);
            const index = this.affectations.findIndex(a => a.id === id);
            if (index !== -1) this.affectations[index] = response.data;
            return response.data;
        },
        async deleteAffectation(id) {
            await apiClient.delete(`/affectations/${id}`);
            this.affectations = this.affectations.filter(a => a.id !== id);
        },
    },
});
