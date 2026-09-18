<template>
  <div>
    <Breadcrumb :items="crumbs" />

    <AppLoading v-if="store.loading && !bulletin" message="Chargement du bulletin…" />

    <template v-else-if="bulletin">
      <PageHeader :title="`Bulletin — ${bulletin.employe?.nom} ${bulletin.employe?.prenom}`">
        <template #action>
          <AppButton variant="danger" @click="downloadPdf">Télécharger PDF</AppButton>
          <AppButton variant="secondary" @click="downloadExcel">Télécharger Excel</AppButton>
        </template>
      </PageHeader>

      <AppCard class="mb-6">
        <p class="text-sm text-on-surface-variant">
          {{ bulletin.paie?.libelle }} · {{ bulletin.paie?.periode }} ·
          Navire : {{ bulletin.navire?.nom }} ·
          {{ formatDate(bulletin.paie?.date_debut) }} → {{ formatDate(bulletin.paie?.date_fin) }}
        </p>
        <p v-if="bulletin.affectation?.fonction" class="mt-1 text-sm text-on-surface-variant">
          Fonction : {{ bulletin.affectation.fonction.libelle }}
        </p>
        <p v-if="bulletin.affectation?.contrat_armateur?.devise" class="mt-1 text-sm text-on-surface-variant">
          Devise du contrat : {{ bulletin.affectation.contrat_armateur.devise.code }}
        </p>
      </AppCard>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
          <AppCard v-if="bulletin.jours?.length">
            <h2 class="mb-2 text-lg font-semibold">Jours de travail</h2>
            <AppTable :columns="joursColumns" :rows="bulletin.jours">
              <template #cell(date)="{ row }">{{ formatDate(row.date) }}</template>
              <template #cell(type_jour)="{ row }">{{ row.type_jour }}</template>
              <template #cell(nombre)="{ row }">{{ row.nombre }}</template>
              <template #cell(taux)="{ row }" class="text-right">{{ formatMoney(row.taux) }}</template>
            </AppTable>
          </AppCard>

          <AppCard>
            <h2 class="mb-2 text-lg font-semibold">Brut et retenues</h2>
            <AppTable :columns="elementsColumns" :rows="bulletin.elements">
              <template #cell(elem_paie)="{ row }">{{ row.elem_paie?.libelle }}</template>
              <template #cell(code)="{ row }">{{ row.elem_paie?.code }}</template>
              <template #cell(type)="{ row }">{{ row.elem_paie?.type }}</template>
              <template #cell(description)="{ row }">{{ row.description || '—' }}</template>
              <template #cell(montant)="{ row }" class="text-right">{{ formatMoney(row.montant) }}</template>
            </AppTable>
          </AppCard>

          <AppCard v-if="bulletin.cotisations?.length">
            <h2 class="mb-2 text-lg font-semibold">Cotisations</h2>
            <AppTable :columns="cotisationsColumns" :rows="bulletin.cotisations">
              <template #cell(cotisation)="{ row }">{{ row.cotisation?.libelle }}</template>
              <template #cell(montant_salarial)="{ row }" class="text-right">{{ formatMoney(row.montant_salarial) }}</template>
              <template #cell(montant_patronal)="{ row }" class="text-right">{{ formatMoney(row.montant_patronal) }}</template>
            </AppTable>
          </AppCard>

          <AppCard v-if="bulletin.delegations?.length">
            <h2 class="mb-2 text-lg font-semibold">Délégations</h2>
            <AppTable :columns="delegationsColumns" :rows="bulletin.delegations">
              <template #cell(beneficiaire)="{ row }">{{ row.delegation?.beneficiaire }}</template>
              <template #cell(montant)="{ row }" class="text-right">{{ formatMoney(row.montant) }}</template>
            </AppTable>
          </AppCard>

          <AppCard v-if="bulletin.remboursementsAvances?.length">
            <h2 class="mb-2 text-lg font-semibold">Avances remboursées</h2>
            <AppTable :columns="avancesColumns" :rows="bulletin.remboursementsAvances">
              <template #cell(date_avance)="{ row }">{{ formatDate(row.avance?.date_avance) }}</template>
              <template #cell(motif)="{ row }">{{ row.avance?.motif || '—' }}</template>
              <template #cell(montant)="{ row }" class="text-right">{{ formatMoney(row.montant) }}</template>
            </AppTable>
          </AppCard>
        </div>

        <aside>
          <AppCard>
            <h2 class="mb-3 text-base font-semibold">Synthèse</h2>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between"><span>Total jours</span><span>{{ bulletin.total_jours }}</span></div>
              <div class="flex justify-between"><span>Total gains</span><span>{{ formatMoney(bulletin.total_gains) }}</span></div>
              <div class="flex justify-between border-t border-outline-variant pt-2 font-semibold">
                <span>BRUT</span><span>{{ formatMoney(bulletin.total_brut) }}</span>
              </div>
              <div class="flex justify-between"><span>Cotisations salariales</span>
                <span>-{{ formatMoney(bulletin.total_cotisations_salariales) }}</span></div>
              <div class="flex justify-between"><span>IGR et retenues</span>
                <span>-{{ formatMoney(bulletin.total_retenues - bulletin.total_cotisations_salariales) }}</span></div>
              <div class="flex justify-between border-t border-outline-variant pt-2 font-semibold">
                <span>NET</span>
                <span>{{ formatMoney(bulletin.total_brut - bulletin.total_retenues) }}</span>
              </div>
              <div v-if="bulletin.total_brut - bulletin.total_retenues - bulletin.net_a_payer > 0"
                   class="flex justify-between">
                <span>Avances déduites</span>
                <span>-{{ formatMoney(bulletin.total_brut - bulletin.total_retenues - bulletin.net_a_payer) }}</span>
              </div>
              <div class="flex justify-between border-t-2 border-outline pt-2 mt-2 text-base font-bold">
                <span>NET À PAYER</span><span>{{ formatMoney(bulletin.net_a_payer) }}</span>
              </div>
              <div class="flex justify-between border-t border-outline-variant pt-2 mt-2 text-on-surface-variant">
                <span>Cotisations patronales</span><span>{{ formatMoney(bulletin.total_cotisations_patronales) }}</span>
              </div>
              <div class="flex justify-between text-on-surface-variant">
                <span>Coût total employeur</span><span>{{ formatMoney(bulletin.cout_total_employeur) }}</span>
              </div>
              <div v-if="bulletin.taux_change" class="flex justify-between text-on-surface-variant">
                <span>Taux de change</span><span>{{ bulletin.taux_change }} ({{ formatDate(bulletin.date_taux_change) }})</span>
              </div>
            </div>
          </AppCard>
        </aside>
      </div>
    </template>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useBulletinStore } from '@/stores/bulletinStore';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import AppCard from '@/components/AppCard.vue';
import AppTable from '@/components/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import AppLoading from '@/components/AppLoading.vue';
import { formatMoney, formatDate } from '@/utils/format';

const store = useBulletinStore();
const route = useRoute();

const bulletin = computed(() => store.currentBulletin);

const crumbs = computed(() => [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Bulletins', to: '/bulletins' },
  { label: bulletin.value?.paie?.periode || 'Bulletin' },
]);

const joursColumns = [
  { key: 'date', label: 'Date' },
  { key: 'type_jour', label: 'Type' },
  { key: 'nombre', label: 'Nombre' },
  { key: 'taux', label: 'Taux', align: 'right' },
];

const elementsColumns = [
  { key: 'elem_paie', label: 'Élément' },
  { key: 'code', label: 'Code' },
  { key: 'type', label: 'Type' },
  { key: 'description', label: 'Description' },
  { key: 'montant', label: 'Montant', align: 'right' },
];

const cotisationsColumns = [
  { key: 'cotisation', label: 'Cotisation' },
  { key: 'montant_salarial', label: 'Part salariale', align: 'right' },
  { key: 'montant_patronal', label: 'Part patronale', align: 'right' },
];

const delegationsColumns = [
  { key: 'beneficiaire', label: 'Bénéficiaire' },
  { key: 'montant', label: 'Montant', align: 'right' },
];

const avancesColumns = [
  { key: 'date_avance', label: 'Date avance' },
  { key: 'motif', label: 'Motif' },
  { key: 'montant', label: 'Montant', align: 'right' },
];

const downloadPdf = () => {
  window.location.href = `/api/v1/bulletins/${route.params.id}/pdf`;
};

const downloadExcel = () => {
  window.location.href = `/api/v1/bulletins/${route.params.id}/excel`;
};

onMounted(() => store.fetchBulletin(route.params.id));
</script>
