<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Délégations">
      <template #action>
        <button @click="$router.push('/delegations/create')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle délégation</button>
      </template>
    </PageHeader>

    <label class="flex items-center gap-2 text-sm mb-4">
      Employé
      <select v-model="filterEmploye" @change="fetch(1)" class="border p-2 rounded">
        <option :value="null">Tous</option>
        <option v-for="e in employes" :key="e.id" :value="e.id">{{ e.nom }} {{ e.prenom }}</option>
      </select>
    </label>

    <div v-if="store.loading" class="py-8 text-center text-gray-500">Chargement…</div>
    <div v-else-if="delegations.length === 0" class="py-8 text-center text-gray-500">Aucune délégation enregistrée.</div>

    <template v-else>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2 border">Employé</th>
              <th class="p-2 border">Bénéficiaire</th>
              <th class="p-2 border">Montant</th>
              <th class="p-2 border">Période</th>
              <th class="p-2 border">Fréquence</th>
              <th class="p-2 border">Statut</th>
              <th class="p-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="d in delegations" :key="d.id" class="hover:bg-gray-50">
              <td class="p-2 border">{{ d.employe?.nom }} {{ d.employe?.prenom }}</td>
              <td class="p-2 border">{{ d.beneficiaire }}</td>
              <td class="p-2 border text-right">{{ formatMoney(d.montant, d.devise?.code) }}</td>
              <td class="p-2 border whitespace-nowrap">{{ formatDate(d.date_debut) }} → {{ formatDate(d.date_fin) }}</td>
              <td class="p-2 border">{{ d.frequence || '—' }}</td>
              <td class="p-2 border">
                <span class="px-2 py-1 rounded text-xs font-semibold"
                      :class="statutBadge[d.statut] || 'bg-gray-200 text-gray-700'">
                  {{ statutLibelle[d.statut] || d.statut }}
                </span>
              </td>
              <td class="p-2 border">
                <button @click="$router.push('/delegations/' + d.id + '/edit')" class="text-blue-600 mr-2 hover:underline">
                  Modifier
                </button>
                <button @click="remove(d.id)" class="text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination :current="pagination?.current_page" :last="pagination?.last_page"
                  @page-change="fetch" />
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useDelegationStore } from '@/stores/delegationStore';
import { useEmployeStore } from '@/stores/employeStore';
import { useToasts } from '@/services/toast';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import { formatMoney, formatDate, statutBadge, statutLibelle } from '@/utils/format';

const store = useDelegationStore();
const employeStore = useEmployeStore();
const { success } = useToasts();

const delegations = computed(() => store.delegations);
const pagination = computed(() => store.pagination);
const employes = computed(() => employeStore.employes);

const filterEmploye = ref(null);
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Délégations' }];

const fetch = (page = 1) => store.fetchDelegations({ page, employeId: filterEmploye.value });

const remove = async (id) => {
  if (confirm('Supprimer cette délégation ?')) {
    await store.deleteDelegation(id);
    success('Délégation supprimée.');
  }
};

onMounted(async () => {
  await employeStore.fetchEmployes();
  await fetch(1);
});
</script>