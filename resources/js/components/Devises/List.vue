<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Devises">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle devise</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Libellé</th>
          <th class="p-2 border">Symbole</th>
          <th class="p-2 border">Décimales</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="devise in devises" :key="devise.id">
          <td class="p-2 border">{{ devise.code }}</td>
          <td class="p-2 border">{{ devise.libelle }}</td>
          <td class="p-2 border">{{ devise.symbole }}</td>
          <td class="p-2 border">{{ devise.nb_decimales }}</td>
          <td class="p-2 border">{{ devise.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(devise.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(devise.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchDevises"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useDeviseStore } from '@/stores/deviseStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useDeviseStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Devises' }];

const devises = computed(() => store.devises);
const pagination = computed(() => store.pagination);

const fetchDevises = (page = 1) => store.fetchDevises(page);
const goToCreate = () => router.push('/devises/create');
const edit = (id) => router.push(`/devises/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cette devise ?')) {
    await store.deleteDevise(id);
  }
};

onMounted(() => fetchDevises());
</script>
