<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

const schedules = ref([]);
const groups = ref([]);
const settings = ref({ days: [], pair_count: 6 });
const loading = ref(true);
const showForm = ref(false);
const editingSchedule = ref(null);

const form = reactive({
  group_id: "",
  day: "",
  pair_number: "",
  time: "",
  subject: "",
  room: "",
  teacher: "",
  week: "",
});


async function fetchSchedules() {
  loading.value = true;
  try {
    const res = await axios.get("/api/admin/schedules");
    schedules.value = res.data;
  } catch (err) {
    console.error("❌ Помилка при отриманні розкладів:", err);
  } finally {
    loading.value = false;
  }
}


async function fetchGroups() {
  try {
    const res = await axios.get("/api/groups");
    groups.value = res.data;
  } catch (err) {
    console.error("❌ Помилка при отриманні груп:", err);
  }
}


async function fetchSettings() {
  try {
    const res = await axios.get("/api/admin/settings/schedule");
    settings.value = res.data;
  } catch (err) {
    console.error("❌ Помилка при отриманні налаштувань розкладу:", err);
    settings.value = {
      days: ["Понеділок", "Вівторок", "Середа", "Четвер", "П’ятниця"],
      pair_count: 6,
    };
  }
}

function toggleForm(schedule = null) {
  if (schedule) {
    editingSchedule.value = schedule;
    Object.assign(form, schedule);
  } else {
    editingSchedule.value = null;
    Object.keys(form).forEach((k) => (form[k] = ""));
  }
  showForm.value = !showForm.value;
}


async function createSchedule() {
  try {
    await axios.post("/api/admin/schedules", form);
    await fetchSchedules();
    toggleForm();
    alert("✅ Розклад додано!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при створенні розкладу");
  }
}


async function updateSchedule() {
  try {
    await axios.put(`/api/admin/schedules/${editingSchedule.value.id}`, form);
    await fetchSchedules();
    toggleForm();
    alert("✅ Розклад оновлено!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при оновленні розкладу");
  }
}


async function deleteSchedule(id) {
  if (!confirm("Ви впевнені, що хочете видалити цей розклад?")) return;
  try {
    await axios.delete(`/api/admin/schedules/${id}`);
    await fetchSchedules();
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при видаленні розкладу");
  }
}

onMounted(async () => {
  await Promise.all([fetchGroups(), fetchSchedules(), fetchSettings()]);
});
</script>

<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-blue-900">🗓️ Розклади</h1>
      <button
        @click="toggleForm()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
      >
        {{ showForm ? "❌ Закрити форму" : "➕ Додати розклад" }}
      </button>
    </div>

    <div v-if="loading" class="text-gray-600">Завантаження...</div>

    <table
      v-else
      class="w-full border border-gray-300 shadow-md bg-white rounded-lg overflow-hidden"
    >
      <thead>
      <tr class="bg-blue-50 text-blue-800">
        <th class="border p-3">ID</th>
        <th class="border p-3">Група</th>
        <th class="border p-3">День</th>
        <th class="border p-3">№ пари</th>
        <th class="border p-3">Час</th>
        <th class="border p-3">Предмет</th>
        <th class="border p-3">Аудиторія</th>
        <th class="border p-3">Викладач</th>
        <th class="border p-3">Тиждень</th>
        <th class="border p-3 text-center">Дії</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="s in schedules" :key="s.id" class="hover:bg-blue-50">
        <td class="border p-3">{{ s.id }}</td>
        <td class="border p-3">{{ s.group?.name || "—" }}</td>
        <td class="border p-3">{{ s.day }}</td>
        <td class="border p-3">{{ s.pair_number || "—" }}</td>
        <td class="border p-3">{{ s.time }}</td>
        <td class="border p-3">{{ s.subject }}</td>
        <td class="border p-3">{{ s.room || "—" }}</td>
        <td class="border p-3">{{ s.teacher || "—" }}</td>
        <td class="border p-3">{{ s.week || "—" }}</td>
        <td class="border p-3 text-center space-x-3">
          <button @click="toggleForm(s)" class="text-blue-600 hover:underline">
            ✏️
          </button>
          <button @click="deleteSchedule(s.id)" class="text-red-600 hover:underline">
            🗑️
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <transition name="fade">
      <div
        v-if="showForm"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50"
      >
        <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-2xl">
          <h2 class="text-2xl font-semibold mb-4 text-blue-800">
            {{ editingSchedule ? "✏️ Редагування розкладу" : "➕ Новий розклад" }}
          </h2>

          <form
            @submit.prevent="editingSchedule ? updateSchedule() : createSchedule()"
            class="grid grid-cols-1 md:grid-cols-2 gap-4"
          >
            <select
              v-model="form.group_id"
              required
              class="border rounded-lg px-3 py-2 w-full"
            >
              <option disabled value="">Оберіть групу</option>
              <option v-for="g in groups" :value="g.id" :key="g.id">
                {{ g.name }}
              </option>
            </select>

            <select
              v-model="form.day"
              required
              class="border rounded-lg px-3 py-2 w-full"
            >
              <option disabled value="">Оберіть день</option>
              <option v-for="day in settings.days" :value="day" :key="day">
                {{ day }}
              </option>
            </select>

            <select
              v-model="form.pair_number"
              required
              class="border rounded-lg px-3 py-2 w-full"
            >
              <option disabled value="">Оберіть № пари</option>
              <option v-for="n in settings.pair_count" :key="n" :value="n">
                {{ n }}
              </option>
            </select>

            <input
              v-model="form.time"
              type="text"
              placeholder="Час (08:30 - 10:00)"
              required
              class="border rounded-lg px-3 py-2 w-full"
            />

            <input
              v-model="form.subject"
              type="text"
              placeholder="Предмет"
              required
              class="border rounded-lg px-3 py-2 w-full"
            />

            <input
              v-model="form.room"
              type="text"
              placeholder="Аудиторія"
              class="border rounded-lg px-3 py-2 w-full"
            />

            <input
              v-model="form.teacher"
              type="text"
              placeholder="Викладач"
              class="border rounded-lg px-3 py-2 w-full"
            />

            <input
              v-model="form.week"
              type="text"
              placeholder="Тиждень (парний/непарний)"
              class="border rounded-lg px-3 py-2 w-full"
            />

            <div class="col-span-2 flex justify-end space-x-3 pt-3">
              <button
                type="button"
                @click="toggleForm()"
                class="px-4 py-2 border rounded-lg hover:bg-gray-100"
              >
                Скасувати
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              >
                {{ editingSchedule ? "💾 Оновити" : "✅ Створити" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
