<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppInput v-model="form.code" label="Code" :error="errors.code" required />
      <AppInput v-model="form.nom" label="Nom" :error="errors.nom" required />
      <AppInput v-model="form.nationalite" label="Nationalité" :error="errors.nationalite" />
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
import { usePaysStore } from '@/stores/paysStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppButton from '@/components/AppButton.vue';

const store = usePaysStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouveau'} pays`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Pays', to: '/pays' },
  { label: isEdit.value ? 'Modifier' : 'Nouveau' },
];

const form = reactive({
  code: '',
  nom: '',
  nationalite: '',
  actif: true,
});

const errors = ref({});

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updatePays(route.params.id, form);
    } else {
      await store.createPays(form);
    }
    router.push('/pays');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  if (isEdit.value) {
    const response = await apiClient.get(`/pays/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
