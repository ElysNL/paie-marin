<template>
  <div class="max-w-lg">
    <Breadcrumb :items="crumbs" />
    <PageHeader :title="headerTitle" />
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label for="matricule" class="block mb-1 text-sm font-medium">Matricule</label>
        <input id="matricule" v-model="form.matricule" required class="w-full border p-2 rounded" />
        <p v-if="errors.matricule" class="text-sm text-red-600 mt-1">{{ errors.matricule[0] }}</p>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="nom" class="block mb-1 text-sm font-medium">Nom</label>
          <input id="nom" v-model="form.nom" required class="w-full border p-2 rounded" />
          <p v-if="errors.nom" class="text-sm text-red-600 mt-1">{{ errors.nom[0] }}</p>
        </div>
        <div>
          <label for="prenom" class="block mb-1 text-sm font-medium">Prénom</label>
          <input id="prenom" v-model="form.prenom" required class="w-full border p-2 rounded" />
          <p v-if="errors.prenom" class="text-sm text-red-600 mt-1">{{ errors.prenom[0] }}</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="date_naissance" class="block mb-1 text-sm font-medium">Date de naissance</label>
          <input id="date_naissance" v-model="form.date_naissance" type="date" class="w-full border p-2 rounded" />
        </div>
        <div>
          <label for="cin" class="block mb-1 text-sm font-medium">CIN</label>
          <input id="cin" v-model="form.cin" class="w-full border p-2 rounded" />
        </div>
      </div>
      <div>
        <label for="nationalite_id" class="block mb-1 text-sm font-medium">Nationalité</label>
        <select id="nationalite_id" v-model="form.nationalite_id" class="w-full border p-2 rounded">
          <option :value="null">-- Sélectionner --</option>
          <option v-for="pays in paysList" :key="pays.id" :value="pays.id">{{ pays.nom }}</option>
        </select>
      </div>
      <div>
        <label for="adresse" class="block mb-1 text-sm font-medium">Adresse</label>
        <textarea id="adresse" v-model="form.adresse" class="w-full border p-2 rounded"></textarea>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="telephone" class="block mb-1 text-sm font-medium">Téléphone</label>
          <input id="telephone" v-model="form.telephone" class="w-full border p-2 rounded" />
        </div>
        <div>
          <label for="email" class="block mb-1 text-sm font-medium">Email</label>
          <input id="email" v-model="form.email" type="email" class="w-full border p-2 rounded" />
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="banque_id" class="block mb-1 text-sm font-medium">Banque</label>
          <select id="banque_id" v-model="form.banque_id" class="w-full border p-2 rounded">
            <option :value="null">-- Sélectionner --</option>
            <option v-for="b in banques" :key="b.id" :value="b.id">{{ b.nom }}</option>
          </select>
        </div>
        <div>
          <label for="compte_bancaire" class="block mb-1 text-sm font-medium">Compte bancaire</label>
          <input id="compte_bancaire" v-model="form.compte_bancaire" class="w-full border p-2 rounded" />
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="date_embauche" class="block mb-1 text-sm font-medium">Date d'embauche</label>
          <input id="date_embauche" v-model="form.date_embauche" type="date" class="w-full border p-2 rounded" />
        </div>
        <div>
          <label for="nbre_charges" class="block mb-1 text-sm font-medium">Nombre de charges</label>
          <input id="nbre_charges" v-model.number="form.nbre_charges" type="number" min="0" step="1"
                 class="w-full border p-2 rounded" />
          <p v-if="errors.nbre_charges" class="text-sm text-red-600 mt-1">{{ errors.nbre_charges[0] }}</p>
        </div>
      </div>
      <div>
        <label for="actif" class="inline-flex items-center gap-2 text-sm font-medium">
          <input id="actif" v-model="form.actif" type="checkbox" /> Actif
        </label>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Enregistrer</button>
        <button type="button" @click="$router.back()" class="px-4 py-2 border rounded hover:bg-gray-50 transition">Annuler</button>
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