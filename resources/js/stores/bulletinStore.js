import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const useBulletinStore = defineStore('bulletins', {
    state: () => ({
        bulletins: [],
        currentBulletin: null,
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchBulletins({ page = 1, paieId = null, employeId = null } = {}) {
            this.loading = true;
            try {
                const params = { page };
                if (paieId) params.paie_id = paieId;
                if (employeId) params.employe_id = employeId;
                const response = await apiClient.get('/bulletins', { params });
                this.bulletins = response.data.data;
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
        async fetchBulletin(id) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/bulletins/${id}`);
                this.currentBulletin = response.data;
                return response.data;
            } finally {
                this.loading = false;
            }
        },
        async deleteBulletin(id) {
            await apiClient.delete(`/bulletins/${id}`);
            this.bulletins = this.bulletins.filter(b => b.id !== id);
        },
    },
});