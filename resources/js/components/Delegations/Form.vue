<template>
  <div class="max-w-xl">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppSelect v-model="form.employe_id" label="Employé" placeholder="-- Sélectionner --"
        :options="employes.map(e => ({ value: e.id, label: e.nom + ' ' + e.prenom }))"
        :error="errors.employe_id" required />
      <AppInput v-model="form.beneficiaire" label="Bénéficiaire" :error="errors.beneficiaire" required />
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model.number="form.montant" label="Montant" type="number" min="0" step="0.01" :error="errors.montant" required />
        <AppSelect v-model="form.devise_id" label="Devise" placeholder="-- Sélectionner --"
          :options="devises.map(d => ({ value: d.id, label: d.code }))"
          :error="errors.devise_id" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model="form.date_debut" label="Date de début" type="date" :error="errors.date_debut" required />
        <AppInput v-model="form.date_fin" label="Date de fin" type="date" :error="errors.date_fin" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppSelect v-model="form.frequence" label="Fréquence"
          :options="[{ value: 'mensuel', label: 'Mensuel' }, { value: 'ponctuel', label: 'Ponctuel' }]" />
        <AppSelect v-model="form.statut" label="Statut"
          :options="[{ value: 'actif', label: 'Actif' }, { value: 'termine', label: 'Terminé' }, { value: 'annule', label: 'Annulé' }]" />
      </div>
      <div class="flex gap-2">
        <AppButton type="submit">Enregistrer</AppButton>
        <AppButton variant="secondary" type="button" @click="$router.back()">Annuler</AppButton>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed, onMounted } from 'vue';
import { useDelegationStore } from '@/stores/delegationStore';
import { useEmployeStore } from '@/stores/employeStore';
import { useDeviseStore } from '@/stores/deviseStore';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';
import { useToasts } from '@/services/toast';
import apiClient from '@/services/api';

const store = useDelegationStore();
const employeStore = useEmployeStore();
const deviseStore = useDeviseStore();
const router = useRouter();
const route = useRoute();
const { success } = useToasts();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} délégation`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Délégations', to: '/delegations' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];
const employes = computed(() => employeStore.employes);
const devises = computed(() => deviseStore.devises);

const form = reactive({
  employe_id: null,
  beneficiaire: '',
  montant: null,
  devise_id: null,
  date_debut: '',
  date_fin: '',
  frequence: 'mensuel',
  statut: 'actif',
});
const errors = reactive({});

const submit = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);
  try {
    if (isEdit.value) {
      await store.updateDelegation(route.params.id, form);
      success('Délégation mise à jour.');
    } else {
      await store.createDelegation(form);
      success('Délégation créée.');
    }
    router.push('/delegations');
  } catch (e) {
    if (e.response?.status === 422 && e.response.data.errors) {
      Object.assign(errors, e.response.data.errors);
    }
  }
};

onMounted(async () => {
  await Promise.all([employeStore.fetchEmployes(), deviseStore.fetchDevises()]);
  if (isEdit.value) {
    const res = await apiClient.get(`/delegations/${route.params.id}`);
    const data = res.data;
    form.employe_id = data.employe_id;
    form.beneficiaire = data.beneficiaire || '';
    form.montant = Number(data.montant);
    form.devise_id = data.devise_id;
    form.date_debut = data.date_debut ? String(data.date_debut).slice(0, 10) : '';
    form.date_fin = data.date_fin ? String(data.date_fin).slice(0, 10) : '';
    form.frequence = data.frequence || 'mensuel';
    form.statut = data.statut || 'actif';
  }
});
</script>
