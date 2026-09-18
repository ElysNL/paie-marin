<template>
  <div class="max-w-xl">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />

    <div v-if="isEdit && locked" class="mb-4 rounded-xl border border-secondary-light/30 bg-secondary-light/5 p-3 text-sm text-secondary">
      Cette paie est déjà traitée : seuls le libellé et la période peuvent être modifiés.
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <AppInput v-model="form.num_paie" label="N° de paie" :error="errors.num_paie"
        :disabled="isEdit && locked" required />
      <AppInput v-model="form.libelle" label="Libellé" :error="errors.libelle" required />
      <AppInput v-model="form.periode" label="Période" placeholder="Ex : Août 2026" :error="errors.periode" required />
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model="form.date_debut" label="Date de début" type="date" :error="errors.date_debut" required />
        <AppInput v-model="form.date_fin" label="Date de fin" type="date" :error="errors.date_fin" required />
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
import { usePaieStore } from '@/stores/paieStore';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppButton from '@/components/AppButton.vue';
import { useToasts } from '@/services/toast';

const store = usePaieStore();
const router = useRouter();
const route = useRoute();
const { success } = useToasts();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} paie`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Paies', to: '/paies' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];
const locked = computed(() => ['calcule', 'valide', 'cloture'].includes(currentStatut.value));
const currentStatut = ref('brouillon');

const form = reactive({
  num_paie: '',
  libelle: '',
  periode: '',
  date_debut: '',
  date_fin: '',
});
const errors = reactive({});

const mapPaie = (data) => {
  form.num_paie = data.num_paie || '';
  form.libelle = data.libelle || '';
  form.periode = data.periode || '';
  form.date_debut = data.date_debut ? String(data.date_debut).slice(0, 10) : '';
  form.date_fin = data.date_fin ? String(data.date_fin).slice(0, 10) : '';
};

const submit = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);
  try {
    if (isEdit.value) {
      await store.updatePaie(route.params.id, form);
      success('Paie mise à jour.');
    } else {
      await store.createPaie(form);
      success('Paie créée.');
    }
    router.push('/paies');
  } catch (e) {
    if (e.response?.status === 422 && e.response.data.errors) {
      Object.assign(errors, e.response.data.errors);
    }
  }
};

onMounted(async () => {
  if (isEdit.value) {
    const paie = await store.fetchPaie(route.params.id);
    currentStatut.value = paie.statut;
    mapPaie(paie);
  }
});
</script>
