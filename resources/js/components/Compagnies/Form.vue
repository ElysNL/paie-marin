<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label>Code</label>
        <input v-model="form.code" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Nom</label>
        <input v-model="form.nom" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Pays</label>
        <select v-model="form.pays_id" class="w-full border p-2 rounded">
          <option :value="null">-- Sélectionner --</option>
          <option v-for="pays in paysList" :key="pays.id" :value="pays.id">
            {{ pays.nom }}
          </option>
        </select>
      </div>
      <div>
        <label>Actif</label>
        <input v-model="form.actif" type="checkbox" class="ml-2" />
      </div>
      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
        <button type="button" @click="$router.back()" class="px-4 py-2 border rounded">Annuler</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed, onMounted } from 'vue';
import { useCompagnieStore } from '@/stores/compagnieStore';
import { usePaysStore } from '@/stores/paysStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useCompagnieStore();
const paysStore = usePaysStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} compagnie`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Compagnies', to: '/compagnies' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];
const paysList = computed(() => paysStore.pays);

const form = reactive({
  code: '',
  nom: '',
  pays_id: null,
  actif: true,
});

const submit = async () => {
  if (isEdit.value) {
    await store.updateCompagnie(route.params.id, form);
  } else {
    await store.createCompagnie(form);
  }
  router.push('/compagnies');
};

onMounted(async () => {
  await paysStore.fetchPays();
  if (isEdit.value) {
    const response = await apiClient.get(`/compagnies/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
