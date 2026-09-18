<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Utilisateurs</h1>
      <router-link to="/utilisateurs/create">
        <AppButton> Nouvel utilisateur </AppButton>
      </router-link>
    </div>

    <AppCard>
      <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher par nom ou email…"
          class="flex-1 rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
          @input="debouncedFetch"
        />
        <select
          v-model="roleFilter"
          class="rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm outline-none focus:border-primary"
          @change="fetchData"
        >
          <option value="">Tous les rôles</option>
          <option value="admin">Admin</option>
          <option value="rh">RH</option>
          <option value="paie">Paie</option>
          <option value="user">Utilisateur</option>
        </select>
      </div>

      <AppTable :columns="columns" :rows="store.users" row-key="id">
        <template #cell(name)="{ row }">
          <div class="font-medium">{{ row.name }}</div>
        </template>
        <template #cell(role)="{ row }">
          <span
            class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"
            :class="roleBadge(row.role)"
          >
            {{ row.role?.toUpperCase() }}
          </span>
        </template>
        <template #cell(actif)="{ row }">
          <span
            class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"
            :class="row.actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
          >
            {{ row.actif ? 'Actif' : 'Inactif' }}
          </span>
        </template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/utilisateurs/${row.id}/edit`">
              <AppButton variant="text" class="!px-2 !py-1">Modifier</AppButton>
            </router-link>
            <AppButton
              variant="text"
              class="!px-2 !py-1"
              @click="toggleActif(row)"
            >
              {{ row.actif ? 'Désactiver' : 'Activer' }}
            </AppButton>
            <AppButton
              variant="text"
              class="!px-2 !py-1 !text-red-600 hover:!bg-red-50"
              @click="remove(row)"
            >
              Supprimer
            </AppButton>
          </div>
        </template>
      </AppTable>

      <Pagination
        v-if="store.pagination"
        :current="store.pagination.current_page"
        :last="store.pagination.last_page"
        @page-change="goToPage"
      />

      <AppEmpty v-if="!loading && store.users.length === 0" message="Aucun utilisateur trouvé." />
      <AppLoading v-if="loading" message="Chargement des utilisateurs…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useUserStore } from '@/stores/userStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';

const store = useUserStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Utilisateurs' }];
const loading = ref(false);
const search = ref('');
const roleFilter = ref('');

const columns = [
  { key: 'name', label: 'Nom' },
  { key: 'email', label: 'Email' },
  { key: 'role', label: 'Rôle' },
  { key: 'actif', label: 'Statut' },
  { key: 'actions', label: 'Actions', align: 'right' },
];

const roleBadge = (role) => {
  const map = {
    admin: 'bg-purple-100 text-purple-800',
    rh: 'bg-blue-100 text-blue-800',
    paie: 'bg-teal-100 text-teal-800',
    user: 'bg-gray-100 text-gray-800',
  };
  return map[role] || 'bg-gray-100 text-gray-800';
};

let debounceTimer = null;
const debouncedFetch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(fetchData, 300);
};

const fetchData = async (page = 1) => {
  loading.value = true;
  try {
    await store.fetchUsers({ page, role: roleFilter.value, search: search.value });
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => fetchData(page);

const toggleActif = async (user) => {
  if (!confirm(`Voulez-vous ${user.actif ? 'désactiver' : 'activer'} cet utilisateur ?`)) return;
  await store.toggleActif(user.id);
};

const remove = async (user) => {
  if (!confirm(`Supprimer l'utilisateur "${user.name}" ?`)) return;
  await store.deleteUser(user.id);
};

onMounted(() => fetchData());
</script>
