<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppInput v-model="form.code" label="Code" :error="errors.code" required />
      <AppInput v-model="form.nom" label="Nom" :error="errors.nom" required />
      <AppSelect v-model="form.pays_id" label="Pays" :options="paysOptions" :error="errors.pays_id" />
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
import { useBanqueStore } from '@/stores/banqueStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';

const store = useBanqueStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} banque`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Banques', to: '/banques' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];

const form = reactive({
  code: '',
  nom: '',
  pays_id: null,
  actif: true,
});

const errors = ref({});
const paysOptions = ref([]);

const submit = async () => {
  errors.value = {};
  try {
    if (isEdit.value) {
      await store.updateBanque(route.params.id, form);
    } else {
      await store.createBanque(form);
    }
    router.push('/banques');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors || {};
    }
  }
};

onMounted(async () => {
  // Load pays for the dropdown
  const paysRes = await apiClient.get('/pays');
  paysOptions.value = paysRes.data.data.map(p => ({ value: p.id, label: `${p.code} — ${p.nom}` }));

  if (isEdit.value) {
    const response = await apiClient.get(`/banques/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
