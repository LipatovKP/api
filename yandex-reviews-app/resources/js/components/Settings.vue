<template>
  <div>
    <h1>Настройки интеграции</h1>
    <form @submit.prevent="save" class="form">
      <label>
        Ссылка на карточку Яндекс Карт
        <input v-model="form.map_url" type="url" placeholder="https://yandex.ru/maps/org/.../reviews/" required>
      </label>

      <label>
        Organization ID (из Yandex Business)
        <input v-model="form.organization_id" type="text" placeholder="1010501395" required>
      </label>

      <label>
        Business ID (опционально)
        <input v-model="form.business_id" type="text" placeholder="business-id">
      </label>

      <button :disabled="loading">{{ loading ? 'Сохранение...' : 'Сохранить' }}</button>
    </form>

    <p v-if="message" class="ok">{{ message }}</p>
    <p class="hint">
      Если у вас только ссылка, извлеките ID организации из URL и дополнительно проверьте его в кабинете
      <code>business.yandex.ru</code>.
    </p>
  </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';

const loading = ref(false);
const message = ref('');

const form = reactive({
  map_url: '',
  organization_id: '',
  business_id: '',
});

const load = async () => {
  const { data } = await axios.get('/api/settings');
  form.map_url = data.map_url || '';
  form.organization_id = data.organization_id || '';
  form.business_id = data.business_id || '';
};

const save = async () => {
  loading.value = true;
  message.value = '';
  await axios.put('/api/settings', form);
  message.value = 'Сохранено';
  loading.value = false;
};

onMounted(load);
</script>

<style scoped>
.form { display: grid; gap: 12px; max-width: 600px; }
input, button { padding: 10px; width: 100%; }
.ok { color: #008a1f; }
.hint { margin-top: 12px; color: #555; }
</style>
