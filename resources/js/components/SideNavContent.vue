<template>
  <div class="flex h-full flex-col">
    <div class="flex items-center justify-between p-4">
      <router-link to="/dashboard" class="flex items-center gap-2 text-lg font-semibold text-white">
        <span class="grid h-8 w-8 place-items-center rounded bg-blue-600">
          <AppIcon name="ship" :size="18" />
        </span>
        Gestion de Paie
      </router-link>
      <button
        type="button"
        class="text-gray-300 hover:text-white lg:hidden"
        aria-label="Fermer le menu"
        @click="$emit('close')"
      >
        <AppIcon name="x" :size="20" />
      </button>
    </div>

    <nav class="flex-1 space-y-4 overflow-y-auto px-3 pb-3">
      <div v-for="section in sections" :key="section.label">
        <div v-if="section.label" class="px-3 pt-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">
          {{ section.label }}
        </div>
        <ul class="space-y-0.5">
          <li v-for="item in section.items" :key="item.path">
            <router-link
              :to="item.path"
              class="flex items-center gap-3 rounded px-3 py-2 text-sm transition"
              :class="isActive(item.path)
                ? 'bg-blue-600 font-medium text-white'
                : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
            >
              <AppIcon :name="item.icon" :size="18" />
              {{ item.label }}
            </router-link>
          </li>
        </ul>
      </div>
    </nav>

    <div class="border-t border-gray-700 p-4">
      <div v-if="auth.user" class="mb-2 truncate px-1 text-xs text-gray-400">{{ auth.user.name }}</div>
      <button
        type="button"
        @click="logout"
        class="flex w-full items-center gap-3 rounded px-3 py-2 text-sm text-gray-300 transition hover:bg-red-600 hover:text-white"
      >
        <AppIcon name="logout" :size="18" />
        Déconnexion
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router';
import AppIcon from '@/components/AppIcon.vue';
import { useAuthStore } from '@/stores/auth';

defineEmits(['close']);

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const sections = [
  {
    label: '',
    items: [
      { label: 'Tableau de bord', path: '/dashboard', icon: 'dashboard' },
    ],
  },
  {
    label: 'Suivi',
    items: [
      { label: 'Paies', path: '/paies', icon: 'wallet' },
      { label: 'Bulletins', path: '/bulletins', icon: 'file' },
      { label: 'Avances', path: '/avances', icon: 'creditcard' },
      { label: 'Délégations', path: '/delegations', icon: 'coins' },
    ],
  },
  {
    label: 'Référentiels',
    items: [
      { label: 'Employés', path: '/employes', icon: 'users' },
      { label: 'Armateurs', path: '/armateurs', icon: 'building' },
      { label: 'Navires', path: '/navires', icon: 'ship' },
      { label: 'Affectations', path: '/affectations', icon: 'clipboard' },
      { label: "Contrats d'armateur", path: '/contrats-armateur', icon: 'file' },
      { label: 'Pays', path: '/pays', icon: 'globe' },
      { label: 'Devises', path: '/devises', icon: 'coins' },
      { label: 'Fonctions', path: '/fonctions', icon: 'briefcase' },
      { label: 'Classifications', path: '/classifications', icon: 'clipboard' },
      { label: 'Compagnies', path: '/compagnies', icon: 'building' },
    ],
  },
];

const isActive = (path) => {
  if (path === '/dashboard') return route.path === '/dashboard';
  return route.path.startsWith(path);
};

const logout = async () => {
  await auth.logout();
  router.push('/login');
};
</script>