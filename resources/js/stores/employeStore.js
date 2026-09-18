import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useEmployeStore = defineStore('employes', {
    state: () => ({
        employes: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchEmployes(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/employes?page=${page}`);
                this.employes = response.data.data;
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
        async createEmploye(data) {
            const response = await apiClient.post('/employes', data);
            // EmployeResource (single) retourne { id, nom, ... } sans wrapper 'data'
            this.employes.push(response.data);
            return response.data;
        },
        async updateEmploye(id, data) {
            const response = await apiClient.put(`/employes/${id}`, data);
            const index = this.employes.findIndex(e => e.id === id);
            if (index !== -1) this.employes[index] = response.data;
            return response.data;
        },
        async deleteEmploye(id) {
            await apiClient.delete(`/employes/${id}`);
            this.employes = this.employes.filter(e => e.id !== id);
        },
    },
});
