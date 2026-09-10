<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Pays">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouveau pays</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Nom</th>
          <th class="p-2 border">Nationalité</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="pays in pays" :key="pays.id">
          <td class="p-2 border">{{ pays.code }}</td>
          <td class="p-2 border">{{ pays.nom }}</td>
          <td class="p-2 border">{{ pays.nationalite }}</td>
          <td class="p-2 border">{{ pays.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(pays.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(pays.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchPays"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { usePaysStore } from '@/stores/paysStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = usePaysStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Pays' }];

const pays = computed(() => store.pays);
const pagination = computed(() => store.pagination);

const fetchPays = (page = 1) => store.fetchPays(page);
const goToCreate = () => router.push('/pays/create');
const edit = (id) => router.push(`/pays/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer ce pays ?')) {
    await store.deletePays(id);
  }
};

onMounted(() => fetchPays());
</script>
