import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useDeviseStore = defineStore('devises', {
    state: () => ({
        devises: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchDevises(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/devises?page=${page}`);
                this.devises = response.data.data;
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
        async createDevise(data) {
            const response = await apiClient.post('/devises', data);
            this.devises.push(response.data);
            return response.data;
        },
        async updateDevise(id, data) {
            const response = await apiClient.put(`/devises/${id}`, data);
            const index = this.devises.findIndex(d => d.id === id);
            if (index !== -1) this.devises[index] = response.data;
            return response.data;
        },
        async deleteDevise(id) {
            await apiClient.delete(`/devises/${id}`);
            this.devises = this.devises.filter(d => d.id !== id);
        },
    },
});
