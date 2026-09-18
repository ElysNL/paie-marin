import axios from 'axios';
import { useToasts } from '@/services/toast';
import { useAuthStore } from '@/stores/auth';
import router from '@/router';

const apiClient = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
});

apiClient.interceptors.response.use(
    response => response,
    async error => {
        const { error: notify } = useToasts();
        if (error.response) {
            const { status, data } = error.response;

            if (status === 401) {
                // Ignorer /auth/me pour éviter la boucle de redirection
                const isAuthCheck = error.config?.url?.includes('/auth/me');
                if (!isAuthCheck && typeof window !== 'undefined') {
                    const auth = useAuthStore();
                    auth.user = null;
                    if (router.currentRoute.value.path !== '/login') {
                        router.push({ path: '/login', query: { redirect: router.currentRoute.value.fullPath } });
                    }
                }
                notify('Session expirée. Veuillez vous reconnecter.');
            } else if (status === 423) {
                const auth = useAuthStore();
                auth.user = null;
                if (router.currentRoute.value.path !== '/login') {
                    router.push({ path: '/login' });
                }
                notify(data.message || 'Compte temporairement verrouillé.');
            } else if (status === 403) {
                notify('Action non autorisée.');
            } else if (status === 422) {
                if (typeof data.message === 'string') {
                    notify(data.message);
                }
            } else if (status >= 500) {
                notify(data.error || data.message || 'Une erreur serveur est survenue.');
            } else if (data && (data.error || data.message)) {
                notify(data.error || data.message);
            }
        } else if (error.request) {
            notify('Impossible de contacter le serveur.');
        }

        return Promise.reject(error);
    }
);

export default apiClient;
