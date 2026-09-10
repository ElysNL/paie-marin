<!-- resources/js/components/Armateurs/Form.vue -->
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
        <label>Adresse</label>
        <textarea v-model="form.adresse" class="w-full border p-2 rounded"></textarea>
      </div>
      <div>
        <label>Téléphone</label>
        <input v-model="form.telephone" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Email</label>
        <input v-model="form.email" type="email" class="w-full border p-2 rounded" />
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
import { useArmateurStore } from '@/stores/armateurStore';
import { usePaysStore } from '@/stores/paysStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useArmateurStore();
const paysStore = usePaysStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvel'} armateur`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Armateurs', to: '/armateurs' },
  { label: isEdit.value ? 'Modifier' : 'Nouveau' },
];
const paysList = computed(() => paysStore.pays);

const form = reactive({
  code: '',
  nom: '',
  pays_id: null,
  adresse: '',
  telephone: '',
  email: '',
  actif: true,
});

const submit = async () => {
  if (isEdit.value) {
    await store.updateArmateur(route.params.id, form);
  } else {
    await store.createArmateur(form);
  }
  router.push('/armateurs');
};

onMounted(async () => {
  paysStore.fetchPays();
  if (isEdit.value) {
    const response = await apiClient.get(`/armateurs/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
