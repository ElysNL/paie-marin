<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Armateurs</h1>
      <router-link to="/armateurs/create">
        <AppButton>Nouvel armateur</AppButton>
      </router-link>
    </div>

    <AppCard>
      <div class="mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher par nom ou code…"
          class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
          @input="debouncedFetch"
        />
      </div>

      <AppTable :columns="columns" :rows="store.armateurs" row-key="id">
        <template #cell(code)="{ row }">{{ row.code }}</template>
        <template #cell(nom)="{ row }">{{ row.nom }}</template>
        <template #cell(pays)="{ row }">{{ row.pays?.nom }}</template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/armateurs/${row.id}/edit`">
              <AppButton variant="text" class="!px-2 !py-1">Modifier</AppButton>
            </router-link>
            <AppButton variant="text" class="!px-2 !py-1 !text-red-600 hover:!bg-red-50" @click="remove(row)">
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

      <AppEmpty v-if="!loading && store.armateurs.length === 0" message="Aucun armateur enregistré." />
      <AppLoading v-if="loading" message="Chargement des armateurs…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useArmateurStore } from '@/stores/armateurStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';

const store = useArmateurStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Armateurs' }];
const loading = ref(false);
const search = ref('');

const columns = [
  { key: 'code', label: 'Code' },
  { key: 'nom', label: 'Nom' },
  { key: 'pays', label: 'Pays' },
  { key: 'actions', label: 'Actions', align: 'right' },
];

let debounceTimer = null;
const debouncedFetch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(fetchData, 300);
};

const fetchData = async (page = 1) => {
  loading.value = true;
  try {
    await store.fetchArmateurs(page);
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => fetchData(page);

const remove = async (row) => {
  if (!confirm('Voulez-vous supprimer cet armateur ?')) return;
  await store.deleteArmateur(row.id);
};

onMounted(() => fetchData());
</script>
