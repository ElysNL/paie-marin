<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Bulletins de paie</h1>
    </div>

    <AppCard>
      <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <AppSelect
          v-model="filterPaie"
          :options="[{ value: null, label: 'Toutes les paies' }, ...paies.map(p => ({ value: p.id, label: p.libelle + ' (' + p.periode + ')' }))]"
          class="flex-1"
        />
        <AppSelect
          v-model="filterEmploye"
          :options="[{ value: null, label: 'Tous les employés' }, ...employes.map(e => ({ value: e.id, label: e.nom + ' ' + e.prenom }))]"
          class="flex-1"
        />
        <AppButton @click="fetch(1)">Filtrer</AppButton>
      </div>

      <AppTable :columns="columns" :rows="store.bulletins" row-key="id">
        <template #cell(employe)="{ row }">{{ row.employe?.nom }} {{ row.employe?.prenom }}</template>
        <template #cell(navire)="{ row }">{{ row.navire?.nom }}</template>
        <template #cell(paie)="{ row }">{{ row.paie?.libelle }}</template>
        <template #cell(total_jours)="{ row }">{{ row.total_jours }}</template>
        <template #cell(total_brut)="{ row }" class="text-right">{{ formatMoney(row.total_brut) }}</template>
        <template #cell(total_retenues)="{ row }" class="text-right">{{ formatMoney(row.total_retenues) }}</template>
        <template #cell(net_a_payer)="{ row }" class="text-right font-semibold">{{ formatMoney(row.net_a_payer) }}</template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/bulletins/${row.id}`">
              <AppButton variant="text" class="!px-2 !py-1">Détail</AppButton>
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
        @page-change="fetch"
      />

      <AppEmpty v-if="!loading && store.bulletins.length === 0" message="Aucun bulletin trouvé." />
      <AppLoading v-if="loading" message="Chargement des bulletins…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useBulletinStore } from '@/stores/bulletinStore';
import { usePaieStore } from '@/stores/paieStore';
import { useEmployeStore } from '@/stores/employeStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';
import { formatMoney } from '@/utils/format';

const store = useBulletinStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Bulletins de paie' }];
const paieStore = usePaieStore();
const employeStore = useEmployeStore();
const route = useRoute();
const loading = ref(false);

const bulletins = computed(() => store.bulletins);
const pagination = computed(() => store.pagination);
const paies = computed(() => paieStore.paies);
const employes = computed(() => employeStore.employes);

const filterPaie = ref(route.query.paie_id ? Number(route.query.paie_id) : null);
const filterEmploye = ref(null);

const columns = [
  { key: 'employe', label: 'Employé' },
  { key: 'navire', label: 'Navire' },
  { key: 'paie', label: 'Paie' },
  { key: 'total_jours', label: 'Jours' },
  { key: 'total_brut', label: 'Brut', align: 'right' },
  { key: 'total_retenues', label: 'Retenues', align: 'right' },
  { key: 'net_a_payer', label: 'Net à payer', align: 'right' },
  { key: 'actions', label: 'Actions', align: 'right' },
];

const fetch = async (page = 1) => {
  loading.value = true;
  try {
    await store.fetchBulletins({
      page,
      paieId: filterPaie.value,
      employeId: filterEmploye.value,
    });
  } finally {
    loading.value = false;
  }
};

const remove = async (row) => {
  if (!confirm('Supprimer ce bulletin ?')) return;
  await store.deleteBulletin(row.id);
  await fetch(pagination.value?.current_page || 1);
};

onMounted(async () => {
  await Promise.all([paieStore.fetchPaies(), employeStore.fetchEmployes()]);
  await fetch(1);
});
</script>
