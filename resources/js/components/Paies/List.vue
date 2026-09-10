<template>
  <div>
    <Breadcrumb :items="crumbs" />
    <PageHeader title="Paies">
      <template #action>
        <button @click="goToCreate" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Nouvelle paie</button>
      </template>
    </PageHeader>

    <div v-if="store.loading" class="py-8 text-center text-gray-500">Chargement…</div>

    <div v-else-if="paies.length === 0" class="py-8 text-center text-gray-500">
      Aucune paie enregistrée. Créez une nouvelle période de paie pour commencer.
    </div>

    <template v-else>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2 border">N°</th>
              <th class="p-2 border">Libellé</th>
              <th class="p-2 border">Période</th>
              <th class="p-2 border">Dates</th>
              <th class="p-2 border">Bulletins</th>
              <th class="p-2 border">Statut</th>
              <th class="p-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="paie in paies" :key="paie.id" class="hover:bg-gray-50">
              <td class="p-2 border">{{ paie.num_paie }}</td>
              <td class="p-2 border">{{ paie.libelle }}</td>
              <td class="p-2 border">{{ paie.periode }}</td>
              <td class="p-2 border whitespace-nowrap">
                {{ formatDate(paie.date_debut) }} → {{ formatDate(paie.date_fin) }}
              </td>
              <td class="p-2 border">{{ paie.bulletins_count ?? 0 }}</td>
              <td class="p-2 border">
                <span class="px-2 py-1 rounded text-xs font-semibold"
                      :class="statutBadge[paie.statut] || 'bg-gray-200 text-gray-700'">
                  {{ statutLibelle[paie.statut] || paie.statut }}
                </span>
              </td>
              <td class="p-2 border">
                <button @click="detail(paie.id)" class="text-green-600 mr-2 hover:underline">Voir</button>
                <button v-if="['brouillon', 'calcule'].includes(paie.statut)"
                        @click="edit(paie.id)" class="text-blue-600 mr-2 hover:underline">Modifier</button>
                <button v-if="paie.statut === 'brouillon'"
                        @click="remove(paie.id)" class="text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination
        :current="pagination?.current_page"
        :last="pagination?.last_page"
        @page-change="fetchPaies"
      />
    </template>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { usePaieStore } from '@/stores/paieStore';
import { useRouter } from 'vue-router';
import Pagination from '@/components/Pagination.vue';
import { formatDate, statutBadge, statutLibelle } from '@/utils/format';

const store = usePaieStore();
const router = useRouter();
const crumbs = [{ label: 'Tableau de bord', to: '/dashboard' }, { label: 'Paies' }];

const paies = computed(() => store.paies);
const pagination = computed(() => store.pagination);

const fetchPaies = (page = 1) => store.fetchPaies(page);
const goToCreate = () => router.push('/paies/create');
const detail = (id) => router.push(`/paies/${id}`);
const edit = (id) => router.push(`/paies/${id}/edit`);

const remove = async (id) => {
  if (confirm('Voulez-vous supprimer cette paie ?')) {
    await store.deletePaie(id);
  }
};

onMounted(() => fetchPaies());
</script>