<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

const faculties = ref([]);
const loading = ref(true);
const showForm = ref(false);
const editingFaculty = ref(null);

const form = reactive({
  name: "",
});

// 📋 Отримати всі факультети
async function fetchFaculties() {
  loading.value = true;
  try {
    const res = await axios.get("/api/admin/faculties");
    faculties.value = res.data;
  } catch (err) {
    console.error("Помилка при отриманні факультетів:", err);
  } finally {
    loading.value = false;
  }
}

// 🔄 Відкрити / закрити форму
function toggleForm(faculty = null) {
  if (faculty) {
    editingFaculty.value = faculty;
    form.name = faculty.name;
  } else {
    editingFaculty.value = null;
    form.name = "";
  }
  showForm.value = !showForm.value;
}

// ➕ Додати факультет
async function createFaculty() {
  try {
    await axios.post("/api/admin/faculties", form);
    await fetchFaculties();
    toggleForm();
    alert("✅ Факультет створено!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при створенні факультету");
  }
}

// ✏️ Оновити факультет
async function updateFaculty() {
  try {
    await axios.put(`/api/admin/faculties/${editingFaculty.value.id}`, form);
    await fetchFaculties();
    toggleForm();
    alert("✅ Факультет оновлено!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при оновленні факультету");
  }
}

// 🗑️ Видалити факультет
async function deleteFaculty(id) {
  if (!confirm("Ви впевнені, що хочете видалити цей факультет?")) return;
  try {
    await axios.delete(`/api/admin/faculties/${id}`);
    await fetchFaculties();
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при видаленні факультету");
  }
}

onMounted(fetchFaculties);
</script>

<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-blue-900">🏛️ Факультети</h1>
      <button
        @click="toggleForm()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
      >
        {{ showForm ? "❌ Закрити форму" : "➕ Додати факультет" }}
      </button>
    </div>

    <div v-if="loading">Завантаження...</div>

    <table
      v-else
      class="w-full border border-gray-300 shadow-md bg-white rounded-lg overflow-hidden"
    >
      <thead>
      <tr class="bg-blue-50 text-blue-800">
        <th class="border p-3">ID</th>
        <th class="border p-3">Назва факультету</th>
        <th class="border p-3 text-center">Дії</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="faculty in faculties" :key="faculty.id" class="hover:bg-blue-50">
        <td class="border p-3">{{ faculty.id }}</td>
        <td class="border p-3">{{ faculty.name }}</td>
        <td class="border p-3 text-center space-x-3">
          <button
            @click="toggleForm(faculty)"
            class="text-blue-600 hover:underline"
          >
            ✏️
          </button>
          <button
            @click="deleteFaculty(faculty.id)"
            class="text-red-600 hover:underline"
          >
            🗑️
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- 🧾 Модальне вікно -->
    <transition name="fade">
      <div
        v-if="showForm"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50"
      >
        <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg">
          <h2 class="text-2xl font-semibold mb-4 text-blue-800">
            {{ editingFaculty ? "✏️ Редагування факультету" : "➕ Новий факультет" }}
          </h2>

          <form
            @submit.prevent="editingFaculty ? updateFaculty() : createFaculty()"
            class="space-y-4"
          >
            <input
              v-model="form.name"
              type="text"
              placeholder="Назва факультету"
              required
              class="border rounded-lg px-3 py-2 w-full"
            />

            <div class="flex justify-end space-x-3 pt-3">
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
                {{ editingFaculty ? "💾 Оновити" : "✅ Створити" }}
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
