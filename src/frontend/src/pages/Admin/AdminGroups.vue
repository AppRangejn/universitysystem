<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

const groups = ref([]);
const courses = ref([]);
const showForm = ref(false);
const loading = ref(true);
const editingGroup = ref(null);

const form = reactive({
  name: "",
  course_id: "",
});

// 🧠 Отримати всі групи
async function fetchGroups() {
  loading.value = true;
  try {
    const res = await axios.get("/api/admin/groups");
    groups.value = res.data;
  } catch (err) {
    console.error("Помилка при отриманні груп:", err);
  } finally {
    loading.value = false;
  }
}

// 🎓 Отримати курси для select
async function fetchCourses() {
  try {
    const res = await axios.get("/api/courses");
    courses.value = res.data;
  } catch (err) {
    console.error("Помилка при отриманні курсів:", err);
  }
}

// 🧾 Відкрити/закрити форму
function toggleForm(group = null) {
  if (group) {
    editingGroup.value = group;
    form.name = group.name;
    form.course_id = group.course_id;
  } else {
    editingGroup.value = null;
    form.name = "";
    form.course_id = "";
  }
  showForm.value = !showForm.value;
}

// ➕ Додати
async function createGroup() {
  try {
    await axios.post("/api/admin/groups", form);
    await fetchGroups();
    toggleForm();
    alert("✅ Групу додано!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при створенні групи");
  }
}

// ✏️ Оновити
async function updateGroup() {
  try {
    await axios.put(`/api/admin/groups/${editingGroup.value.id}`, form);
    await fetchGroups();
    toggleForm();
    alert("✅ Групу оновлено!");
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при оновленні групи");
  }
}

// 🗑️ Видалити
async function deleteGroup(id) {
  if (!confirm("Ви впевнені, що хочете видалити цю групу?")) return;
  try {
    await axios.delete(`/api/admin/groups/${id}`);
    await fetchGroups();
  } catch (err) {
    console.error(err);
    alert("❌ Помилка при видаленні");
  }
}

onMounted(async () => {
  await fetchCourses();
  await fetchGroups();
});
</script>

<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-blue-900">🏫 Групи</h1>
      <button
        @click="toggleForm()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
      >
        {{ showForm ? "❌ Закрити форму" : "➕ Додати групу" }}
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
        <th class="border p-3">Назва групи</th>
        <th class="border p-3">Курс</th>
        <th class="border p-3 text-center">Дії</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="group in groups" :key="group.id" class="hover:bg-blue-50">
        <td class="border p-3">{{ group.id }}</td>
        <td class="border p-3">{{ group.name }}</td>
        <td class="border p-3">{{ group.course?.name || "—" }}</td>
        <td class="border p-3 text-center space-x-3">
          <button
            @click="toggleForm(group)"
            class="text-blue-600 hover:underline"
          >
            ✏️
          </button>
          <button
            @click="deleteGroup(group.id)"
            class="text-red-600 hover:underline"
          >
            🗑️
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- 🧩 Модалка -->
    <transition name="fade">
      <div
        v-if="showForm"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm flex justify-center items-center z-50"
      >
        <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-lg">
          <h2 class="text-2xl font-semibold mb-4 text-blue-800">
            {{ editingGroup ? "✏️ Редагування групи" : "➕ Нова група" }}
          </h2>

          <form
            @submit.prevent="editingGroup ? updateGroup() : createGroup()"
            class="space-y-4"
          >
            <input
              v-model="form.name"
              type="text"
              placeholder="Назва групи"
              required
              class="border rounded-lg px-3 py-2 w-full"
            />

            <select
              v-model="form.course_id"
              required
              class="border rounded-lg px-3 py-2 w-full"
            >
              <option disabled value="">Оберіть курс</option>
              <option v-for="c in courses" :value="c.id" :key="c.id">
                {{ c.name }}
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
                {{ editingGroup ? "💾 Оновити" : "✅ Створити" }}
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
