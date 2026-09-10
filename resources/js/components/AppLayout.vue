<template>
  <div class="flex h-screen overflow-hidden">
    <SideNav :open="navOpen" @close="navOpen = false" />

    <main class="flex flex-1 flex-col overflow-y-auto p-4 lg:p-6">
      <div class="mb-4 lg:hidden">
        <button
          type="button"
          @click="navOpen = true"
          class="inline-flex h-9 w-9 items-center justify-center rounded border border-gray-300 bg-white text-gray-700"
          aria-label="Ouvrir le menu"
        >
          <AppIcon name="menu" :size="18" />
        </button>
      </div>

      <div class="mx-auto w-full max-w-[1440px]">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import AppIcon from '@/components/AppIcon.vue';
import SideNav from '@/components/SideNav.vue';

const navOpen = ref(false);
</script>