import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useClassificationStore = defineStore('classifications', {
    state: () => ({
        classifications: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchClassifications(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/classifications?page=${page}`);
                this.classifications = response.data.data;
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
        async createClassification(data) {
            const response = await apiClient.post('/classifications', data);
            this.classifications.push(response.data);
            return response.data;
        },
        async updateClassification(id, data) {
            const response = await apiClient.put(`/classifications/${id}`, data);
            const index = this.classifications.findIndex(c => c.id === id);
            if (index !== -1) this.classifications[index] = response.data;
            return response.data;
        },
        async deleteClassification(id) {
            await apiClient.delete(`/classifications/${id}`);
            this.classifications = this.classifications.filter(c => c.id !== id);
        },
    },
});
