import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useDelegationStore = defineStore('delegations', {
    state: () => ({
        delegations: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchDelegations({ page = 1, employeId = null } = {}) {
            this.loading = true;
            try {
                const params = { page };
                if (employeId) params.employe_id = employeId;
                const response = await apiClient.get('/delegations', { params });
                this.delegations = response.data.data;
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
        async createDelegation(data) {
            const response = await apiClient.post('/delegations', data);
            this.delegations.unshift(response.data);
            return response.data;
        },
        async updateDelegation(id, data) {
            const response = await apiClient.put(`/delegations/${id}`, data);
            const index = this.delegations.findIndex(d => d.id === id);
            if (index !== -1) this.delegations[index] = response.data;
            return response.data;
        },
        async deleteDelegation(id) {
            await apiClient.delete(`/delegations/${id}`);
            this.delegations = this.delegations.filter(d => d.id !== id);
        },
    },
});