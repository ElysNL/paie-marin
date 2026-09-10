<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label>Armateur</label>
        <select v-model="form.armateur_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="armateur in armateurs" :key="armateur.id" :value="armateur.id">
            {{ armateur.nom }}
          </option>
        </select>
      </div>
      <div>
        <label>Code</label>
        <input v-model="form.code" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Nom</label>
        <input v-model="form.nom" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Immatriculation</label>
        <input v-model="form.immatriculation" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Compagnie</label>
        <select v-model="form.compagnie_id" class="w-full border p-2 rounded">
          <option :value="null">-- Sélectionner --</option>
          <option v-for="compagnie in compagnies" :key="compagnie.id" :value="compagnie.id">
            {{ compagnie.nom }}
          </option>
        </select>
      </div>
      <div>
        <label>Pavillon (pays)</label>
        <select v-model="form.pavillon_id" class="w-full border p-2 rounded">
          <option :value="null">-- Sélectionner --</option>
          <option v-for="pays in paysList" :key="pays.id" :value="pays.id">
            {{ pays.nom }}
          </option>
        </select>
      </div>
      <div>
        <label>Type</label>
        <input v-model="form.type" class="w-full border p-2 rounded" />
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
import { useNavireStore } from '@/stores/navireStore';
import { useArmateurStore } from '@/stores/armateurStore';
import { useCompagnieStore } from '@/stores/compagnieStore';
import { usePaysStore } from '@/stores/paysStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useNavireStore();
const armateurStore = useArmateurStore();
const compagnieStore = useCompagnieStore();
const paysStore = usePaysStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouveau'} navire`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Navires', to: '/navires' },
  { label: isEdit.value ? 'Modifier' : 'Nouveau' },
];
const armateurs = computed(() => armateurStore.armateurs);
const compagnies = computed(() => compagnieStore.compagnies);
const paysList = computed(() => paysStore.pays);

const form = reactive({
  armateur_id: null,
  code: '',
  nom: '',
  immatriculation: '',
  compagnie_id: null,
  pavillon_id: null,
  type: '',
  actif: true,
});

const submit = async () => {
  if (isEdit.value) {
    await store.updateNavire(route.params.id, form);
  } else {
    await store.createNavire(form);
  }
  router.push('/navires');
};

onMounted(async () => {
  armateurStore.fetchArmateurs();
  compagnieStore.fetchCompagnies();
  paysStore.fetchPays();
  if (isEdit.value) {
    const response = await apiClient.get(`/navires/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
