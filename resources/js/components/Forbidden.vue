<template>
  <div class="grid min-h-screen place-items-center bg-surface">
    <div class="text-center">
      <p class="text-6xl font-bold text-error">403</p>
      <p class="mt-2 text-lg text-on-surface">Accès interdit</p>
      <p class="mt-1 text-sm text-on-surface-variant">
        Vous n'avez pas les permissions nécessaires pour accéder à cette page.
      </p>
      <router-link to="/dashboard" class="mt-6 inline-block">
        <AppButton>Retour au tableau de bord</AppButton>
      </router-link>
    </div>
  </div>
</template>
