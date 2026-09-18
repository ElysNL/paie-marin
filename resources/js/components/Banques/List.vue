<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Banques</h1>
      <router-link to="/banques/create">
        <AppButton>Nouvelle banque</AppButton>
      </router-link>
    </div>

    <AppCard>
      <AppTable :columns="columns" :rows="store.banques" row-key="id">
        <template #cell(code)="{ row }">{{ row.code }}</template>
        <template #cell(nom)="{ row }">{{ row.nom }}</template>
        <template #cell(pays)="{ row }">{{ row.pays?.nom || '—' }}</template>
        <template #cell(actif)="{ row }">
          <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"
            :class="row.actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
            {{ row.actif ? 'Oui' : 'Non' }}
          </span>
        </template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/banques/${row.id}/edit`">
              <AppButton variant="text" class="!px-2 !py-1">Modifier</AppButton>
            </router-link>
            <AppButton variant="text" class="!px-2 !py-1 !text-red-600 hover:!bg-red-50" @click="remove(row)">
              Supprimer
            </AppButton>
          </div>
        </template>
      </AppTable>

      <AppEmpty v-if="!loading && store.banques.length === 0" message="Aucune banque enregistrée." />
      <AppLoading v-if="loading" message="Chargement des banques…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useBanqueStore } from '@/stores/banqueStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';

const store = useBanqueStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Banques' }];
const loading = ref(false);

const columns = [
  { key: 'code', label: 'Code' },
  { key: 'nom', label: 'Nom' },
  { key: 'pays', label: 'Pays' },
  { key: 'actif', label: 'Actif' },
  { key: 'actions', label: 'Actions', align: 'right' },
];

const fetchData = async () => {
  loading.value = true;
  try {
    await store.fetchBanques();
  } finally {
    loading.value = false;
  }
};

const remove = async (row) => {
  if (!confirm('Voulez-vous supprimer cette banque ?')) return;
  await store.deleteBanque(row.id);
};

onMounted(() => fetchData());
</script>
