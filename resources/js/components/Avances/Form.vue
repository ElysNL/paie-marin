<template>
  <div class="max-w-xl">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppSelect v-model="form.employe_id" label="Employé" placeholder="-- Sélectionner --"
        :options="employes.map(e => ({ value: e.id, label: e.nom + ' ' + e.prenom }))"
        :error="errors.employe_id" required />
      <AppInput v-model="form.date_avance" label="Date de l'avance" type="date" :error="errors.date_avance" required />
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model.number="form.montant" label="Montant" type="number" min="0" step="0.01" :error="errors.montant" required />
        <AppSelect v-model="form.devise_id" label="Devise" placeholder="-- Sélectionner --"
          :options="devises.map(d => ({ value: d.id, label: d.code }))"
          :error="errors.devise_id" />
      </div>
      <AppInput v-model="form.motif" label="Motif" :error="errors.motif" />
      <div class="flex gap-2">
        <AppButton type="submit">Enregistrer</AppButton>
        <AppButton variant="secondary" type="button" @click="$router.back()">Annuler</AppButton>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed, onMounted } from 'vue';
import { useAvanceStore } from '@/stores/avanceStore';
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

const store = useAvanceStore();
const employeStore = useEmployeStore();
const deviseStore = useDeviseStore();
const router = useRouter();
const route = useRoute();
const { success } = useToasts();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} avance`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Avances', to: '/avances' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];
const employes = computed(() => employeStore.employes);
const devises = computed(() => deviseStore.devises);

const form = reactive({
  employe_id: null,
  date_avance: '',
  montant: null,
  devise_id: null,
  motif: '',
});
const errors = reactive({});

const submit = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);
  try {
    if (isEdit.value) {
      await store.updateAvance(route.params.id, form);
      success('Avance mise à jour.');
    } else {
      await store.createAvance(form);
      success('Avance créée.');
    }
    router.push('/avances');
  } catch (e) {
    if (e.response?.status === 422 && e.response.data.errors) {
      Object.assign(errors, e.response.data.errors);
    }
  }
};

onMounted(async () => {
  await Promise.all([employeStore.fetchEmployes(), deviseStore.fetchDevises()]);
  if (isEdit.value) {
    const res = await apiClient.get(`/avances/${route.params.id}`);
    const data = res.data;
    form.employe_id = data.employe_id;
    form.date_avance = data.date_avance ? String(data.date_avance).slice(0, 10) : '';
    form.montant = Number(data.montant);
    form.devise_id = data.devise_id;
    form.motif = data.motif || '';
  }
});
</script>
