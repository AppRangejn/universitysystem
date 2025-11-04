<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

const courses = ref([]);
const faculties = ref([]);
const loading = ref(true);
const showForm = ref(false);
const editingCourse = ref(null);

const form = reactive({
  name: "",
  faculty_id: "",
});

// 📋 Отримати всі курси
async function fetchCourses() {
  loading.value = true;
  try {
    const res = await axios.get("/api/admin/courses");
    courses.value = res.data;
  } catch (err) {
    console.error("Помилка при отриманні курсів:", err);
  } finally {
    loading.value = false;
  }
}

// 🎓 Отримати факультети
async function fetchFaculties() {
  try {
    const res = await axios.get("/api/faculties");
    faculties.value = res.data;
  } catch (err) {
    console.error("Помилка при отриманні факультетів:", err);
  }
}

// 🔄 Відкрити / закрити форму
function toggleForm(course = null) {
  if (course) {
    editingCourse.value = course;
    form.name = course.name;
    form.faculty_id = course.faculty_id;
  } else {
    editingCourse.value = null;
    form.name = "";
    form.faculty_id = "";
  }
  showForm.value = !showForm.value;
}

// ➕ Додати курс
async function createCourse() {
  try {
    await axios.post("/api/admin/courses", form);
    await fetchCourses();
    toggleForm();
    alert("✅ Курс додано!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при створенні курсу");
  }
}

// ✏️ Оновити курс
async function updateCourse() {
  try {
    await axios.put(`/api/admin/courses/${editingCourse.value.id}`, form);
    await fetchCourses();
    toggleForm();
    alert("✅ Курс оновлено!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при оновленні курсу");
  }
}

// 🗑️ Видалити курс
async function deleteCourse(id) {
  if (!confirm("Ви впевнені, що хочете видалити цей курс?")) return;
  try {
    await axios.delete(`/api/admin/courses/${id}`);
    await fetchCourses();
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при видаленні курсу");
  }
}

onMounted(async () => {
  await fetchFaculties();
  await fetchCourses();
});
</script>

<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-blue-900">📘 Курси</h1>
      <button
        @click="toggleForm()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
      >
        {{ showForm ? "❌ Закрити форму" : "➕ Додати курс" }}
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
        <th class="border p-3">Назва курсу</th>
        <th class="border p-3">Факультет</th>
        <th class="border p-3 text-center">Дії</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="course in courses" :key="course.id" class="hover:bg-blue-50">
        <td class="border p-3">{{ course.id }}</td>
        <td class="border p-3">{{ course.name }}</td>
        <td class="border p-3">{{ course.faculty?.name || "—" }}</td>
        <td class="border p-3 text-center space-x-3">
          <button
            @click="toggleForm(course)"
            class="text-blue-600 hover:underline"
          >
            ✏️
          </button>
          <button
            @click="deleteCourse(course.id)"
            class="text-red-600 hover:underline"
          >
            🗑️
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- 🧾 Форма додавання / редагування -->
    <transition name="fade">
      <div
        v-if="showForm"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50"
      >
        <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg">
          <h2 class="text-2xl font-semibold mb-4 text-blue-800">
            {{ editingCourse ? "✏️ Редагування курсу" : "➕ Новий курс" }}
          </h2>

          <form
            @submit.prevent="editingCourse ? updateCourse() : createCourse()"
            class="space-y-4"
          >
            <input
              v-model="form.name"
              type="text"
              placeholder="Назва курсу (1, 2, 3 або 4 курс)"
              required
              class="border rounded-lg px-3 py-2 w-full"
            />

            <select
              v-model="form.faculty_id"
              required
              class="border rounded-lg px-3 py-2 w-full"
            >
              <option disabled value="">Оберіть факультет</option>
              <option v-for="f in faculties" :value="f.id" :key="f.id">
                {{ f.name }}
              </option>
            </select>

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
                {{ editingCourse ? "💾 Оновити" : "✅ Створити" }}
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
