<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Contrats d'armateur">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouveau contrat</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Libellé</th>
          <th class="p-2 border">Armateur</th>
          <th class="p-2 border">Devise</th>
          <th class="p-2 border">Taux base</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="contrat in contrats" :key="contrat.id">
          <td class="p-2 border">{{ contrat.code }}</td>
          <td class="p-2 border">{{ contrat.libelle }}</td>
          <td class="p-2 border">{{ contrat.armateur?.nom }}</td>
          <td class="p-2 border">{{ contrat.devise?.code }}</td>
          <td class="p-2 border">{{ contrat.taux_base }}</td>
          <td class="p-2 border">{{ contrat.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(contrat.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(contrat.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchContrats"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useContratArmateurStore } from '@/stores/contratArmateurStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useContratArmateurStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: "Contrats d'armateur" }];

const contrats = computed(() => store.contrats);
const pagination = computed(() => store.pagination);

const fetchContrats = (page = 1) => store.fetchContrats(page);
const goToCreate = () => router.push('/contrats-armateur/create');
const edit = (id) => router.push(`/contrats-armateur/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer ce contrat ?')) {
    await store.deleteContrat(id);
  }
};

onMounted(() => fetchContrats());
</script>
