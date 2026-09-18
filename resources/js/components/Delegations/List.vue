<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Délégations</h1>
      <router-link to="/delegations/create">
        <AppButton>Nouvelle délégation</AppButton>
      </router-link>
    </div>

    <AppCard>
      <div class="mb-4">
        <AppSelect
          v-model="filterEmploye"
          :options="[{ value: null, label: 'Tous les employés' }, ...employes.map(e => ({ value: e.id, label: e.nom + ' ' + e.prenom }))]"
          @update:model-value="fetch(1)"
        />
      </div>

      <AppTable :columns="columns" :rows="store.delegations" row-key="id">
        <template #cell(employe)="{ row }">{{ row.employe?.nom }} {{ row.employe?.prenom }}</template>
        <template #cell(beneficiaire)="{ row }">{{ row.beneficiaire }}</template>
        <template #cell(montant)="{ row }" class="text-right">{{ formatMoney(row.montant, row.devise?.code) }}</template>
        <template #cell(periode)="{ row }" class="whitespace-nowrap">{{ formatDate(row.date_debut) }} → {{ formatDate(row.date_fin) }}</template>
        <template #cell(frequence)="{ row }">{{ row.frequence || '—' }}</template>
        <template #cell(statut)="{ row }"><AppBadge :statut="row.statut" /></template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/delegations/${row.id}/edit`">
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
        @page-change="fetch"
      />

      <AppEmpty v-if="!loading && store.delegations.length === 0" message="Aucune délégation enregistrée." />
      <AppLoading v-if="loading" message="Chargement des délégations…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useDelegationStore } from '@/stores/delegationStore';
import { useEmployeStore } from '@/stores/employeStore';
import { useToasts } from '@/services/toast';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppBadge from '@/components/AppBadge.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';
import { formatMoney, formatDate } from '@/utils/format';

const store = useDelegationStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Délégations' }];
const employeStore = useEmployeStore();
const { success } = useToasts();
const loading = ref(false);

const delegations = computed(() => store.delegations);
const pagination = computed(() => store.pagination);
const employes = computed(() => employeStore.employes);

const filterEmploye = ref(null);

const columns = [
  { key: 'employe', label: 'Employé' },
  { key: 'beneficiaire', label: 'Bénéficiaire' },
  { key: 'montant', label: 'Montant', align: 'right' },
  { key: 'periode', label: 'Période' },
  { key: 'frequence', label: 'Fréquence' },
  { key: 'statut', label: 'Statut' },
  { key: 'actions', label: 'Actions', align: 'right' },
];

const fetch = async (page = 1) => {
  loading.value = true;
  try {
    await store.fetchDelegations({ page, employeId: filterEmploye.value });
  } finally {
    loading.value = false;
  }
};

const remove = async (row) => {
  if (!confirm('Supprimer cette délégation ?')) return;
  await store.deleteDelegation(row.id);
  success('Délégation supprimée.');
};

onMounted(async () => {
  await employeStore.fetchEmployes();
  await fetch(1);
});
</script>
