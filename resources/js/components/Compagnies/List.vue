<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Compagnies">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle compagnie</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Nom</th>
          <th class="p-2 border">Pays</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="compagnie in compagnies" :key="compagnie.id">
          <td class="p-2 border">{{ compagnie.code }}</td>
          <td class="p-2 border">{{ compagnie.nom }}</td>
          <td class="p-2 border">{{ compagnie.pays?.nom }}</td>
          <td class="p-2 border">{{ compagnie.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(compagnie.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(compagnie.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchCompagnies"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useCompagnieStore } from '@/stores/compagnieStore';
import { usePaysStore } from '@/stores/paysStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useCompagnieStore();
const paysStore = usePaysStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Compagnies' }];

const compagnies = computed(() => store.compagnies);
const paysList = computed(() => paysStore.pays);
const pagination = computed(() => store.pagination);

const fetchCompagnies = (page = 1) => store.fetchCompagnies(page);
const goToCreate = () => router.push('/compagnies/create');
const edit = (id) => router.push(`/compagnies/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cette compagnie ?')) {
    await store.deleteCompagnie(id);
  }
};

onMounted(() => {
  fetchCompagnies();
  paysStore.fetchPays();
});
</script>
