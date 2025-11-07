<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const loading = ref(true);
const settings = ref({ days: [], pair_count: 6 });
const newDay = ref("");

async function fetchSettings() {
  try {
    const res = await axios.get("/api/admin/settings/schedule");
    settings.value = res.data;
  } catch (err) {
    console.error("❌ Помилка при отриманні налаштувань:", err);
    settings.value = {
      days: ["Понеділок", "Вівторок", "Середа", "Четвер", "П’ятниця"],
      pair_count: 6,
    };
  } finally {
    loading.value = false;
  }
}

async function saveSettings() {
  try {
    await axios.put("/api/admin/settings/schedule", settings.value);
    alert("✅ Налаштування збережено!");
  } catch (err) {
    console.error("❌ Помилка при збереженні:", err);
    alert("❌ Помилка при збереженні");
  }
}

async function addDay() {
  if (newDay.value.trim()) {
    settings.value.days.push(newDay.value.trim());
    newDay.value = "";
    try {
      await axios.put("/api/admin/settings/schedule", settings.value);
      console.log("✅ Новий день додано та збережено");
    } catch (err) {
      console.error("❌ Помилка при збереженні після додавання:", err);
    }
  }
}

async function removeDay(day) {
  settings.value.days = settings.value.days.filter(d => d !== day);
  try {
    await axios.put("/api/admin/settings/schedule", settings.value);
    console.log(`✅ День "${day}" видалено і збережено`);
  } catch (err) {
    console.error("❌ Помилка при збереженні після видалення:", err);
  }
}

onMounted(fetchSettings);
</script>

<template>
  <div>
    <h1 class="text-3xl font-bold text-blue-900 mb-6">
      ⚙️ Налаштування розкладу
    </h1>

    <div v-if="loading" class="text-gray-600">Завантаження...</div>

    <div
      v-else
      class="bg-white p-6 rounded-xl shadow-lg space-y-6 border border-blue-100"
    >
      <div>
        <h2 class="text-xl font-semibold mb-2 text-blue-700">
          Дні тижня
        </h2>

        <div class="flex flex-wrap gap-2 mb-3">
          <span
            v-for="day in settings.days"
            :key="day"
            class="bg-blue-100 px-3 py-1 rounded-md flex items-center gap-2 border border-blue-200"
          >
            {{ day }}
            <button
              @click="removeDay(day)"
              class="text-red-600 hover:text-red-800 font-bold"
              title="Видалити день"
            >
              ✕
            </button>
          </span>
        </div>

        <div class="flex gap-3">
          <input
            v-model="newDay"
            placeholder="Новий день (наприклад, Субота)"
            class="border rounded-lg px-3 py-2 w-full"
          />
          <button
            @click="addDay"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          >
            ➕ Додати день
          </button>
        </div>
      </div>

      <div>
        <h2 class="text-xl font-semibold mb-2 text-blue-700">
          Кількість пар на день
        </h2>
        <input
          v-model.number="settings.pair_count"
          type="number"
          min="1"
          max="12"
          class="border rounded-lg px-3 py-2 w-32"
        />
      </div>

      <div class="flex justify-end pt-4">
        <button
          @click="saveSettings"
          class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
        >
          💾 Зберегти зміни
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
button {
  transition: all 0.2s ease;
}
</style>
