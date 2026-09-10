<template>
  <div>
    <Breadcrumb :items="crumbs" />

    <div v-if="store.loading && !paie" class="py-8 text-center text-gray-500">Chargement…</div>

    <template v-else-if="paie">
      <PageHeader
        :title="paie.libelle"
        :subtitle="`${paie.num_paie} · ${paie.periode} · ${formatDate(paie.date_debut)} → ${formatDate(paie.date_fin)}`"
      >
        <template #action>
          <span class="px-2 py-1 rounded text-xs font-semibold"
                :class="statutBadge[paie.statut] || 'bg-gray-200 text-gray-700'">
            {{ statutLibelle[paie.statut] || paie.statut }}
          </span>
          <template v-if="paie.statut === 'brouillon'">
            <button @click="goEdit" class="px-3 py-2 border rounded hover:bg-gray-50 transition">Modifier</button>
            <button @click="runCalculer" :disabled="calculerLoading"
                    class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-40">
              {{ calculerLoading ? 'Calcul…' : 'Calculer les bulletins' }}
            </button>
          </template>
          <button v-if="paie.statut === 'calcule'" @click="runValider" :disabled="actionLoading"
                  class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition disabled:opacity-40">
            Valider la paie
          </button>
          <button v-if="paie.statut === 'valide'" @click="runCloturer" :disabled="actionLoading"
                  class="px-3 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition disabled:opacity-40">
            Clôturer la paie
          </button>
        </template>
      </PageHeader>

      <template v-if="paie.statut === 'brouillon'">
        <section class="mb-6">
          <h2 class="text-lg font-semibold mb-2">Marins éligibles pour cette période</h2>
          <button @click="loadEligibles" class="mb-3 text-sm text-blue-600 hover:underline">
            {{ eligibles.length ? 'Rafraîchir' : 'Afficher les marins éligibles' }}
          </button>

          <div v-if="eligiblesLoading" class="text-gray-500 text-sm">Chargement…</div>
          <div v-else-if="eligibles.length === 0" class="text-gray-500 text-sm">
            Aucune affectation active ne couvre cette période. Ajoutez des affectations avant de calculer.
          </div>
          <div v-else class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
              <thead>
                <tr class="bg-gray-100 text-left">
                  <th class="p-2 border">Employé</th>
                  <th class="p-2 border">Navire</th>
                  <th class="p-2 border">Fonction</th>
                  <th class="p-2 border">Taux journalier</th>
                  <th class="p-2 border">Période</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="a in eligibles" :key="a.id" class="hover:bg-gray-50">
                  <td class="p-2 border">{{ a.employe?.nom }} {{ a.employe?.prenom }}</td>
                  <td class="p-2 border">{{ a.navire?.nom }}</td>
                  <td class="p-2 border">{{ a.fonction?.nom }}</td>
                  <td class="p-2 border">{{ formatMoney(a.taux_journalier, a.contrat_armateur?.devise?.code) }}</td>
                  <td class="p-2 border">{{ formatDate(a.date_embt) }} → {{ formatDate(a.date_debt) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <p class="text-gray-500 text-sm mt-2">
            {{ eligibles.length }} marin(s) seront inclus dans le calcul.
          </p>
        </section>
      </template>

      <section>
        <h2 class="text-lg font-semibold mb-2">Bulletins ({{ paie.bulletins?.length || 0 }})</h2>
        <div class="flex gap-2 mb-3">
          <button @click="$router.push('/bulletins?paie_id=' + paie.id)" class="text-sm text-blue-600 hover:underline">
            Tout voir dans la liste des bulletins
          </button>
        </div>

        <div v-if="paie.bulletins?.length" class="overflow-x-auto">
          <table class="w-full border-collapse text-sm">
            <thead>
              <tr class="bg-gray-100 text-left">
                <th class="p-2 border">Employé</th>
                <th class="p-2 border">Navire</th>
                <th class="p-2 border">Jours</th>
                <th class="p-2 border">Brut</th>
                <th class="p-2 border">Retenues</th>
                <th class="p-2 border">Net à payer</th>
                <th class="p-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="b in paie.bulletins" :key="b.id" class="hover:bg-gray-50">
                <td class="p-2 border">{{ b.employe?.nom }} {{ b.employe?.prenom }}</td>
                <td class="p-2 border">{{ b.navire?.nom }}</td>
                <td class="p-2 border">{{ b.total_jours }}</td>
                <td class="p-2 border text-right">{{ formatMoney(b.total_brut) }}</td>
                <td class="p-2 border text-right">{{ formatMoney(b.total_retenues) }}</td>
                <td class="p-2 border text-right font-semibold">{{ formatMoney(b.net_a_payer) }}</td>
                <td class="p-2 border">
                  <button @click="$router.push('/bulletins/' + b.id)" class="text-green-600 hover:underline">
                    Voir le bulletin
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-else class="text-gray-500 text-sm">
          Aucun bulletin pour le moment. Lancez le calcul pour générer les bulletins.
        </p>
      </section>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { usePaieStore } from '@/stores/paieStore';
import { useToasts } from '@/services/toast';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import { formatMoney, formatDate, statutBadge, statutLibelle } from '@/utils/format';

const store = usePaieStore();
const router = useRouter();
const route = useRoute();
const { success, error } = useToasts();

const paie = computed(() => store.currentPaie);
const eligibles = computed(() => store.eligibles);

const crumbs = computed(() => [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Paies', to: '/paies' },
  { label: paie.value?.num_paie || 'Paie' },
]);

const calculerLoading = ref(false);
const actionLoading = ref(false);
const eligiblesLoading = ref(false);

const loadEligibles = async () => {
  eligiblesLoading.value = true;
  try {
    await store.fetchEligibles(route.params.id);
  } finally {
    eligiblesLoading.value = false;
  }
};

const goEdit = () => router.push(`/paies/${route.params.id}/edit`);

const runCalculer = async () => {
  if (!confirm('Calculer les bulletins de tous les marins éligibles ? Les anciens bulletins seront recalculés.')) return;
  calculerLoading.value = true;
  try {
    const result = await store.calculer(route.params.id);
    success(result.message || 'Calcul terminé.');
    await store.fetchPaie(route.params.id);
  } catch (e) {
    if (e.response?.data?.error) error(e.response.data.error);
  } finally {
    calculerLoading.value = false;
  }
};

const runValider = async () => {
  if (!confirm('Valider cette paie ? Elle ne pourra plus être modifiée.')) return;
  actionLoading.value = true;
  try {
    const result = await store.valider(route.params.id);
    success(result.message || 'Paie validée.');
    await store.fetchPaie(route.params.id);
  } finally {
    actionLoading.value = false;
  }
};

const runCloturer = async () => {
  if (!confirm('Clôturer cette paie ?')) return;
  actionLoading.value = true;
  try {
    const result = await store.cloturer(route.params.id);
    success(result.message || 'Paie clôturée.');
    await store.fetchPaie(route.params.id);
  } finally {
    actionLoading.value = false;
  }
};

onMounted(async () => {
  await store.fetchPaie(route.params.id);
  if (paie.value?.statut === 'brouillon') loadEligibles();
});
</script>