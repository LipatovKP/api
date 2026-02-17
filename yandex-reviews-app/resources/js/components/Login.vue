<template>
  <main class="container">
    <h1>Вход</h1>
    <form @submit.prevent="submit">
      <input v-model="form.email" type="email" placeholder="Email" required>
      <input v-model="form.password" type="password" placeholder="Пароль" required>
      <button :disabled="loading">{{ loading ? 'Входим...' : 'Войти' }}</button>
    </form>
    <p v-if="error" class="error">{{ error }}</p>
  </main>
</template>

<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const error = ref('');
const form = reactive({ email: '', password: '' });

const submit = async () => {
  loading.value = true;
  error.value = '';

  try {
    await axios.post('/api/login', form);
    router.push('/settings');
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Ошибка входа';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.container { max-width: 420px; margin: 40px auto; }
form { display: grid; gap: 12px; }
input, button { padding: 10px; font-size: 14px; }
.error { color: #d00; }
</style>
