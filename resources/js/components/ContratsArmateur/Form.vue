<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppSelect v-model="form.armateur_id" label="Armateur" placeholder="-- Sélectionner --"
        :options="armateurs.map(a => ({ value: a.id, label: a.nom }))"
        :error="errors.armateur_id" required />
      <AppInput v-model="form.code" label="Code" :error="errors.code" required />
      <AppInput v-model="form.libelle" label="Libellé" :error="errors.libelle" required />
      <AppSelect v-model="form.devise_id" label="Devise" placeholder="-- Sélectionner --"
        :options="devises.map(d => ({ value: d.id, label: d.code + ' - ' + d.libelle }))"
        :error="errors.devise_id" required />
      <AppInput v-model="form.date_debut" label="Date début" type="date" :error="errors.date_debut" required />
      <AppInput v-model="form.date_fin" label="Date fin" type="date" :error="errors.date_fin" />
      <AppInput v-model.number="form.taux_base" label="Taux de base" type="number" step="0.01" :error="errors.taux_base" />
      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-on-surface-variant">Conditions</label>
        <textarea v-model="form.conditions" class="w-full rounded-xl border border-outline bg-surface-container px-4 py-3 text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
        <p v-if="errors.conditions" class="mt-1.5 text-sm text-error">{{ errors.conditions[0] }}</p>
      </div>
      <div class="mb-4">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-on-surface-variant">
          <input v-model="form.actif" type="checkbox" class="h-4 w-4 rounded border-outline text-primary focus:ring-primary/20" /> Actif
        </label>
      </div>
      <div class="flex gap-2">
        <AppButton type="submit">Enregistrer</AppButton>
        <AppButton variant="secondary" type="button" @click="$router.back()">Annuler</AppButton>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useContratArmateurStore } from '@/stores/contratArmateurStore';
import { useArmateurStore } from '@/stores/armateurStore';
import { useDeviseStore } from '@/stores/deviseStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';

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

const errors = ref({});

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updateContrat(route.params.id, form);
    } else {
      await store.createContrat(form);
    }
    router.push('/contrats-armateur');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  await Promise.all([
    armateurStore.fetchArmateurs(),
    deviseStore.fetchDevises(),
  ]);
  if (isEdit.value) {
    const response = await apiClient.get(`/contrats-armateur/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
