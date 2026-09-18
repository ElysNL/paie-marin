import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
        pagination: null,
    }),
    actions: {
        async fetchUsers({ page = 1, role = '', search = '' } = {}) {
            const params = { page };
            if (role) params.role = role;
            if (search) params.search = search;

            const response = await apiClient.get('/users', { params });
            this.users = response.data.data;
            this.pagination = {
                total: response.data.total,
                per_page: response.data.per_page,
                current_page: response.data.current_page,
                last_page: response.data.last_page,
            };
        },
        async createUser(data) {
            const response = await apiClient.post('/users', data);
            this.users.unshift(response.data);
            return response.data;
        },
        async updateUser(id, data) {
            const response = await apiClient.put(`/users/${id}`, data);
            const index = this.users.findIndex((u) => u.id === id);
            if (index !== -1) this.users[index] = response.data;
            return response.data;
        },
        async deleteUser(id) {
            await apiClient.delete(`/users/${id}`);
            this.users = this.users.filter((u) => u.id !== id);
        },
        async toggleActif(id) {
            const response = await apiClient.post(`/users/${id}/toggle-actif`);
            const index = this.users.findIndex((u) => u.id === id);
            if (index !== -1) this.users[index] = response.data;
            return response.data;
        },
    },
});
