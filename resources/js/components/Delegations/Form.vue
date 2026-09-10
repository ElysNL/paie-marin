<template>
  <div class="max-w-xl">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label for="employe_id" class="block mb-1 text-sm font-medium">Employé</label>
        <select id="employe_id" v-model="form.employe_id" required class="w-full border p-2 rounded">
          <option :value="null" disabled>-- Sélectionner --</option>
          <option v-for="e in employes" :key="e.id" :value="e.id">{{ e.nom }} {{ e.prenom }}</option>
        </select>
        <p v-if="errors.employe_id" class="text-sm text-red-600 mt-1">{{ errors.employe_id[0] }}</p>
      </div>
      <div>
        <label for="beneficiaire" class="block mb-1 text-sm font-medium">Bénéficiaire</label>
        <input id="beneficiaire" v-model="form.beneficiaire" required class="w-full border p-2 rounded" />
        <p v-if="errors.beneficiaire" class="text-sm text-red-600 mt-1">{{ errors.beneficiaire[0] }}</p>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="montant" class="block mb-1 text-sm font-medium">Montant</label>
          <input id="montant" v-model.number="form.montant" type="number" min="0" step="0.01" required
                 class="w-full border p-2 rounded" />
          <p v-if="errors.montant" class="text-sm text-red-600 mt-1">{{ errors.montant[0] }}</p>
        </div>
        <div>
          <label for="devise_id" class="block mb-1 text-sm font-medium">Devise</label>
          <select id="devise_id" v-model="form.devise_id" class="w-full border p-2 rounded">
            <option :value="null">-- Sélectionner --</option>
            <option v-for="d in devises" :key="d.id" :value="d.id">{{ d.code }}</option>
          </select>
          <p v-if="errors.devise_id" class="text-sm text-red-600 mt-1">{{ errors.devise_id[0] }}</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="date_debut" class="block mb-1 text-sm font-medium">Date de début</label>
          <input id="date_debut" v-model="form.date_debut" type="date" required class="w-full border p-2 rounded" />
          <p v-if="errors.date_debut" class="text-sm text-red-600 mt-1">{{ errors.date_debut[0] }}</p>
        </div>
        <div>
          <label for="date_fin" class="block mb-1 text-sm font-medium">Date de fin</label>
          <input id="date_fin" v-model="form.date_fin" type="date" class="w-full border p-2 rounded" />
          <p v-if="errors.date_fin" class="text-sm text-red-600 mt-1">{{ errors.date_fin[0] }}</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="frequence" class="block mb-1 text-sm font-medium">Fréquence</label>
          <select id="frequence" v-model="form.frequence" class="w-full border p-2 rounded">
            <option value="mensuel">Mensuel</option>
            <option value="ponctuel">Ponctuel</option>
          </select>
        </div>
        <div>
          <label for="statut" class="block mb-1 text-sm font-medium">Statut</label>
          <select id="statut" v-model="form.statut" class="w-full border p-2 rounded">
            <option value="actif">Actif</option>
            <option value="termine">Terminé</option>
            <option value="annule">Annulé</option>
          </select>
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
import { reactive, computed, onMounted } from 'vue';
import { useDelegationStore } from '@/stores/delegationStore';
import { useEmployeStore } from '@/stores/employeStore';
import { useDeviseStore } from '@/stores/deviseStore';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import { useToasts } from '@/services/toast';
import apiClient from '@/services/api';

const store = useDelegationStore();
const employeStore = useEmployeStore();
const deviseStore = useDeviseStore();
const router = useRouter();
const route = useRoute();
const { success } = useToasts();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvelle'} délégation`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Délégations', to: '/delegations' },
  { label: isEdit.value ? 'Modifier' : 'Nouvelle' },
];
const employes = computed(() => employeStore.employes);
const devises = computed(() => deviseStore.devises);

const form = reactive({
  employe_id: null,
  beneficiaire: '',
  montant: null,
  devise_id: null,
  date_debut: '',
  date_fin: '',
  frequence: 'mensuel',
  statut: 'actif',
});
const errors = reactive({});

const submit = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);
  try {
    if (isEdit.value) {
      await store.updateDelegation(route.params.id, form);
      success('Délégation mise à jour.');
    } else {
      await store.createDelegation(form);
      success('Délégation créée.');
    }
    router.push('/delegations');
  } catch (e) {
    if (e.response?.status === 422 && e.response.data.errors) {
      Object.assign(errors, e.response.data.errors);
    }
  }
};

onMounted(async () => {
  await Promise.all([employeStore.fetchEmployes(), deviseStore.fetchDevises()]);
  if (isEdit.value) {
    const res = await apiClient.get(`/delegations/${route.params.id}`);
    const data = res.data;
    form.employe_id = data.employe_id;
    form.beneficiaire = data.beneficiaire || '';
    form.montant = Number(data.montant);
    form.devise_id = data.devise_id;
    form.date_debut = data.date_debut ? String(data.date_debut).slice(0, 10) : '';
    form.date_fin = data.date_fin ? String(data.date_fin).slice(0, 10) : '';
    form.frequence = data.frequence || 'mensuel';
    form.statut = data.statut || 'actif';
  }
});
</script>