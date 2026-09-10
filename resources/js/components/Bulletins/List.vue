<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Bulletins de paie" />

    <div class="flex gap-4 mb-4 flex-wrap">
      <label class="flex items-center gap-2 text-sm">
        Paie
        <select v-model="filterPaie" class="border p-2 rounded">
          <option :value="null">Toutes</option>
          <option v-for="p in paies" :key="p.id" :value="p.id">
            {{ p.libelle }} ({{ p.periode }})
          </option>
        </select>
      </label>
      <label class="flex items-center gap-2 text-sm">
        Employé
        <select v-model="filterEmploye" class="border p-2 rounded">
          <option :value="null">Tous</option>
          <option v-for="e in employes" :key="e.id" :value="e.id">
            {{ e.nom }} {{ e.prenom }}
          </option>
        </select>
      </label>
      <button @click="fetch(1)" class="px-3 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
        Filtrer
      </button>
    </div>

    <div v-if="store.loading" class="py-8 text-center text-gray-500">Chargement…</div>

    <div v-else-if="bulletins.length === 0" class="py-8 text-center text-gray-500">
      Aucun bulletin trouvé.
    </div>

    <template v-else>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2 border">Employé</th>
              <th class="p-2 border">Navire</th>
              <th class="p-2 border">Paie</th>
              <th class="p-2 border">Jours</th>
              <th class="p-2 border">Brut</th>
              <th class="p-2 border">Retenues</th>
              <th class="p-2 border">Net à payer</th>
              <th class="p-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="b in bulletins" :key="b.id" class="hover:bg-gray-50">
              <td class="p-2 border">{{ b.employe?.nom }} {{ b.employe?.prenom }}</td>
              <td class="p-2 border">{{ b.navire?.nom }}</td>
              <td class="p-2 border">{{ b.paie?.libelle }}</td>
              <td class="p-2 border">{{ b.total_jours }}</td>
              <td class="p-2 border text-right">{{ formatMoney(b.total_brut) }}</td>
              <td class="p-2 border text-right">{{ formatMoney(b.total_retenues) }}</td>
              <td class="p-2 border text-right font-semibold">{{ formatMoney(b.net_a_payer) }}</td>
              <td class="p-2 border">
                <button @click="$router.push('/bulletins/' + b.id)" class="text-green-600 mr-2 hover:underline">
                  Détail
                </button>
                <button @click="remove(b.id)" class="text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination
        :current="pagination?.current_page"
        :last="pagination?.last_page"
        @page-change="fetch"
      />
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useBulletinStore } from '@/stores/bulletinStore';
import { usePaieStore } from '@/stores/paieStore';
import { useEmployeStore } from '@/stores/employeStore';
import Pagination from '@/components/Pagination.vue';
import Breadcrumb from '@/components/Breadcrumb.vue';
import PageHeader from '@/components/PageHeader.vue';
import { formatMoney } from '@/utils/format';

const store = useBulletinStore();
const paieStore = usePaieStore();
const employeStore = useEmployeStore();
const route = useRoute();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Bulletins de paie' }];

const bulletins = computed(() => store.bulletins);
const pagination = computed(() => store.pagination);
const paies = computed(() => paieStore.paies);
const employes = computed(() => employeStore.employes);

const filterPaie = ref(route.query.paie_id ? Number(route.query.paie_id) : null);
const filterEmploye = ref(null);

const fetch = (page = 1) => store.fetchBulletins({
  page,
  paieId: filterPaie.value,
  employeId: filterEmploye.value,
});

const remove = async (id) => {
  if (confirm('Supprimer ce bulletin ?')) {
    await store.deleteBulletin(id);
    await fetch(pagination.value?.current_page || 1);
  }
};

onMounted(async () => {
  await Promise.all([paieStore.fetchPaies(), employeStore.fetchEmployes()]);
  await fetch(1);
});
</script>