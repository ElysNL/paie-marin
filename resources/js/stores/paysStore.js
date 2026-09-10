import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const usePaysStore = defineStore('pays', {
    state: () => ({
        pays: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchPays(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/pays?page=${page}`);
                this.pays = response.data.data;
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
        async createPays(data) {
            const response = await apiClient.post('/pays', data);
            this.pays.push(response.data);
            return response.data;
        },
        async updatePays(id, data) {
            const response = await apiClient.put(`/pays/${id}`, data);
            const index = this.pays.findIndex(p => p.id === id);
            if (index !== -1) this.pays[index] = response.data;
            return response.data;
        },
        async deletePays(id) {
            await apiClient.delete(`/pays/${id}`);
            this.pays = this.pays.filter(p => p.id !== id);
        },
    },
});
