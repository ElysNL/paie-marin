<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Fonctions">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle fonction</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Libellé</th>
          <th class="p-2 border">Description</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="fonction in fonctions" :key="fonction.id">
          <td class="p-2 border">{{ fonction.code }}</td>
          <td class="p-2 border">{{ fonction.libelle }}</td>
          <td class="p-2 border">{{ fonction.description }}</td>
          <td class="p-2 border">{{ fonction.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(fonction.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(fonction.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchFonctions"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useFonctionStore } from '@/stores/fonctionStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useFonctionStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Fonctions' }];

const fonctions = computed(() => store.fonctions);
const pagination = computed(() => store.pagination);

const fetchFonctions = (page = 1) => store.fetchFonctions(page);
const goToCreate = () => router.push('/fonctions/create');
const edit = (id) => router.push(`/fonctions/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cette fonction ?')) {
    await store.deleteFonction(id);
  }
};

onMounted(() => fetchFonctions());
</script>
