<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Classifications">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle classification</button>
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
        <tr v-for="classification in classifications" :key="classification.id">
          <td class="p-2 border">{{ classification.code }}</td>
          <td class="p-2 border">{{ classification.libelle }}</td>
          <td class="p-2 border">{{ classification.description }}</td>
          <td class="p-2 border">{{ classification.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(classification.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(classification.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchClassifications"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useClassificationStore } from '@/stores/classificationStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useClassificationStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Classifications' }];

const classifications = computed(() => store.classifications);
const pagination = computed(() => store.pagination);

const fetchClassifications = (page = 1) => store.fetchClassifications(page);
const goToCreate = () => router.push('/classifications/create');
const edit = (id) => router.push(`/classifications/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cette classification ?')) {
    await store.deleteClassification(id);
  }
};

onMounted(() => fetchClassifications());
</script>
