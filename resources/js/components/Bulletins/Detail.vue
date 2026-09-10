<template>
  <div>
    <Breadcrumb :items="crumbs" />

    <div v-if="store.loading && !bulletin" class="py-8 text-center text-gray-500">Chargement…</div>

    <template v-else-if="bulletin">
      <PageHeader :title="`Bulletin — ${bulletin.employe?.nom} ${bulletin.employe?.prenom}`">
        <template #action>
          <button @click="downloadPdf" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
            Télécharger PDF
          </button>
          <button @click="downloadExcel" class="px-3 py-2 bg-green-700 text-white rounded hover:bg-green-800 transition text-sm">
            Télécharger Excel
          </button>
        </template>
      </PageHeader>

      <div class="mb-6 rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
        <p>{{ bulletin.paie?.libelle }} · {{ bulletin.paie?.periode }} ·
          Navire : {{ bulletin.navire?.nom }} ·
          {{ formatDate(bulletin.paie?.date_debut) }} → {{ formatDate(bulletin.paie?.date_fin) }}
        </p>
        <p v-if="bulletin.affectation?.fonction" class="mt-1">
          Fonction : {{ bulletin.affectation.fonction.nom }}
        </p>
        <p v-if="bulletin.affectation?.contrat_armateur?.devise" class="mt-1">
          Devise du contrat : {{ bulletin.affectation.contrat_armateur.devise.code }}
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
          <section v-if="bulletin.jours?.length">
            <h2 class="text-lg font-semibold mb-2">Jours de travail</h2>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse text-sm">
                <thead>
                  <tr class="bg-gray-100 text-left">
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Type</th>
                    <th class="p-2 border">Nombre</th>
                    <th class="p-2 border">Taux</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="j in bulletin.jours" :key="j.id">
                    <td class="p-2 border">{{ formatDate(j.date) }}</td>
                    <td class="p-2 border">{{ j.type_jour }}</td>
                    <td class="p-2 border">{{ j.nombre }}</td>
                    <td class="p-2 border text-right">{{ formatMoney(j.taux) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <section>
            <h2 class="text-lg font-semibold mb-2">Brut et retenues</h2>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse text-sm">
                <thead>
                  <tr class="bg-gray-100 text-left">
                    <th class="p-2 border">Élément</th>
                    <th class="p-2 border">Code</th>
                    <th class="p-2 border">Type</th>
                    <th class="p-2 border">Description</th>
                    <th class="p-2 border text-right">Montant</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="e in bulletin.elements" :key="e.id">
                    <td class="p-2 border">{{ e.elem_paie?.libelle }}</td>
                    <td class="p-2 border">{{ e.elem_paie?.code }}</td>
                    <td class="p-2 border">{{ e.elem_paie?.type }}</td>
                    <td class="p-2 border">{{ e.description || '—' }}</td>
                    <td class="p-2 border text-right">{{ formatMoney(e.montant) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <section v-if="bulletin.cotisations?.length">
            <h2 class="text-lg font-semibold mb-2">Cotisations</h2>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse text-sm">
                <thead>
                  <tr class="bg-gray-100 text-left">
                    <th class="p-2 border">Cotisation</th>
                    <th class="p-2 border">Part salariale</th>
                    <th class="p-2 border">Part patronale</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="c in bulletin.cotisations" :key="c.id">
                    <td class="p-2 border">{{ c.cotisation?.nom }}</td>
                    <td class="p-2 border text-right">{{ formatMoney(c.montant_salarial) }}</td>
                    <td class="p-2 border text-right">{{ formatMoney(c.montant_patronal) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <section v-if="bulletin.delegations?.length">
            <h2 class="text-lg font-semibold mb-2">Délégations</h2>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse text-sm">
                <thead>
                  <tr class="bg-gray-100 text-left">
                    <th class="p-2 border">Bénéficiaire</th>
                    <th class="p-2 border text-right">Montant</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="d in bulletin.delegations" :key="d.id">
                    <td class="p-2 border">{{ d.delegation?.beneficiaire }}</td>
                    <td class="p-2 border text-right">{{ formatMoney(d.montant) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <section v-if="bulletin.remboursements_avances?.length">
            <h2 class="text-lg font-semibold mb-2">Avances remboursées</h2>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse text-sm">
                <thead>
                  <tr class="bg-gray-100 text-left">
                    <th class="p-2 border">Date avance</th>
                    <th class="p-2 border">Motif</th>
                    <th class="p-2 border text-right">Montant</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="r in bulletin.remboursements_avances" :key="r.id">
                    <td class="p-2 border">{{ formatDate(r.avance?.date_avance) }}</td>
                    <td class="p-2 border">{{ r.avance?.motif || '—' }}</td>
                    <td class="p-2 border text-right">{{ formatMoney(r.montant) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <aside>
          <div class="bg-gray-50 border rounded-lg p-4 space-y-2 text-sm">
            <h2 class="text-base font-semibold mb-3">Synthèse</h2>
            <div class="flex justify-between"><span>Total jours</span><span>{{ bulletin.total_jours }}</span></div>
            <div class="flex justify-between"><span>Total gains</span><span>{{ formatMoney(bulletin.total_gains) }}</span></div>
            <div class="flex justify-between font-semibold border-t pt-2 mt-2">
              <span>BRUT</span><span>{{ formatMoney(bulletin.total_brut) }}</span>
            </div>
            <div class="flex justify-between"><span>Cotisations salariales</span>
              <span>-{{ formatMoney(bulletin.total_cotisations_salariales) }}</span></div>
            <div class="flex justify-between"><span>IGR et retenues</span>
              <span>-{{ formatMoney(bulletin.total_retenues - bulletin.total_cotisations_salariales) }}</span></div>
            <div class="flex justify-between font-semibold border-t pt-2 mt-2">
              <span>NET</span>
              <span>{{ formatMoney(bulletin.total_brut - bulletin.total_retenues) }}</span>
            </div>
            <div v-if="bulletin.total_brut - bulletin.total_retenues - bulletin.net_a_payer > 0"
                 class="flex justify-between">
              <span>Avances déduites</span>
              <span>-{{ formatMoney(bulletin.total_brut - bulletin.total_retenues - bulletin.net_a_payer) }}</span>
            </div>
            <div class="flex justify-between text-base font-bold border-t-2 border-gray-300 pt-2 mt-2">
              <span>NET À PAYER</span><span>{{ formatMoney(bulletin.net_a_payer) }}</span>
            </div>
            <div class="flex justify-between border-t pt-2 mt-2 text-gray-600">
              <span>Cotisations patronales</span><span>{{ formatMoney(bulletin.total_cotisations_patronales) }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Coût total employeur</span><span>{{ formatMoney(bulletin.cout_total_employeur) }}</span>
            </div>
            <div v-if="bulletin.taux_change" class="flex justify-between text-gray-600">
              <span>Taux de change</span><span>{{ bulletin.taux_change }} ({{ formatDate(bulletin.date_taux_change) }})</span>
            </div>
          </div>
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
import { formatMoney, formatDate } from '@/utils/format';

const store = useBulletinStore();
const route = useRoute();

const bulletin = computed(() => store.currentBulletin);

const crumbs = computed(() => [
  { label: 'Tableau de bord', to: '/dashboard' },
  { label: 'Bulletins', to: '/bulletins' },
  { label: bulletin.value?.paie?.periode || 'Bulletin' },
]);

const downloadPdf = () => {
  window.location.href = `/api/v1/bulletins/${route.params.id}/pdf`;
};

const downloadExcel = () => {
  window.location.href = `/api/v1/bulletins/${route.params.id}/excel`;
};

onMounted(() => store.fetchBulletin(route.params.id));
</script>