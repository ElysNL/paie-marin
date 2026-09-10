<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Armateurs">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvel armateur</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Nom</th>
          <th class="p-2 border">Pays</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="armateur in armateurs" :key="armateur.id">
          <td class="p-2 border">{{ armateur.code }}</td>
          <td class="p-2 border">{{ armateur.nom }}</td>
          <td class="p-2 border">{{ armateur.pays?.nom }}</td>
          <td class="p-2 border">
            <button @click="edit(armateur.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(armateur.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchArmateurs"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useArmateurStore } from '@/stores/armateurStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useArmateurStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Armateurs' }];

const armateurs = computed(() => store.armateurs);
const pagination = computed(() => store.pagination);

const fetchArmateurs = (page = 1) => store.fetchArmateurs(page);

const goToCreate = () => router.push('/armateurs/create');

const edit = (id) => router.push(`/armateurs/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cet armateur ?')) {
    await store.deleteArmateur(id);
  }
};

onMounted(() => fetchArmateurs());
</script>
