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
      <div v-for="section in filteredSections" :key="section.label || 'main'">
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
      <div v-if="auth.user" class="mb-2 px-1 text-xs text-gray-400">
        <div class="truncate">{{ auth.user.name }}</div>
        <div class="mt-0.5 font-semibold uppercase text-blue-400">{{ auth.userRole }}</div>
      </div>
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
import { computed } from 'vue';
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
      { label: 'Tableau de bord', path: '/dashboard', icon: 'dashboard', roles: [] },
    ],
  },
  {
    label: 'Suivi',
    items: [
      { label: 'Paies', path: '/paies', icon: 'wallet', roles: ['admin', 'paie'] },
      { label: 'Bulletins', path: '/bulletins', icon: 'file', roles: ['admin', 'paie'] },
      { label: 'Avances', path: '/avances', icon: 'creditcard', roles: ['admin', 'rh', 'paie'] },
      { label: 'Délégations', path: '/delegations', icon: 'coins', roles: ['admin', 'rh', 'paie'] },
    ],
  },
  {
    label: 'Référentiels',
    items: [
      { label: 'Employés', path: '/employes', icon: 'users', roles: ['admin', 'rh'] },
      { label: 'Armateurs', path: '/armateurs', icon: 'building', roles: ['admin', 'rh'] },
      { label: 'Navires', path: '/navires', icon: 'ship', roles: ['admin', 'rh'] },
      { label: 'Affectations', path: '/affectations', icon: 'clipboard', roles: ['admin', 'rh', 'paie'] },
      { label: "Contrats d'armateur", path: '/contrats-armateur', icon: 'file', roles: ['admin', 'rh'] },
      { label: 'Pays', path: '/pays', icon: 'globe', roles: ['admin', 'rh'] },
      { label: 'Devises', path: '/devises', icon: 'coins', roles: ['admin', 'rh'] },
      { label: 'Fonctions', path: '/fonctions', icon: 'briefcase', roles: ['admin', 'rh'] },
      { label: 'Classifications', path: '/classifications', icon: 'clipboard', roles: ['admin', 'rh'] },
      { label: 'Compagnies', path: '/compagnies', icon: 'building', roles: ['admin', 'rh'] },
      { label: 'Banques', path: '/banques', icon: 'building', roles: ['admin', 'rh'] },
    ],
  },
  {
    label: 'Administration',
    items: [
      { label: 'Utilisateurs', path: '/utilisateurs', icon: 'shield', roles: ['admin'] },
    ],
  },
];

const filteredSections = computed(() => {
  return sections
    .map((section) => ({
      ...section,
      items: section.items.filter(
        (item) => !item.roles.length || auth.hasRole(...item.roles)
      ),
    }))
    .filter((section) => section.items.length > 0);
});

const isActive = (path) => {
  if (path === '/dashboard') return route.path === '/dashboard';
  return route.path.startsWith(path);
};

const logout = async () => {
  await auth.logout();
  router.push('/login');
};
</script>
