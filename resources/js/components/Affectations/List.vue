<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Affectations">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle affectation</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Employé</th>
          <th class="p-2 border">Navire</th>
          <th class="p-2 border">Fonction</th>
          <th class="p-2 border">Date embarquement</th>
          <th class="p-2 border">Taux journalier</th>
          <th class="p-2 border">Statut</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="affectation in affectations" :key="affectation.id">
          <td class="p-2 border">{{ affectation.employe?.nom }} {{ affectation.employe?.prenom }}</td>
          <td class="p-2 border">{{ affectation.navire?.nom }}</td>
          <td class="p-2 border">{{ affectation.fonction?.libelle }}</td>
          <td class="p-2 border">{{ affectation.date_embt }}</td>
          <td class="p-2 border">{{ affectation.taux_journalier }}</td>
          <td class="p-2 border">{{ affectation.statut }}</td>
          <td class="p-2 border">
            <button @click="edit(affectation.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(affectation.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchAffectations"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useAffectationStore } from '@/stores/affectationStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useAffectationStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Affectations' }];

const affectations = computed(() => store.affectations);
const pagination = computed(() => store.pagination);

const fetchAffectations = (page = 1) => store.fetchAffectations(page);
const goToCreate = () => router.push('/affectations/create');
const edit = (id) => router.push(`/affectations/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cette affectation ?')) {
    await store.deleteAffectation(id);
  }
};

onMounted(() => fetchAffectations());
</script>
