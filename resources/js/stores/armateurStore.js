import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useArmateurStore = defineStore('armateurs', {
    state: () => ({
        armateurs: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchArmateurs(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/armateurs?page=${page}`);
                this.armateurs = response.data.data;
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
        async createArmateur(data) {
            const response = await apiClient.post('/armateurs', data);
            this.armateurs.push(response.data);
            return response.data;
        },
        async updateArmateur(id, data) {
            const response = await apiClient.put(`/armateurs/${id}`, data);
            const index = this.armateurs.findIndex(a => a.id === id);
            if (index !== -1) this.armateurs[index] = response.data;
            return response.data;
        },
        async deleteArmateur(id) {
            await apiClient.delete(`/armateurs/${id}`);
            this.armateurs = this.armateurs.filter(a => a.id !== id);
        },
    },
});
