<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Paies</h1>
      <router-link to="/paies/create">
        <AppButton>Nouvelle paie</AppButton>
      </router-link>
    </div>

    <AppCard>
      <div class="mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher par libellé ou numéro…"
          class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
          @input="debouncedFetch"
        />
      </div>

      <AppTable :columns="columns" :rows="store.paies" row-key="id">
        <template #cell(num_paie)="{ row }">{{ row.num_paie }}</template>
        <template #cell(libelle)="{ row }">{{ row.libelle }}</template>
        <template #cell(periode)="{ row }">{{ row.periode }}</template>
        <template #cell(dates)="{ row }" class="whitespace-nowrap">
          {{ formatDate(row.date_debut) }} → {{ formatDate(row.date_fin) }}
        </template>
        <template #cell(bulletins_count)="{ row }">{{ row.bulletins_count ?? 0 }}</template>
        <template #cell(statut)="{ row }"><AppBadge :statut="row.statut" /></template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/paies/${row.id}`">
              <AppButton variant="text" class="!px-2 !py-1">Voir</AppButton>
            </router-link>
            <router-link v-if="['brouillon', 'calcule'].includes(row.statut)" :to="`/paies/${row.id}/edit`">
              <AppButton variant="text" class="!px-2 !py-1">Modifier</AppButton>
            </router-link>
            <AppButton
              v-if="row.statut === 'brouillon'"
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

      <AppEmpty v-if="!loading && store.paies.length === 0" message="Aucune paie enregistrée." />
      <AppLoading v-if="loading" message="Chargement des paies…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePaieStore } from '@/stores/paieStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppBadge from '@/components/AppBadge.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';
import { formatDate } from '@/utils/format';

const store = usePaieStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Paies' }];
const loading = ref(false);
const search = ref('');

const columns = [
  { key: 'num_paie', label: 'N°' },
  { key: 'libelle', label: 'Libellé' },
  { key: 'periode', label: 'Période' },
  { key: 'dates', label: 'Dates' },
  { key: 'bulletins_count', label: 'Bulletins' },
  { key: 'statut', label: 'Statut' },
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
    await store.fetchPaies(page);
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => fetchData(page);

const remove = async (row) => {
  if (!confirm('Voulez-vous supprimer cette paie ?')) return;
  await store.deletePaie(row.id);
};

onMounted(() => fetchData());
</script>
