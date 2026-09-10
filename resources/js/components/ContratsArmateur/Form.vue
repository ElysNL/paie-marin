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
        <label>Libellé</label>
        <input v-model="form.libelle" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Devise</label>
        <select v-model="form.devise_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="devise in devises" :key="devise.id" :value="devise.id">
            {{ devise.code }} - {{ devise.libelle }}
          </option>
        </select>
      </div>
      <div>
        <label>Date début</label>
        <input v-model="form.date_debut" type="date" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Date fin</label>
        <input v-model="form.date_fin" type="date" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Taux de base</label>
        <input v-model.number="form.taux_base" type="number" step="0.01" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Conditions</label>
        <textarea v-model="form.conditions" class="w-full border p-2 rounded"></textarea>
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
import { useContratArmateurStore } from '@/stores/contratArmateurStore';
import { useArmateurStore } from '@/stores/armateurStore';
import { useDeviseStore } from '@/stores/deviseStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useContratArmateurStore();
const armateurStore = useArmateurStore();
const deviseStore = useDeviseStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : "Nouveau"} contrat d'armateur`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: "Contrats d'armateur", to: '/contrats-armateur' },
  { label: isEdit.value ? 'Modifier' : 'Nouveau' },
];
const armateurs = computed(() => armateurStore.armateurs);
const devises = computed(() => deviseStore.devises);

const form = reactive({
  armateur_id: null,
  code: '',
  libelle: '',
  devise_id: null,
  date_debut: '',
  date_fin: '',
  taux_base: null,
  conditions: '',
  actif: true,
});

const submit = async () => {
  if (isEdit.value) {
    await store.updateContrat(route.params.id, form);
  } else {
    await store.createContrat(form);
  }
  router.push('/contrats-armateur');
};

onMounted(async () => {
  armateurStore.fetchArmateurs();
  deviseStore.fetchDevises();
  if (isEdit.value) {
    const response = await apiClient.get(`/contrats-armateur/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
