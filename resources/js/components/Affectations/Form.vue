<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label>Employé</label>
        <select v-model="form.employe_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="employe in employes" :key="employe.id" :value="employe.id">
            {{ employe.nom }} {{ employe.prenom }}
          </option>
        </select>
      </div>
      <div>
        <label>Navire</label>
        <select v-model="form.navire_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="navire in navires" :key="navire.id" :value="navire.id">
            {{ navire.nom }}
          </option>
        </select>
      </div>
      <div>
        <label>Fonction</label>
        <select v-model="form.fonction_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="fonction in fonctions" :key="fonction.id" :value="fonction.id">
            {{ fonction.libelle }}
          </option>
        </select>
      </div>
      <div>
        <label>Contrat armateur</label>
        <select v-model="form.contrat_armateur_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="contrat in contrats" :key="contrat.id" :value="contrat.id">
            {{ contrat.libelle }}
          </option>
        </select>
      </div>
      <div>
        <label>Date d'embarquement</label>
        <input v-model="form.date_embt" type="date" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Date de débarquement</label>
        <input v-model="form.date_debt" type="date" class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Taux journalier</label>
        <input v-model.number="form.taux_journalier" type="number" step="0.01" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Devise</label>
        <select v-model="form.devise_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="devise in devises" :key="devise.id" :value="devise.id">
            {{ devise.code }}
          </option>
        </select>
      </div>
      <div>
        <label>Statut</label>
        <select v-model="form.statut" class="w-full border p-2 rounded">
          <option value="actif">Actif</option>
          <option value="termine">Terminé</option>
          <option value="annule">Annulé</option>
        </select>
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
import { useAffectationStore } from '@/stores/affectationStore';
import { useEmployeStore } from '@/stores/employeStore';
import { useNavireStore } from '@/stores/navireStore';
import { useFonctionStore } from '@/stores/fonctionStore';
import { useContratArmateurStore } from '@/stores/contratArmateurStore';
import { useDeviseStore } from '@/stores/deviseStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useAffectationStore();
const employeStore = useEmployeStore();
const navireStore = useNavireStore();
const fonctionStore = useFonctionStore();
const contratStore = useContratArmateurStore();
const deviseStore = useDeviseStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} affectation`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Affectations', to: '/affectations' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];
const employes = computed(() => employeStore.employes);
const navires = computed(() => navireStore.navires);
const fonctions = computed(() => fonctionStore.fonctions);
const contrats = computed(() => contratStore.contrats);
const devises = computed(() => deviseStore.devises);

const form = reactive({
  employe_id: null,
  navire_id: null,
  fonction_id: null,
  contrat_armateur_id: null,
  date_embt: '',
  date_debt: '',
  taux_journalier: null,
  devise_id: null,
  statut: 'actif',
});

const submit = async () => {
  if (isEdit.value) {
    await store.updateAffectation(route.params.id, form);
  } else {
    await store.createAffectation(form);
  }
  router.push('/affectations');
};

onMounted(async () => {
  employeStore.fetchEmployes();
  navireStore.fetchNavires();
  fonctionStore.fetchFonctions();
  contratStore.fetchContrats();
  deviseStore.fetchDevises();
  if (isEdit.value) {
    const response = await apiClient.get(`/affectations/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
