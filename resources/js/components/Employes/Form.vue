<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <AppInput v-model="form.matricule" label="Matricule" :error="errors.matricule" required />
      <div class="grid grid-cols-3 gap-4">
        <AppInput v-model="form.num_lpm" label="N° LPM" :error="errors.num_lpm" />
        <AppInput v-model="form.num_cnaps" label="N° CNAPS" :error="errors.num_cnaps" />
        <AppInput v-model="form.visa_contrat" label="N° Visa Contrat" :error="errors.visa_contrat" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model="form.nom" label="Nom" :error="errors.nom" required />
        <AppInput v-model="form.prenom" label="Prénom" :error="errors.prenom" required />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model="form.date_naissance" label="Date de naissance" type="date" :error="errors.date_naissance" />
        <AppInput v-model="form.cin" label="CIN" :error="errors.cin" />
      </div>
      <AppSelect v-model="form.nationalite_id" label="Nationalité" placeholder="-- Sélectionner --"
        :options="paysList.map(p => ({ value: p.id, label: p.nom }))" :error="errors.nationalite_id" />
      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-on-surface-variant">Adresse</label>
        <textarea v-model="form.adresse" class="w-full rounded-xl border border-outline bg-surface-container px-4 py-3 text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model="form.telephone" label="Téléphone" :error="errors.telephone" />
        <AppInput v-model="form.email" label="Email" type="email" :error="errors.email" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppSelect v-model="form.banque_id" label="Banque" placeholder="-- Sélectionner --"
          :options="banques.map(b => ({ value: b.id, label: b.nom }))" :error="errors.banque_id" />
        <AppInput v-model="form.compte_bancaire" label="Compte bancaire" :error="errors.compte_bancaire" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <AppInput v-model="form.date_embauche" label="Date d'embauche" type="date" :error="errors.date_embauche" />
        <AppInput v-model.number="form.nbre_charges" label="Nombre de charges" type="number" min="0" step="1" :error="errors.nbre_charges" />
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
import { reactive, computed, onMounted } from 'vue';
import { useEmployeStore } from '@/stores/employeStore';
import { usePaysStore } from '@/stores/paysStore';
import { useBanqueStore } from '@/stores/banqueStore';
import apiClient from '@/services/api';
import { useRouter, useRoute } from 'vue-router';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';
import { useToasts } from '@/services/toast';

const store = useEmployeStore();
const paysStore = usePaysStore();
const banqueStore = useBanqueStore();
const router = useRouter();
const route = useRoute();
const { success } = useToasts();

const isEdit = computed(() => !!route.params.id);
const headerTitle = computed(() => `${isEdit.value ? 'Modifier' : 'Nouvel'} employé`);
const crumbs = [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Employés', to: '/employes' },
  { label: isEdit.value ? 'Modifier' : 'Nouveau' },
];
const paysList = computed(() => paysStore.pays);
const banques = computed(() => banqueStore.banques);

const form = reactive({
  matricule: '',
  num_lpm: '',
  num_cnaps: '',
  visa_contrat: '',
  nom: '',
  prenom: '',
  date_naissance: '',
  nationalite_id: null,
  adresse: '',
  telephone: '',
  email: '',
  cin: '',
  banque_id: null,
  compte_bancaire: '',
  date_embauche: '',
  nbre_charges: 0,
  actif: true,
});
const errors = reactive({});

const mapEmploye = (data) => {
  form.matricule = data.matricule || '';
  form.num_lpm = data.num_lpm || '';
  form.num_cnaps = data.num_cnaps || '';
  form.visa_contrat = data.visa_contrat || '';
  form.nom = data.nom || '';
  form.prenom = data.prenom || '';
  form.date_naissance = data.date_naissance ? String(data.date_naissance).slice(0, 10) : '';
  form.nationalite_id = data.nationalite_id ?? null;
  form.adresse = data.adresse || '';
  form.telephone = data.telephone || '';
  form.email = data.email || '';
  form.cin = data.cin || '';
  form.banque_id = data.banque_id ?? null;
  form.compte_bancaire = data.compte_bancaire || '';
  form.date_embauche = data.date_embauche ? String(data.date_embauche).slice(0, 10) : '';
  form.nbre_charges = Number(data.nbre_charges) || 0;
  form.actif = !!data.actif;
};

const submit = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);
  try {
    if (isEdit.value) {
      await store.updateEmploye(route.params.id, form);
      success('Employé mis à jour.');
    } else {
      await store.createEmploye(form);
      success('Employé créé.');
    }
    router.push('/employes');
  } catch (e) {
    if (e.response?.status === 422 && e.response.data.errors) {
      Object.assign(errors, e.response.data.errors);
    }
  }
};

onMounted(async () => {
  await Promise.all([paysStore.fetchPays(), banqueStore.fetchBanques()]);
  if (isEdit.value) {
    const res = await apiClient.get(`/employes/${route.params.id}`);
    mapEmploye(res.data);
  }
});
</script>
