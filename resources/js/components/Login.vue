<template>
  <div class="flex min-h-screen items-center justify-center bg-surface p-4">
    <div class="w-full max-w-sm">
      <div class="mb-6 flex flex-col items-center">
        <span class="grid h-12 w-12 place-items-center rounded-xl bg-primary text-on-primary shadow-elevation-2">
          <AppIcon name="ship" :size="26" />
        </span>
        <h1 class="mt-3 text-2xl font-bold text-on-surface">Gestion de Paie</h1>
        <p class="text-sm text-on-surface-variant">Connectez-vous pour accéder à l'application</p>
      </div>

      <form @submit.prevent="submit" class="space-y-4 rounded-lg border border-outline-variant bg-surface-container-lowest p-6 shadow-elevation-2">
        <AppInput v-model="form.email" label="Email" type="email" autocomplete="email"
          placeholder="admin@example.com" :error="errors.email" required />
        <AppInput v-model="form.password" label="Mot de passe" type="password" autocomplete="current-password"
          :error="errors.password" required />
        <p v-if="globalError" class="text-sm text-on-error-container">{{ globalError }}</p>
        <AppButton type="submit" :loading="loading" class="w-full">
          {{ loading ? 'Connexion…' : 'Se connecter' }}
        </AppButton>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import AppIcon from '@/components/AppIcon.vue';
import AppInput from '@/components/AppInput.vue';
import AppButton from '@/components/AppButton.vue';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const form = reactive({ email: '', password: '' });
const errors = reactive({});
const globalError = ref('');
const loading = ref(false);

const redirect = () => {
  const target = typeof route.query.redirect === 'string' ? route.query.redirect : '/dashboard';
  if (target.startsWith('http://') || target.startsWith('https://') || target.startsWith('//')) {
    router.replace('/dashboard');
  } else {
    router.replace(target);
  }
};

const submit = async () => {
  Object.keys(errors).forEach((k) => delete errors[k]);
  globalError.value = '';
  loading.value = true;
  try {
    await auth.login(form.email, form.password);
    redirect();
  } catch (e) {
    if (e.response?.status === 422 && e.response.data?.errors) {
      Object.assign(errors, e.response.data.errors);
    } else if (e.response?.data?.message) {
      globalError.value = e.response.data.message;
    } else {
      globalError.value = 'Impossible de contacter le serveur.';
    }
  } finally {
    loading.value = false;
  }
};
</script>
