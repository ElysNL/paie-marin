<template>
  <div class="max-w-xl">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />

    <div v-if="isEdit && locked" class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
      Cette paie est déjà traitée : seuls le libellé et la période peuvent être modifiés.
    </div>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label for="num_paie" class="block mb-1 text-sm font-medium">N° de paie</label>
        <input id="num_paie" v-model="form.num_paie" required
               :disabled="isEdit && locked"
               class="w-full border p-2 rounded disabled:bg-gray-100" />
        <p v-if="errors.num_paie" class="text-sm text-red-600 mt-1">{{ errors.num_paie[0] }}</p>
      </div>
      <div>
        <label for="libelle" class="block mb-1 text-sm font-medium">Libellé</label>
        <input id="libelle" v-model="form.libelle" required class="w-full border p-2 rounded" />
        <p v-if="errors.libelle" class="text-sm text-red-600 mt-1">{{ errors.libelle[0] }}</p>
      </div>
      <div>
        <label for="periode" class="block mb-1 text-sm font-medium">Période</label>
        <input id="periode" v-model="form.periode" placeholder="Ex : Août 2026"
               required class="w-full border p-2 rounded" />
        <p v-if="errors.periode" class="text-sm text-red-600 mt-1">{{ errors.periode[0] }}</p>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="date_debut" class="block mb-1 text-sm font-medium">Date de début</label>
          <input id="date_debut" v-model="form.date_debut" type="date" required class="w-full border p-2 rounded" />
          <p v-if="errors.date_debut" class="text-sm text-red-600 mt-1">{{ errors.date_debut[0] }}</p>
        </div>
        <div>
          <label for="date_fin" class="block mb-1 text-sm font-medium">Date de fin</label>
          <input id="date_fin" v-model="form.date_fin" type="date" required class="w-full border p-2 rounded" />
          <p v-if="errors.date_fin" class="text-sm text-red-600 mt-1">{{ errors.date_fin[0] }}</p>
        </div>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
          Enregistrer
        </button>
        <button type="button" @click="$router.back()" class="px-4 py-2 border rounded hover:bg-gray-50 transition">
          Annuler
        </button>
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