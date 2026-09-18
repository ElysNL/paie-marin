import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useNavireStore = defineStore('navires', {
    state: () => ({
        navires: [],
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchNavires(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/navires?page=${page}`);
                this.navires = response.data.data;
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
        async createNavire(data) {
            const response = await apiClient.post('/navires', data);
            this.navires.push(response.data);
            return response.data;
        },
        async updateNavire(id, data) {
            const response = await apiClient.put(`/navires/${id}`, data);
            const index = this.navires.findIndex(n => n.id === id);
            if (index !== -1) this.navires[index] = response.data;
            return response.data;
        },
        async deleteNavire(id) {
            await apiClient.delete(`/navires/${id}`);
            this.navires = this.navires.filter(n => n.id !== id);
        },
    },
});
