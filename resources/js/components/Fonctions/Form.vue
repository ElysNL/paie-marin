<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppInput v-model="form.code" label="Code" :error="errors.code" required />
      <AppInput v-model="form.libelle" label="Libellé" :error="errors.libelle" required />
      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-on-surface-variant">Description</label>
        <textarea v-model="form.description" class="w-full rounded-xl border border-outline bg-surface-container px-4 py-3 text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
        <p v-if="errors.description" class="mt-1.5 text-sm text-error">{{ errors.description[0] }}</p>
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
import { useFonctionStore } from '@/stores/fonctionStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppButton from '@/components/AppButton.vue';

const store = useFonctionStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} fonction`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Fonctions', to: '/fonctions' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];

const form = reactive({
  code: '',
  libelle: '',
  description: '',
  actif: true,
});

const errors = ref({});

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updateFonction(route.params.id, form);
    } else {
      await store.createFonction(form);
    }
    router.push('/fonctions');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  if (isEdit.value) {
    const response = await apiClient.get(`/fonctions/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
