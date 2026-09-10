<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Employés">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvel employé</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Matricule</th>
          <th class="p-2 border">Nom complet</th>
          <th class="p-2 border">Nationalité</th>
          <th class="p-2 border">Téléphone</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employe in employes" :key="employe.id">
          <td class="p-2 border">{{ employe.matricule }}</td>
          <td class="p-2 border">{{ employe.nom }} {{ employe.prenom }}</td>
          <td class="p-2 border">{{ employe.nationalite?.nationalite || employe.nationalite?.nom }}</td>
          <td class="p-2 border">{{ employe.telephone }}</td>
          <td class="p-2 border">{{ employe.email }}</td>
          <td class="p-2 border">{{ employe.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(employe.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(employe.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchEmployes"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useEmployeStore } from '@/stores/employeStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useEmployeStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Employés' }];

const employes = computed(() => store.employes);
const pagination = computed(() => store.pagination);

const fetchEmployes = (page = 1) => store.fetchEmployes(page);
const goToCreate = () => router.push('/employes/create');
const edit = (id) => router.push(`/employes/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cet employé ?')) {
    await store.deleteEmploye(id);
  }
};

onMounted(() => fetchEmployes());
</script>
