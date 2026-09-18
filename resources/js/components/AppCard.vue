<template>
  <div class="rounded-lg border border-outline-variant bg-surface-container-lowest shadow-elevation-1">
    <div v-if="$slots.header" class="border-b border-outline-variant px-6 py-4">
      <slot name="header" />
    </div>
    <div class="p-6">
      <slot />
    </div>
  </div>
</template>
