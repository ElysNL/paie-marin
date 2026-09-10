<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-sm">
      <div class="mb-6 flex flex-col items-center">
        <span class="grid h-12 w-12 place-items-center rounded-lg bg-blue-600 text-white">
          <AppIcon name="ship" :size="26" />
        </span>
        <h1 class="mt-3 text-2xl font-bold text-gray-800">Gestion de Paie</h1>
        <p class="text-sm text-gray-500">Connectez-vous pour accéder à l'application</p>
      </div>

      <form @submit.prevent="submit" class="space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div>
          <label for="email" class="mb-1 block text-sm font-medium">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            autocomplete="email"
            required
            class="w-full rounded border p-2"
            placeholder="admin@example.com"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
        </div>

        <div>
          <label for="password" class="mb-1 block text-sm font-medium">Mot de passe</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            autocomplete="current-password"
            required
            class="w-full rounded border p-2"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password[0] }}</p>
        </div>

        <p v-if="globalError" class="text-sm text-red-600">{{ globalError }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:opacity-40"
        >
          {{ loading ? 'Connexion…' : 'Se connecter' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import AppIcon from '@/components/AppIcon.vue';
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
  router.replace(target);
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