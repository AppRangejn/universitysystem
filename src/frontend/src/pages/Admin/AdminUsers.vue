<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

const users = ref([]);
const loading = ref(true);
const showForm = ref(false);
const editingUser = ref(null);
const form = reactive({
  name: "",
  surname: "",
  email: "",
  password: "",
  role: "student",
});

async function fetchUsers() {
  loading.value = true;
  try {
    const res = await axios.get("/api/admin/users");
    users.value = res.data;
  } catch (err) {
    console.error("❌ Помилка при отриманні користувачів:", err);
  } finally {
    loading.value = false;
  }
}

function toggleForm(user = null) {
  if (user) {
    editingUser.value = user;
    Object.assign(form, {
      name: user.name,
      surname: user.surname,
      email: user.email,
      password: "",
      role: user.role,
    });
  } else {
    editingUser.value = null;
    Object.assign(form, {
      name: "",
      surname: "",
      email: "",
      password: "",
      role: "student",
    });
  }
  showForm.value = !showForm.value;
}

// ➕ Створення
async function createUser() {
  try {
    await axios.post("/api/admin/users", form);
    await fetchUsers();
    toggleForm();
    alert("✅ Користувача додано!");
  } catch (err) {
    console.error("Помилка створення:", err);
    alert("❌ Помилка створення користувача");
  }
}

// ✏️ Оновлення
async function updateUser() {
  try {
    await axios.put(`/api/admin/users/${editingUser.value.id}`, form);
    await fetchUsers();
    toggleForm();
    alert("✅ Користувача оновлено!");
  } catch (err) {
    console.error("Помилка оновлення:", err);
    alert("❌ Помилка оновлення користувача");
  }
}

// 🗑️ Видалення
async function deleteUser(id) {
  if (!confirm("Ви впевнені, що хочете видалити користувача?")) return;
  try {
    await axios.delete(`/api/admin/users/${id}`);
    await fetchUsers();
  } catch (err) {
    console.error("Помилка видалення:", err);
    alert("❌ Помилка видалення користувача");
  }
}

onMounted(fetchUsers);
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold text-blue-900">👥 Користувачі</h1>
      <button
        @click="toggleForm()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all"
      >
        {{ showForm ? "❌ Закрити форму" : "➕ Додати користувача" }}
      </button>
    </div>

    <!-- 📋 Таблиця користувачів -->
    <div v-if="loading">Завантаження...</div>

    <table
      v-else
      class="w-full border-collapse border border-gray-300 shadow-sm bg-white rounded-lg overflow-hidden"
    >
      <thead>
      <tr class="bg-blue-50 text-blue-800 text-left">
        <th class="border p-3">ID</th>
        <th class="border p-3">Ім’я</th>
        <th class="border p-3">Email</th>
        <th class="border p-3">Роль</th>
        <th class="border p-3 text-center">Дії</th>
      </tr>
      </thead>
      <tbody>
      <tr
        v-for="user in users"
        :key="user.id"
        class="hover:bg-blue-50 transition"
      >
        <td class="border p-3">{{ user.id }}</td>
        <td class="border p-3">{{ user.name }} {{ user.surname }}</td>
        <td class="border p-3">{{ user.email }}</td>
        <td class="border p-3">{{ user.role }}</td>
        <td class="border p-3 text-center space-x-2">
          <button
            @click="toggleForm(user)"
            class="text-blue-600 hover:underline"
          >
            ✏️
          </button>
          <button
            @click="deleteUser(user.id)"
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
        <div
          class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 border border-blue-100"
        >
          <h2 class="text-2xl font-semibold text-blue-800 mb-4">
            {{ editingUser ? "✏️ Редагувати користувача" : "➕ Новий користувач" }}
          </h2>

          <form
            @submit.prevent="editingUser ? updateUser() : createUser()"
            class="space-y-4"
          >
            <div class="grid grid-cols-2 gap-4">
              <input
                v-model="form.name"
                type="text"
                placeholder="Ім’я"
                required
                class="border rounded-lg px-3 py-2 w-full"
              />
              <input
                v-model="form.surname"
                type="text"
                placeholder="Прізвище"
                required
                class="border rounded-lg px-3 py-2 w-full"
              />
            </div>

            <input
              v-model="form.email"
              type="email"
              placeholder="Email"
              required
              class="border rounded-lg px-3 py-2 w-full"
            />

            <input
              v-model="form.password"
              type="password"
              placeholder="Пароль"
              :required="!editingUser"
              class="border rounded-lg px-3 py-2 w-full"
            />

            <select
              v-model="form.role"
              class="border rounded-lg px-3 py-2 w-full"
            >
              <option value="admin">Адмін</option>
              <option value="teacher">Викладач</option>
              <option value="student">Студент</option>
              <option value="guest">Гість</option>
            </select>

            <div class="flex justify-end space-x-3 pt-3">
              <button
                type="button"
                @click="toggleForm()"
                class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100"
              >
                Скасувати
              </button>
              <button
                type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
              >
                {{ editingUser ? "💾 Оновити" : "✅ Створити" }}
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
