<template>
  <div>
    <h1>Отзывы компании</h1>

    <div class="toolbar">
      <label>
        Сортировка
        <select v-model="sort" @change="load(1)">
          <option value="newest">Сначала новые</option>
          <option value="oldest">Сначала старые</option>
        </select>
      </label>
    </div>

    <div v-if="company" class="card">
      <h2>{{ company.name || 'Компания' }}</h2>
      <p>Рейтинг: <strong>{{ company.rating ?? '—' }}</strong></p>
      <p>Всего отзывов: <strong>{{ company.reviews_count ?? 0 }}</strong></p>
    </div>

    <article v-for="review in reviews" :key="review.id" class="review">
      <header>
        <strong>{{ review.author?.name || 'Пользователь' }}</strong>
        <span>{{ review.rating }}★</span>
      </header>
      <p>{{ review.text || 'Без текста' }}</p>
      <small>{{ review.createdAt || review.created_at }}</small>
    </article>

    <nav class="pager" v-if="pagination.last_page > 1">
      <button :disabled="pagination.current_page <= 1" @click="load(pagination.current_page - 1)">Назад</button>
      <span>Страница {{ pagination.current_page }} / {{ pagination.last_page }}</span>
      <button :disabled="pagination.current_page >= pagination.last_page" @click="load(pagination.current_page + 1)">Вперед</button>
    </nav>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const reviews = ref([]);
const company = ref(null);
const error = ref('');
const sort = ref('newest');
const pagination = ref({ current_page: 1, last_page: 1 });

const load = async (page = 1) => {
  error.value = '';
  try {
    const { data } = await axios.get('/api/reviews', {
      params: { page, per_page: 10, sort: sort.value },
    });

    reviews.value = data.reviews || [];
    company.value = data.company || null;
    pagination.value = data.pagination || { current_page: 1, last_page: 1 };
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Не удалось получить отзывы';
  }
};

onMounted(() => load(1));
</script>

<style scoped>
.toolbar { margin-bottom: 12px; }
.review { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin: 10px 0; }
.review header { display: flex; justify-content: space-between; margin-bottom: 8px; }
.card { background: #eef5ff; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
.pager { display: flex; gap: 12px; align-items: center; margin-top: 16px; }
.error { color: #d00; }
</style>
