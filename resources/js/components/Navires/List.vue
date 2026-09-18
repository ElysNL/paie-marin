<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-bold">Navires</h1>
      <router-link to="/navires/create">
        <AppButton>Nouveau navire</AppButton>
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

      <AppTable :columns="columns" :rows="store.navires" row-key="id">
        <template #cell(code)="{ row }">{{ row.code }}</template>
        <template #cell(nom)="{ row }">{{ row.nom }}</template>
        <template #cell(immatriculation)="{ row }">{{ row.immatriculation }}</template>
        <template #cell(armateur)="{ row }">{{ row.armateur?.nom }}</template>
        <template #cell(type)="{ row }">{{ row.type }}</template>
        <template #cell(actif)="{ row }">
          <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold"
            :class="row.actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
            {{ row.actif ? 'Oui' : 'Non' }}
          </span>
        </template>
        <template #cell(actions)="{ row }">
          <div class="flex items-center gap-1">
            <router-link :to="`/navires/${row.id}/edit`">
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

      <AppEmpty v-if="!loading && store.navires.length === 0" message="Aucun navire enregistré." />
      <AppLoading v-if="loading" message="Chargement des navires…" />
    </AppCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useNavireStore } from '@/stores/navireStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import AppLoading from '@/components/AppLoading.vue';
import Pagination from '@/components/Pagination.vue';

const store = useNavireStore();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Navires' }];
const loading = ref(false);
const search = ref('');

const columns = [
  { key: 'code', label: 'Code' },
  { key: 'nom', label: 'Nom' },
  { key: 'immatriculation', label: 'Immatriculation' },
  { key: 'armateur', label: 'Armateur' },
  { key: 'type', label: 'Type' },
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
    await store.fetchNavires(page);
  } finally {
    loading.value = false;
  }
};

const goToPage = (page) => fetchData(page);

const remove = async (row) => {
  if (!confirm('Voulez-vous supprimer ce navire ?')) return;
  await store.deleteNavire(row.id);
};

onMounted(() => fetchData());
</script>
