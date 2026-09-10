<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label>Code</label>
        <input v-model="form.code" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Libellé</label>
        <input v-model="form.libelle" required class="w-full border p-2 rounded" />
      </div>
      <div>
        <label>Description</label>
        <textarea v-model="form.description" class="w-full border p-2 rounded"></textarea>
      </div>
      <div>
        <label>Actif</label>
        <input v-model="form.actif" type="checkbox" class="ml-2" />
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
import { useClassificationStore } from '@/stores/classificationStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';

const store = useClassificationStore();
const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} classification`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Classifications', to: '/classifications' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];

const form = reactive({
  code: '',
  libelle: '',
  description: '',
  actif: true,
});

const submit = async () => {
  if (isEdit.value) {
    await store.updateClassification(route.params.id, form);
  } else {
    await store.createClassification(form);
  }
  router.push('/classifications');
};

onMounted(async () => {
  if (isEdit.value) {
    const response = await apiClient.get(`/classifications/${route.params.id}`);
    Object.assign(form, response.data);
  }
});
</script>
