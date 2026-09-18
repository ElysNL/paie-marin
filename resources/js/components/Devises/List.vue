<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Devises</h1>
      <router-link to="/devises/create">
        <AppButton>Nouvelle devise</AppButton>
      </router-link>
    </div>

    <AppCard>
      <div class="mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher par code ou libellé…"
          class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
          @input="debouncedFetch"
        />
      </div>

      <AppTable :columns="columns" :rows="store.devises" row-key="id">
        <template #cell(code)="{ row }">{{ row.code }}</template>
        <template #cell(libelle)="{ row }">{{ row.libelle }}</template>
        <template #cell(symbole)="{ row }">{{ row.symbole }}</template>
        <template #cell(nb_decimales)="{ row }">{{ row.nb_decimales }}</template>
        <template #cell(actif)="{ row }">
          <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"
            :class="row.actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
            {{ row.actif ? 'Oui' : 'Non' }}
          </span>
        </template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/devises/${row.id}/edit`">
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

      <AppEmpty v-if="!loading && store.devises.length === 0" message="Aucune devise enregistrée." />
      <AppLoading v-if="loading" message="Chargement des devises…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useDeviseStore } from '@/stores/deviseStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';

const store = useDeviseStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Devises' }];
const loading = ref(false);
const search = ref('');

const columns = [
  { key: 'code', label: 'Code' },
  { key: 'libelle', label: 'Libellé' },
  { key: 'symbole', label: 'Symbole' },
  { key: 'nb_decimales', label: 'Décimales' },
  { key: 'actif', label: 'Actif' },
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
    await store.fetchDevises(page);
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => fetchData(page);

const remove = async (row) => {
  if (!confirm('Voulez-vous supprimer cette devise ?')) return;
  await store.deleteDevise(row.id);
};

onMounted(() => fetchData());
</script>
