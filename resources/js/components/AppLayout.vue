<template>
  <div class="flex h-screen overflow-hidden">
    <SideNav :open="navOpen" @close="navOpen = false" />

    <div class="flex flex-1 flex-col overflow-hidden">
      <TopBar @toggle-nav="navOpen = true" />

      <main class="flex-1 overflow-y-auto p-4 lg:p-6">
        <div class="mx-auto w-full max-w-[1440px]">
          <RouterView />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import SideNav from '@/components/SideNav.vue';
import TopBar from '@/components/TopBar.vue';

const navOpen = ref(false);
</script>
