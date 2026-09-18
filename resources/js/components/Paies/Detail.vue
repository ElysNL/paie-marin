<template>
  <div>
    <Breadcrumb :items="crumbs" />

    <AppLoading v-if="store.loading && !paie" message="Chargement de la paie…" />

    <template v-else-if="paie">
      <PageHeader
        :title="paie.libelle"
        :subtitle="`${paie.num_paie} · ${paie.periode} · ${formatDate(paie.date_debut)} → ${formatDate(paie.date_fin)}`"
      >
        <template #action>
          <AppBadge :statut="paie.statut" />
          <template v-if="paie.statut === 'brouillon'">
            <AppButton variant="secondary" @click="goEdit">Modifier</AppButton>
          </template>
          <AppButton v-if="paie.statut === 'calcule'" :loading="actionLoading" @click="runValider">
            Valider la paie
          </AppButton>
          <AppButton v-if="paie.statut === 'valide'" variant="secondary" :loading="actionLoading" @click="runCloturer">
            Clôturer la paie
          </AppButton>
        </template>
      </PageHeader>

      <template v-if="paie.statut === 'brouillon'">
        <section class="mb-6">
          <h2 class="mb-4 text-lg font-semibold">Calculer les bulletins</h2>

          <div v-if="calculEnCours" class="mb-6 rounded-xl border border-primary/20 bg-primary/5 p-4">
            <div class="mb-3 flex items-center gap-3">
              <svg class="h-5 w-5 animate-spin text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <span class="font-medium text-primary">{{ calculMessage }}</span>
            </div>
            <div class="h-2 w-full overflow-hidden rounded-full bg-primary/20">
              <div class="h-full rounded-full bg-primary transition-all duration-500"
                   :style="{ width: progressPercent + '%' }"></div>
            </div>
          </div>

          <div v-if="calculErreur" class="mb-6 rounded-xl border border-error/20 bg-error/5 p-4 text-error">
            {{ calculErreur }}
          </div>

          <AppLoading v-if="naviresLoading" message="Chargement des navires éligibles…" />

          <AppTable v-else-if="naviresEligibles.length > 0 && !calculEnCours" :columns="naviresColumns" :rows="naviresEligibles" row-key="navire_id">
            <template #cell(navire_nom)="{ row }" class="font-medium">{{ row.navire_nom }}</template>
            <template #cell(nb_marins)="{ row }" class="text-center">{{ row.nb_marins }} marin(s)</template>
            <template #cell(action)="{ row }">
              <AppButton :loading="calculEnCours" @click="calculerNavire(row.navire_id)">Calculer</AppButton>
            </template>
            <template #cell(total)="{}">
              <span class="font-medium">{{ totalMarins }} marin(s)</span>
            </template>
          </AppTable>

          <div v-if="naviresEligibles.length > 0 && !calculEnCours" class="mt-3 flex justify-end">
            <AppButton variant="secondary" :loading="calculEnCours" @click="calculerTous()">Tout calculer</AppButton>
          </div>

          <AppEmpty v-if="!naviresEligibles.length && !calculEnCours" message="Aucune affectation active ne couvre cette période. Ajoutez des affectations avant de calculer." />
        </section>
      </template>

      <template v-if="paie.statut === 'brouillon'">
        <section class="mb-6">
          <h2 class="mb-2 text-lg font-semibold">Marins éligibles pour cette période</h2>
          <button @click="loadEligibles" class="mb-3 text-sm text-primary hover:underline">
            {{ eligibles.length ? 'Rafraîchir' : 'Afficher les marins éligibles' }}
          </button>

          <AppLoading v-if="eligiblesLoading" />

          <template v-else-if="eligibles.length > 0">
            <AppTable :columns="eligiblesColumns" :rows="eligibles">
              <template #cell(employe)="{ row }">{{ row.employe?.nom }} {{ row.employe?.prenom }}</template>
              <template #cell(navire)="{ row }">{{ row.navire?.nom }}</template>
              <template #cell(fonction)="{ row }">{{ row.fonction?.libelle }}</template>
              <template #cell(taux_journalier)="{ row }">{{ formatMoney(row.taux_journalier, row.contrat_armateur?.devise?.code) }}</template>
              <template #cell(periode)="{ row }">{{ formatDate(row.date_embt) }} → {{ formatDate(row.date_debt) }}</template>
            </AppTable>
            <p class="mt-2 text-sm text-on-surface-variant/70">
              {{ eligibles.length }} marin(s) seront inclus dans le calcul.
            </p>
          </template>
        </section>
      </template>

      <section>
        <h2 class="mb-2 text-lg font-semibold">Bulletins ({{ paie.bulletins?.length || 0 }})</h2>
        <div class="mb-3 flex gap-2">
          <button @click="$router.push('/bulletins?paie_id=' + paie.id)" class="text-sm text-primary hover:underline">
            Tout voir dans la liste des bulletins
          </button>
        </div>

        <AppTable v-if="paie.bulletins?.length" :columns="bulletinsColumns" :rows="paie.bulletins">
          <template #cell(employe)="{ row }">{{ row.employe?.nom }} {{ row.employe?.prenom }}</template>
          <template #cell(navire)="{ row }">{{ row.navire?.nom }}</template>
          <template #cell(total_jours)="{ row }">{{ row.total_jours }}</template>
          <template #cell(total_brut)="{ row }" class="text-right">{{ formatMoney(row.total_brut) }}</template>
          <template #cell(total_retenues)="{ row }" class="text-right">{{ formatMoney(row.total_retenues) }}</template>
          <template #cell(net_a_payer)="{ row }" class="text-right font-semibold">{{ formatMoney(row.net_a_payer) }}</template>
          <template #cell(actions)="{ row }">
            <button @click="$router.push('/bulletins/' + row.id)" class="text-sm text-secondary hover:underline">
              Voir le bulletin
            </button>
          </template>
        </AppTable>
        <AppEmpty v-else message="Aucun bulletin pour le moment. Lancez le calcul pour générer les bulletins." />
      </section>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { usePaieStore } from '@/stores/paieStore';
import { useToasts } from '@/services/toast';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppBadge from '@/components/AppBadge.vue';
import AppButton from '@/components/AppButton.vue';
import AppTable from '@/components/AppTable.vue';
import AppCard from '@/components/AppCard.vue';
import AppLoading from '@/components/AppLoading.vue';
import AppEmpty from '@/components/AppEmpty.vue';
import { formatMoney, formatDate } from '@/utils/format';

const store = usePaieStore();
const router = useRouter();
const route = useRoute();
const { success, error } = useToasts();

const paie = computed(() => store.currentPaie);
const eligibles = computed(() => store.eligibles);
const naviresEligibles = computed(() => store.naviresEligibles);

const totalMarins = computed(() =>
  naviresEligibles.value.reduce((sum, n) => sum + n.nb_marins, 0)
);

const crumbs = computed(() => [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Paies', to: '/paies' },
  { label: paie.value?.num_paie || 'Paie' },
]);

const actionLoading = ref(false);
const eligiblesLoading = ref(false);
const naviresLoading = ref(false);
const calculEnCours = ref(false);
const calculMessage = ref('');
const calculErreur = ref('');
const progressPercent = ref(0);

let pollTimer = null;

const naviresColumns = [
  { key: 'navire_nom', label: 'Navire' },
  { key: 'nb_marins', label: 'Marins éligibles', align: 'center' },
  { key: 'action', label: 'Action', align: 'center' },
];

const eligiblesColumns = [
  { key: 'employe', label: 'Employé' },
  { key: 'navire', label: 'Navire' },
  { key: 'fonction', label: 'Fonction' },
  { key: 'taux_journalier', label: 'Taux journalier' },
  { key: 'periode', label: 'Période' },
];

const bulletinsColumns = [
  { key: 'employe', label: 'Employé' },
  { key: 'navire', label: 'Navire' },
  { key: 'total_jours', label: 'Jours' },
  { key: 'total_brut', label: 'Brut', align: 'right' },
  { key: 'total_retenues', label: 'Retenues', align: 'right' },
  { key: 'net_a_payer', label: 'Net à payer', align: 'right' },
  { key: 'actions', label: 'Actions', align: 'right' },
];

const loadEligibles = async () => {
  eligiblesLoading.value = true;
  try {
    await store.fetchEligibles(route.params.id);
  } finally {
    eligiblesLoading.value = false;
  }
};

const loadNaviresEligibles = async () => {
  naviresLoading.value = true;
  try {
    await store.fetchNaviresEligibles(route.params.id);
  } finally {
    naviresLoading.value = false;
  }
};

const goEdit = () => router.push(`/paies/${route.params.id}/edit`);

const startPolling = () => {
  progressPercent.value = 20;
  pollTimer = setInterval(async () => {
    try {
      const data = await store.fetchStatutCalcul(route.params.id);
      if (data.statut_calcul === 'termine') {
        clearInterval(pollTimer);
        pollTimer = null;
        calculEnCours.value = false;
        progressPercent.value = 100;
        const nb = data.resultat_calcul?.nb_bulletins ?? 0;
        calculMessage.value = `${nb} bulletin(s) calculé(s).`;
        success(`Calcul terminé — ${nb} bulletin(s).`);
        await store.fetchPaie(route.params.id);
        await loadNaviresEligibles();
      } else if (data.statut_calcul === 'erreur') {
        clearInterval(pollTimer);
        pollTimer = null;
        calculEnCours.value = false;
        calculErreur.value = data.resultat_calcul?.erreur || 'Erreur lors du calcul.';
        error(calculErreur.value);
      } else {
        progressPercent.value = Math.min(progressPercent.value + 10, 90);
      }
    } catch {
      // continuer à poller
    }
  }, 2000);
};

const calculerNavire = async (navireId) => {
  calculEnCours.value = true;
  calculErreur.value = '';
  calculMessage.value = 'Lancement du calcul…';
  progressPercent.value = 10;
  try {
    await store.calculer(route.params.id, { navire_id: navireId });
    calculMessage.value = 'Calcul en cours…';
    startPolling();
  } catch (e) {
    calculEnCours.value = false;
    if (e.response?.data?.error) {
      calculErreur.value = e.response.data.error;
      error(e.response.data.error);
    }
  }
};

const calculerTous = async () => {
  calculEnCours.value = true;
  calculErreur.value = '';
  calculMessage.value = 'Lancement du calcul global…';
  progressPercent.value = 10;
  try {
    await store.calculer(route.params.id);
    calculMessage.value = 'Calcul en cours…';
    startPolling();
  } catch (e) {
    calculEnCours.value = false;
    if (e.response?.data?.error) {
      calculErreur.value = e.response.data.error;
      error(e.response.data.error);
    }
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
  if (paie.value?.statut === 'brouillon') {
    loadEligibles();
    loadNaviresEligibles();
    if (paie.value?.statut_calcul === 'en_cours') {
      calculEnCours.value = true;
      calculMessage.value = 'Calcul en cours…';
      startPolling();
    }
  }
});

onUnmounted(() => {
  if (pollTimer) clearInterval(pollTimer);
});
</script>
