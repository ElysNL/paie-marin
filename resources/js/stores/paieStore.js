import { defineStore } from 'pinia';
import apiClient from '@/services/api';

export const usePaieStore = defineStore('paies', {
    state: () => ({
        paies: [],
        currentPaie: null,
        eligibles: [],
        naviresEligibles: [],
        statutCalcul: null,
        loading: false,
        pagination: null,
    }),
    actions: {
        async fetchPaies(page = 1) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/paies?page=${page}`);
                this.paies = response.data.data;
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
        async fetchPaie(id) {
            this.loading = true;
            try {
                const response = await apiClient.get(`/paies/${id}`);
                this.currentPaie = response.data;
                return response.data;
            } finally {
                this.loading = false;
            }
        },
        async fetchEligibles(id) {
            const response = await apiClient.get(`/paies/${id}/eligibles`);
            this.eligibles = response.data;
            return response.data;
        },
        async fetchNaviresEligibles(id) {
            const response = await apiClient.get(`/paies/${id}/navires-eligibles`);
            this.naviresEligibles = response.data;
            return response.data;
        },
        async fetchStatutCalcul(id) {
            const response = await apiClient.get(`/paies/${id}/statut-calcul`);
            this.statutCalcul = response.data;
            return response.data;
        },
        async createPaie(data) {
            const response = await apiClient.post('/paies', data);
            this.paies.unshift(response.data.data);
            return response.data.data;
        },
        async updatePaie(id, data) {
            const response = await apiClient.put(`/paies/${id}`, data);
            const index = this.paies.findIndex(p => p.id === id);
            if (index !== -1) this.paies[index] = response.data;
            return response.data;
        },
        async deletePaie(id) {
            await apiClient.delete(`/paies/${id}`);
            this.paies = this.paies.filter(p => p.id !== id);
        },
        async calculer(id, payload = {}) {
            const response = await apiClient.post(`/paies/${id}/calculer`, payload);
            return response.data;
        },
        async valider(id) {
            const response = await apiClient.post(`/paies/${id}/valider`);
            return response.data;
        },
        async cloturer(id) {
            const response = await apiClient.post(`/paies/${id}/cloturer`);
            return response.data;
        },
    },
});
