<template>
  <div class="layout">
    <aside>
      <h2>Yandex Reviews</h2>
      <router-link to="/settings">Настройки</router-link>
      <router-link to="/reviews">Отзывы</router-link>
      <button @click="logout">Выйти</button>
    </aside>

    <section>
      <router-view />
    </section>
  </div>
</template>

<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = async () => {
  await axios.post('/api/logout');
  router.push('/login');
};
</script>

<style scoped>
.layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
aside { background: #202632; color: #fff; padding: 18px; display: grid; gap: 10px; align-content: start; }
a { color: #fff; text-decoration: none; }
button { padding: 8px 10px; }
section { padding: 20px; background: #f5f6f8; }
</style>
