<template>
  <nav class="bg-gray-800 text-white shadow-md">
    <div class="px-4 py-3 flex items-center justify-between">
      <router-link to="/paies" class="font-bold text-lg">Gestion de Paie</router-link>

      <!-- Menu desktop -->
      <ul class="hidden lg:flex gap-6 text-sm">
        <li><router-link to="/paies" class="hover:text-blue-300" :class="{ 'text-blue-300': isActive('/paies') }">Paies</router-link></li>
        <li><router-link to="/bulletins" class="hover:text-blue-300" :class="{ 'text-blue-300': isActive('/bulletins') }">Bulletins</router-link></li>
        <li><router-link to="/employes" class="hover:text-blue-300" :class="{ 'text-blue-300': isActive('/employes') }">Employés</router-link></li>
        <li><router-link to="/avances" class="hover:text-blue-300" :class="{ 'text-blue-300': isActive('/avances') }">Avances</router-link></li>
        <li><router-link to="/delegations" class="hover:text-blue-300" :class="{ 'text-blue-300': isActive('/delegations') }">Délégations</router-link></li>
        <li class="relative">
          <button @click="toggleGroup('gestion')" class="hover:text-blue-300">
            Gestion <span class="text-xs">▾</span>
          </button>
          <div v-if="openGroups.gestion" class="absolute z-40 mt-2 w-48 bg-gray-700 rounded shadow-lg py-2">
            <router-link to="/armateurs" class="block px-4 py-2 hover:bg-gray-600">Armateurs</router-link>
            <router-link to="/navires" class="block px-4 py-2 hover:bg-gray-600">Navires</router-link>
            <router-link to="/contrats-armateur" class="block px-4 py-2 hover:bg-gray-600">Contrats d'armateur</router-link>
            <router-link to="/affectations" class="block px-4 py-2 hover:bg-gray-600">Affectations</router-link>
          </div>
        </li>
        <li class="relative">
          <button @click="toggleGroup('referentials')" class="hover:text-blue-300">
            Référentiels <span class="text-xs">▾</span>
          </button>
          <div v-if="openGroups.referentials" class="absolute z-40 mt-2 w-48 bg-gray-700 rounded shadow-lg py-2">
            <router-link to="/pays" class="block px-4 py-2 hover:bg-gray-600">Pays</router-link>
            <router-link to="/devises" class="block px-4 py-2 hover:bg-gray-600">Devises</router-link>
            <router-link to="/fonctions" class="block px-4 py-2 hover:bg-gray-600">Fonctions</router-link>
            <router-link to="/classifications" class="block px-4 py-2 hover:bg-gray-600">Classifications</router-link>
            <router-link to="/compagnies" class="block px-4 py-2 hover:bg-gray-600">Compagnies</router-link>
          </div>
        </li>
      </ul>

      <!-- Bouton menu mobile -->
      <button class="lg:hidden text-2xl leading-none" @click="mobileOpen = !mobileOpen">☰</button>
    </div>

    <!-- Menu mobile -->
    <div v-if="mobileOpen" class="lg:hidden bg-gray-700 px-4 pb-4 text-sm space-y-2">
      <router-link to="/paies" class="block py-2 border-b border-gray-600">Paies</router-link>
      <router-link to="/bulletins" class="block py-2 border-b border-gray-600">Bulletins</router-link>
      <router-link to="/employes" class="block py-2 border-b border-gray-600">Employés</router-link>
      <router-link to="/avances" class="block py-2 border-b border-gray-600">Avances</router-link>
      <router-link to="/delegations" class="block py-2 border-b border-gray-600">Délégations</router-link>
      <div>
        <div class="py-2 font-semibold text-gray-300">Gestion</div>
        <router-link to="/armateurs" class="block pl-3 py-1">Armateurs</router-link>
        <router-link to="/navires" class="block pl-3 py-1">Navires</router-link>
        <router-link to="/contrats-armateur" class="block pl-3 py-1">Contrats d'armateur</router-link>
        <router-link to="/affectations" class="block pl-3 py-1">Affectations</router-link>
      </div>
      <div>
        <div class="py-2 font-semibold text-gray-300">Référentiels</div>
        <router-link to="/pays" class="block pl-3 py-1">Pays</router-link>
        <router-link to="/devises" class="block pl-3 py-1">Devises</router-link>
        <router-link to="/fonctions" class="block pl-3 py-1">Fonctions</router-link>
        <router-link to="/classifications" class="block pl-3 py-1">Classifications</router-link>
        <router-link to="/compagnies" class="block pl-3 py-1">Compagnies</router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const mobileOpen = ref(false);
const openGroups = reactive({ gestion: false, referentials: false });

const toggleGroup = (group) => {
  openGroups[group] = !openGroups[group];
};

const isActive = (path) => route.path.startsWith(path);
</script>