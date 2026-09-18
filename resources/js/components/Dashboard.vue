<template>
  <div>
    <Breadcrumb :items="[{ label: 'Tableau de bord' }]" />
    <PageHeader title="Tableau de bord" subtitle="Vue d'ensemble de l'activité (lecture seule)" />

    <div v-if="loading" class="py-8 text-center text-gray-500">Chargement…</div>

    <div v-else-if="error" class="rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
      {{ error }}
    </div>

    <template v-else>
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard label="Employés actifs" :value="String(data.employes_actifs)" icon="users" to="/employes" />
        <StatCard label="Navires" :value="String(data.navires)" icon="ship" to="/navires" />
        <StatCard label="Affectations actives" :value="String(data.affectations_actives)" icon="clipboard" to="/affectations" />
        <StatCard label="Bulletins émis" :value="String(data.bulletins.total)" icon="file" to="/bulletins" />
      </div>

      <div class="mt-4 grid gap-4 lg:grid-cols-3">
        <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
          <h2 class="mb-4 text-lg font-semibold">Paies par statut</h2>
          <div v-if="Object.keys(paiesParStatut).length" class="space-y-3">
            <div
              v-for="(count, statut) in paiesParStatut"
              :key="statut"
              class="flex items-center justify-between rounded border border-gray-100 px-3 py-2 text-sm"
            >
              <span class="flex items-center gap-2">
                <span class="px-2 py-1 rounded text-xs font-semibold" :class="statutBadge[statut] || 'bg-gray-200 text-gray-700'">
                  {{ statutLibelle[statut] || statut }}
                </span>
                <span class="text-gray-500">{{ paiesParStatutLibelle[statut] }}</span>
              </span>
              <span class="text-lg font-semibold">{{ count }}</span>
            </div>
          </div>
          <p v-else class="text-sm text-gray-500">Aucune paie enregistrée.</p>

          <div class="mt-5 flex items-center justify-between rounded-lg border border-blue-100 bg-blue-50 p-4 text-sm">
            <div>
              <p class="font-semibold text-blue-900">Net à payer total</p>
              <p class="text-xs text-blue-700">{{ data.bulletins.total }} bulletin(s)</p>
            </div>
            <p class="text-lg font-bold text-blue-900">{{ formatMoney(data.bulletins.net_a_payer) }}</p>
          </div>
          <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 p-4 text-sm">
            <div>
              <p class="font-semibold text-gray-800">Coût total employeur</p>
              <p class="text-xs text-gray-500">Somme des bulletins</p>
            </div>
            <p class="text-lg font-bold text-gray-800">{{ formatMoney(data.bulletins.cout_total_employeur) }}</p>
          </div>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
          <h2 class="mb-4 text-lg font-semibold">Actions rapides</h2>
          <ul class="space-y-2">
            <li>
              <router-link to="/paies/create" class="flex items-center gap-2 text-blue-600 hover:underline">
                → Créer une paie
              </router-link>
            </li>
            <li>
              <router-link to="/paies" class="flex items-center gap-2 text-blue-600 hover:underline">
                → Voir les paies
              </router-link>
            </li>
            <li>
              <router-link to="/employes" class="flex items-center gap-2 text-blue-600 hover:underline">
                → Gérer les employés
              </router-link>
            </li>
            <li>
              <router-link to="/bulletins" class="flex items-center gap-2 text-blue-600 hover:underline">
                → Consulter les bulletins
              </router-link>
            </li>
          </ul>

          <h2 class="mb-3 mt-8 text-lg font-semibold">Dernières paies</h2>
          <div v-if="data.dernieres_paies.length" class="space-y-2">
            <router-link
              v-for="paie in data.dernieres_paies"
              :key="paie.id"
              :to="`/paies/${paie.id}`"
              class="block rounded border border-gray-100 px-3 py-2 text-sm transition hover:border-blue-200 hover:bg-blue-50"
            >
              <div class="flex items-center justify-between gap-2">
                <span class="truncate font-medium text-gray-800">{{ paie.num_paie }} · {{ paie.libelle }}</span>
                <span class="px-2 py-0.5 rounded text-xs font-semibold shrink-0" :class="statutBadge[paie.statut] || 'bg-gray-200 text-gray-700'">
                  {{ statutLibelle[paie.statut] || paie.statut }}
                </span>
              </div>
              <span class="text-xs text-gray-500">{{ paie.periode }} · {{ paie.bulletins_count ?? 0 }} bulletin(s)</span>
            </router-link>
          </div>
          <p v-else class="text-sm text-gray-500">Aucune paie pour le moment.</p>
        </section>
      </div>
    </template>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import StatCard from '@/components/StatCard.vue';
import { formatMoney, statutBadge, statutLibelle } from '@/utils/format';
import apiClient from '@/services/api';

const data = ref({
  employes_actifs: 0,
  affectations_actives: 0,
  navires: 0,
  paies_par_statut: {},
  bulletins: { total: 0, net_a_payer: 0, cout_total_employeur: 0 },
  dernieres_paies: [],
});
const loading = ref(true);
const error = ref('');

const paiesParStatut = computed(() => {
  const entries = Object.entries(data.value.paies_par_statut || {});
  const order = ['brouillon', 'calcule', 'valide', 'cloture'];
  return Object.fromEntries(
    entries.sort(([a], [b]) => order.indexOf(a) - order.indexOf(b)),
  );
});

const paiesParStatutLibelle = {
  brouillon: 'en cours de saisie',
  calcule: 'calculées, à valider',
  valide: 'validées, à clôturer',
  cloture: 'clôturées',
};

const fetchDashboard = async () => {
  loading.value = true;
  error.value = '';
  try {
    const response = await apiClient.get('/dashboard');
    data.value = response.data.data;
  } catch (e) {
    error.value = e.response?.data?.message || 'Impossible de charger le tableau de bord.';
  } finally {
    loading.value = false;
  }
};

onMounted(fetchDashboard);
</script>