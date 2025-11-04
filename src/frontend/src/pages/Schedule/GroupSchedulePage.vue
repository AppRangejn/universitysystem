<template>
  <div class="mt-10 bg-white rounded-2xl shadow-xl border border-blue-200 overflow-hidden">
    <!-- 🔹 Верхня шапка -->
    <div class="bg-blue-700 text-white py-6 text-center border-b-4 border-blue-500">
      <h2 class="text-3xl font-bold uppercase tracking-wide">
        Розклад групи {{ groupName || "..." }}
      </h2>
      <p class="text-lg font-semibold mt-1">Тиждень 1</p>
    </div>

    <!-- 🔄 Завантаження -->
    <div v-if="loading" class="text-center py-10 text-gray-500 text-lg">
      Завантаження...
    </div>

    <div v-else-if="schedules.length === 0" class="text-center py-10 text-gray-500 text-lg">
      Розклад відсутній 😔
    </div>

    <!-- 📅 Таблиця -->
    <div v-else class="overflow-x-auto">
      <table class="w-full text-center border-collapse">
        <thead class="bg-blue-100 text-blue-900 border-b border-blue-300">
        <tr>
          <th class="border border-blue-200 py-3 w-24 font-bold text-lg bg-blue-800 text-white">
            № пари
          </th>
          <th
            v-for="day in days"
            :key="day"
            class="border border-blue-200 py-3 text-lg font-semibold bg-blue-700 text-white"
          >
            {{ day }}
          </th>
        </tr>
        </thead>

        <tbody>
        <tr
          v-for="pairNumber in pairNumbers"
          :key="pairNumber"
          class="odd:bg-white even:bg-blue-50"
        >
          <td class="border border-blue-200 py-6 font-bold text-blue-800 bg-blue-50">
            {{ pairNumber }}
          </td>

          <td
            v-for="day in days"
            :key="day"
            class="border border-blue-200 align-top px-2 py-3 min-w-[220px] relative"
          >
            <div
              v-for="lesson in getDaySchedule(day, pairNumber)"
              :key="lesson.id"
              class="bg-yellow-100 border border-yellow-400 rounded-md p-2 mb-2 shadow-sm hover:shadow-md transition text-left"
            >
              <p class="font-bold text-gray-900 leading-tight">
                {{ lesson.subject }}
              </p>
              <p class="text-sm text-gray-700 mt-1 italic">
                {{ lesson.type || 'Заняття' }}
              </p>
              <p class="text-sm text-gray-700 mt-1">
                <span class="font-semibold">Викладач:</span> {{ lesson.teacher }}
              </p>
              <p class="text-xs text-gray-600 mt-1">
                <span class="font-semibold">Ауд.:</span> {{ lesson.room }}
                <span class="ml-2">• {{ lesson.time }}</span>
              </p>
            </div>

            <div
              v-if="getDaySchedule(day, pairNumber).length === 0"
              class="text-gray-400 italic text-sm"
            >
              —
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";

const route = useRoute();
const groupName = ref("");
const schedules = ref([]);
const loading = ref(true);
const days = ref([]);
const pairNumbers = ref([]);

const getDaySchedule = (day, pairNumber) => {
  return schedules.value.filter(
    (s) =>
      s.day?.trim().toLowerCase() === day.trim().toLowerCase() &&
      Number(s.pair_number) === Number(pairNumber)
  );
};

onMounted(async () => {
  try {
    const groupId = route.params.id;

    // 🧩 Назва групи
    const groupRes = await axios.get(`/api/groups/${groupId}`);
    groupName.value = groupRes.data.name;

    // ⚙️ Отримати налаштування розкладу
    const settingsRes = await axios.get("/api/admin/settings/schedule");
    const settings = settingsRes.data;

    // Записати дні та кількість пар
    days.value =
      Array.isArray(settings.days) && settings.days.length
        ? settings.days
        : ["Понеділок", "Вівторок", "Середа", "Четвер", "П’ятниця"];

    pairNumbers.value = Array.from(
      { length: settings.pair_count || 6 },
      (_, i) => i + 1
    );

    // 📅 Отримати розклад
    const scheduleRes = await axios.get(`/api/schedules?group_id=${groupId}`);
    schedules.value = scheduleRes.data;
  } catch (err) {
    console.error("❌ Помилка при завантаженні розкладу:", err);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
table {
  border-radius: 8px;
  overflow: hidden;
}
</style>
