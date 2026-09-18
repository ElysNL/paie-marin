<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppSelect v-model="form.employe_id" label="Employé" placeholder="-- Sélectionner --"
        :options="employes.map(e => ({ value: e.id, label: e.nom + ' ' + e.prenom }))"
        :error="errors.employe_id" required />
      <AppSelect v-model="form.navire_id" label="Navire" placeholder="-- Sélectionner --"
        :options="navires.map(n => ({ value: n.id, label: n.nom }))"
        :error="errors.navire_id" required />
      <AppSelect v-model="form.fonction_id" label="Fonction" placeholder="-- Sélectionner --"
        :options="fonctions.map(f => ({ value: f.id, label: f.libelle }))"
        :error="errors.fonction_id" required />
      <AppSelect v-model="form.contrat_armateur_id" label="Contrat armateur" placeholder="-- Sélectionner --"
        :options="contrats.map(c => ({ value: c.id, label: c.libelle }))"
        :error="errors.contrat_armateur_id" required />
      <AppInput v-model="form.date_embt" label="Date d'embarquement" type="date" :error="errors.date_embt" required />
      <AppInput v-model="form.date_debt" label="Date de débarquement" type="date" :error="errors.date_debt" />
      <AppInput v-model.number="form.taux_journalier" label="Taux journalier" type="number" step="0.01" :error="errors.taux_journalier" required />
      <AppSelect v-model="form.devise_id" label="Devise" placeholder="-- Sélectionner --"
        :options="devises.map(d => ({ value: d.id, label: d.code }))"
        :error="errors.devise_id" required />
      <AppSelect v-model="form.statut" label="Statut"
        :options="[{ value: 'actif', label: 'Actif' }, { value: 'termine', label: 'Terminé' }, { value: 'annule', label: 'Annulé' }]"
        :error="errors.statut" />
      <div class="flex gap-2">
        <AppButton type="submit">Enregistrer</AppButton>
        <AppButton variant="secondary" type="button" @click="$router.back()">Annuler</AppButton>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
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
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';

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

const errors = ref({});

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updateAffectation(route.params.id, form);
    } else {
      await store.createAffectation(form);
    }
    router.push('/affectations');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  await Promise.all([
    employeStore.fetchEmployes(),
    navireStore.fetchNavires(),
    fonctionStore.fetchFonctions(),
    contratStore.fetchContrats(),
    deviseStore.fetchDevises(),
  ]);
  if (isEdit.value) {
    const response = await apiClient.get(`/affectations/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
