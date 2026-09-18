<template>
  <div>
    <div class="mb-6">
      <h1 class="text-2xl font-bold">{{ isEdit ? "Modifier l'utilisateur" : 'Nouvel utilisateur' }}</h1>
    </div>

    <AppCard>
      <form @submit.prevent="submit" class="space-y-4">
        <AppInput v-model="form.name" label="Nom complet" :error="errors.name" required />
        <AppInput v-model="form.email" label="Email" type="email" :error="errors.email" required />
        <AppInput
          v-model="form.password"
          :label="isEdit ? 'Nouveau mot de passe (laisser vide pour garder)' : 'Mot de passe'"
          type="password"
          :error="errors.password"
          :required="!isEdit"
        />
        <AppInput
          v-if="form.password"
          v-model="form.password_confirmation"
          label="Confirmer le mot de passe"
          type="password"
        />
        <AppSelect
          v-model="form.role"
          label="Rôle"
          :options="roleOptions"
          :error="errors.role"
          required
        />

        <div class="flex items-center gap-3 pt-4">
          <AppButton type="submit" :loading="loading">
            {{ isEdit ? 'Enregistrer' : 'Créer' }}
          </AppButton>
          <router-link to="/utilisateurs">
            <AppButton variant="secondary">Annuler</AppButton>
          </router-link>
        </div>
      </form>
    </AppCard>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import apiClient from '@/services/api';
import AppCard from '@/components/AppCard.vue';
import AppInput from '@/components/AppInput.vue';
import AppSelect from '@/components/AppSelect.vue';
import AppButton from '@/components/AppButton.vue';

const route = useRoute();
const router = useRouter();

const isEdit = computed(() => !!route.params.id);
const loading = ref(false);
const errors = reactive({});

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'user',
});

const roleOptions = [
  { value: 'admin', label: 'Administrateur' },
  { value: 'rh', label: 'RH' },
  { value: 'paie', label: 'Paie' },
  { value: 'user', label: 'Utilisateur' },
];

onMounted(async () => {
  if (isEdit.value) {
    const res = await apiClient.get(`/users/${route.params.id}`);
    const data = res.data;
    form.name = data.name || '';
    form.email = data.email || '';
    form.role = data.role || 'user';
  }
});

const submit = async () => {
  Object.keys(errors).forEach((k) => delete errors[k]);
  loading.value = true;

  try {
    const payload = { ...form };
    if (isEdit.value && !payload.password) {
      delete payload.password;
      delete payload.password_confirmation;
    }

    if (isEdit.value) {
      await apiClient.put(`/users/${route.params.id}`, payload);
    } else {
      await apiClient.post('/users', payload);
    }
    router.push('/utilisateurs');
  } catch (e) {
    if (e.response?.status === 422 && e.response.data?.errors) {
      Object.assign(errors, e.response.data.errors);
    }
  } finally {
    loading.value = false;
  }
};
</script>
