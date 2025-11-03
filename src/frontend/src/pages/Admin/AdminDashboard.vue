<template>
  <div>
    <h1 class="text-3xl font-bold text-blue-900 mb-6">Панель адміністратора</h1>

    <div v-if="loading" class="text-gray-500">Завантаження...</div>

    <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg border border-blue-100 transition">
        <h2 class="text-xl font-semibold text-gray-700">📚 Розклади</h2>
        <p class="text-gray-500 mt-2">Кількість: {{ stats.schedules_count }}</p>
      </div>
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg border border-blue-100 transition">
        <h2 class="text-xl font-semibold text-gray-700">👥 Користувачі</h2>
        <p class="text-gray-500 mt-2">Кількість: {{ stats.users_count }}</p>
      </div>
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg border border-blue-100 transition">
        <h2 class="text-xl font-semibold text-gray-700">🏫 Групи</h2>
        <p class="text-gray-500 mt-2">Кількість: {{ stats.groups_count }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const stats = ref({
  users_count: 0,
  groups_count: 0,
  schedules_count: 0,
});
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await axios.get("/api/admin/stats", { withCredentials: true });
    stats.value = res.data;
  } catch (err) {
    console.error("Помилка при отриманні статистики:", err);
  } finally {
    loading.value = false;
  }
});
</script>
