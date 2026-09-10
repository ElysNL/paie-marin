import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useAvanceStore = defineStore('avances', {
    state: () => ({
        avances: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchAvances({ page = 1, employeId = null } = {}) {
            this.loading = true;
            try {
                const params = { page };
                if (employeId) params.employe_id = employeId;
                const response = await apiClient.get('/avances', { params });
                this.avances = response.data.data;
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
        async createAvance(data) {
            const response = await apiClient.post('/avances', data);
            this.avances.unshift(response.data);
            return response.data;
        },
        async updateAvance(id, data) {
            const response = await apiClient.put(`/avances/${id}`, data);
            const index = this.avances.findIndex(a => a.id === id);
            if (index !== -1) this.avances[index] = response.data;
            return response.data;
        },
        async deleteAvance(id) {
            await apiClient.delete(`/avances/${id}`);
            this.avances = this.avances.filter(a => a.id !== id);
        },
    },
});