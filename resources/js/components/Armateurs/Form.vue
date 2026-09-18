<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppInput v-model="form.code" label="Code" :error="errors.code" required />
      <AppInput v-model="form.nom" label="Nom" :error="errors.nom" required />
      <AppSelect v-model="form.pays_id" label="Pays" placeholder="-- Sélectionner --"
        :options="paysList.map(p => ({ value: p.id, label: p.nom }))"
        :error="errors.pays_id" />
      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-on-surface-variant">Adresse</label>
        <textarea v-model="form.adresse" class="w-full rounded-xl border border-outline bg-surface-container px-4 py-3 text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
        <p v-if="errors.adresse" class="mt-1.5 text-sm text-error">{{ errors.adresse[0] }}</p>
      </div>
      <AppInput v-model="form.telephone" label="Téléphone" :error="errors.telephone" />
      <AppInput v-model="form.email" label="Email" type="email" :error="errors.email" />
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
import { useArmateurStore } from '@/stores/armateurStore';
import { usePaysStore } from '@/stores/paysStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';

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

const errors = ref({});

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updateArmateur(route.params.id, form);
    } else {
      await store.createArmateur(form);
    }
    router.push('/armateurs');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  await paysStore.fetchPays();
  if (isEdit.value) {
    const response = await apiClient.get(`/armateurs/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
