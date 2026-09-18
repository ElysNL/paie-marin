<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppSelect v-model="form.armateur_id" label="Armateur" placeholder="-- Sélectionner --"
        :options="armateurs.map(a => ({ value: a.id, label: a.nom }))"
        :error="errors.armateur_id" required />
      <AppInput v-model="form.code" label="Code" :error="errors.code" required />
      <AppInput v-model="form.nom" label="Nom" :error="errors.nom" required />
      <AppInput v-model="form.immatriculation" label="Immatriculation" :error="errors.immatriculation" />
      <AppSelect v-model="form.compagnie_id" label="Compagnie" placeholder="-- Sélectionner --"
        :options="compagnies.map(c => ({ value: c.id, label: c.nom }))"
        :error="errors.compagnie_id" />
      <AppSelect v-model="form.pavillon_id" label="Pavillon (pays)" placeholder="-- Sélectionner --"
        :options="paysList.map(p => ({ value: p.id, label: p.nom }))"
        :error="errors.pavillon_id" />
      <AppInput v-model="form.type" label="Type" :error="errors.type" />
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
import { useNavireStore } from '@/stores/navireStore';
import { useArmateurStore } from '@/stores/armateurStore';
import { useCompagnieStore } from '@/stores/compagnieStore';
import { usePaysStore } from '@/stores/paysStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';

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

const errors = ref({});

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updateNavire(route.params.id, form);
    } else {
      await store.createNavire(form);
    }
    router.push('/navires');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  await Promise.all([
    armateurStore.fetchArmateurs(),
    compagnieStore.fetchCompagnies(),
    paysStore.fetchPays(),
  ]);
  if (isEdit.value) {
    const response = await apiClient.get(`/navires/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
