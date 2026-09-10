<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Navires">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouveau navire</button>
      </template>
    </PageHeader>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Code</th>
          <th class="p-2 border">Nom</th>
          <th class="p-2 border">Immatriculation</th>
          <th class="p-2 border">Armateur</th>
          <th class="p-2 border">Type</th>
          <th class="p-2 border">Actif</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="navire in navires" :key="navire.id">
          <td class="p-2 border">{{ navire.code }}</td>
          <td class="p-2 border">{{ navire.nom }}</td>
          <td class="p-2 border">{{ navire.immatriculation }}</td>
          <td class="p-2 border">{{ navire.armateur?.nom }}</td>
          <td class="p-2 border">{{ navire.type }}</td>
          <td class="p-2 border">{{ navire.actif ? 'Oui' : 'Non' }}</td>
          <td class="p-2 border">
            <button @click="edit(navire.id)" class="text-blue-600 mr-2">Modifier</button>
            <button @click="remove(navire.id)" class="text-red-600">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>

    <Pagination
      :current="pagination?.current_page"
      :last="pagination?.last_page"
      @page-change="fetchNavires"
    />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useNavireStore } from '@/stores/navireStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useNavireStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Navires' }];

const navires = computed(() => store.navires);
const pagination = computed(() => store.pagination);

const fetchNavires = (page = 1) => store.fetchNavires(page);
const goToCreate = () => router.push('/navires/create');
const edit = (id) => router.push(`/navires/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer ce navire ?')) {
    await store.deleteNavire(id);
  }
};

onMounted(() => fetchNavires());
</script>
