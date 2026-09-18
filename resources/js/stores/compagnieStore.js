import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useCompagnieStore = defineStore('compagnies', {
    state: () => ({
        compagnies: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchCompagnies(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/compagnies?page=${page}`);
                this.compagnies = response.data.data;
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
        async createCompagnie(data) {
            const response = await apiClient.post('/compagnies', data);
            this.compagnies.push(response.data);
            return response.data;
        },
        async updateCompagnie(id, data) {
            const response = await apiClient.put(`/compagnies/${id}`, data);
            const index = this.compagnies.findIndex(c => c.id === id);
            if (index !== -1) this.compagnies[index] = response.data;
            return response.data;
        },
        async deleteCompagnie(id) {
            await apiClient.delete(`/compagnies/${id}`);
            this.compagnies = this.compagnies.filter(c => c.id !== id);
        },
    },
});
