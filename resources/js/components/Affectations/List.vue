<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Affectations</h1>
      <router-link to="/affectations/create">
        <AppButton>Nouvelle affectation</AppButton>
      </router-link>
    </div>

    <AppCard>
      <div class="mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher par employé ou navire…"
          class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
          @input="debouncedFetch"
        />
      </div>

      <AppTable :columns="columns" :rows="store.affectations" row-key="id">
        <template #cell(employe)="{ row }">{{ row.employe?.nom }} {{ row.employe?.prenom }}</template>
        <template #cell(navire)="{ row }">{{ row.navire?.nom }}</template>
        <template #cell(fonction)="{ row }">{{ row.fonction?.libelle }}</template>
        <template #cell(date_embt)="{ row }">{{ row.date_embt }}</template>
        <template #cell(taux_journalier)="{ row }">{{ row.taux_journalier }}</template>
        <template #cell(statut)="{ row }"><AppBadge :statut="row.statut" /></template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/affectations/${row.id}/edit`">
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

      <AppEmpty v-if="!loading && store.affectations.length === 0" message="Aucune affectation enregistrée." />
      <AppLoading v-if="loading" message="Chargement des affectations…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAffectationStore } from '@/stores/affectationStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppBadge from '@/components/AppBadge.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';

const store = useAffectationStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Affectations' }];
const loading = ref(false);
const search = ref('');

const columns = [
  { key: 'employe', label: 'Employé' },
  { key: 'navire', label: 'Navire' },
  { key: 'fonction', label: 'Fonction' },
  { key: 'date_embt', label: 'Date embarquement' },
  { key: 'taux_journalier', label: 'Taux journalier' },
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
    await store.fetchAffectations(page);
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => fetchData(page);

const remove = async (row) => {
  if (!confirm('Voulez-vous supprimer cette affectation ?')) return;
  await store.deleteAffectation(row.id);
};

onMounted(() => fetchData());
</script>
