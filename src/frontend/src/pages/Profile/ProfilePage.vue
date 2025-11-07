<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-blue-100 py-10 px-4 md:px-10">
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl border border-blue-200 overflow-hidden">
      <!-- 🔹 Верхня панель -->
      <div class="bg-blue-700 text-white py-8 px-6 text-center border-b-4 border-blue-500">
        <h1 class="text-4xl font-extrabold uppercase tracking-wide drop-shadow-sm">
          Вітаємо, {{ fullName || "Студент" }}!
        </h1>
        <p class="text-blue-100 text-lg mt-2">
          Ви увійшли до власного електронного кабінету 🎓
        </p>
      </div>

      <!-- 🔄 Завантаження -->
      <div v-if="loading" class="text-center py-16 text-gray-500 text-lg">
        Завантаження профілю...
      </div>

      <div v-else class="p-8 space-y-10">
        <!-- 🧑‍🎓 Фото та базова інформація -->
        <div class="flex flex-col md:flex-row items-center gap-8">
          <div class="relative">
            <img
              v-if="user.photo"
              :src="photoUrl"
              alt="Фото користувача"
              class="w-36 h-36 rounded-full object-cover border-4 border-blue-300 shadow-lg"
            />
            <div
              v-else
              class="w-36 h-36 flex items-center justify-center rounded-full bg-blue-100 text-blue-800 text-5xl font-bold border border-blue-300 shadow-md"
            >
              {{ initials }}
            </div>
          </div>

          <div class="flex-1 w-full">
            <table class="w-full border border-blue-100 rounded-xl overflow-hidden">
              <tbody>
              <tr class="border-b border-blue-100">
                <td class="bg-blue-50 px-4 py-3 font-semibold text-blue-900 w-1/3">ПІБ</td>
                <td class="px-4 py-3">{{ fullName }}</td>
              </tr>
              <tr class="border-b border-blue-100">
                <td class="bg-blue-50 px-4 py-3 font-semibold text-blue-900">Email</td>
                <td class="px-4 py-3">{{ user.email }}</td>
              </tr>
              <tr v-if="user.phone" class="border-b border-blue-100">
                <td class="bg-blue-50 px-4 py-3 font-semibold text-blue-900">Телефон</td>
                <td class="px-4 py-3">{{ user.phone }}</td>
              </tr>
              <tr class="border-b border-blue-100">
                <td class="bg-blue-50 px-4 py-3 font-semibold text-blue-900">Приналежність до групи</td>
                <td class="px-4 py-3">
                    <span
                      v-if="user.group"
                      class="font-semibold text-blue-700 bg-blue-50 px-3 py-1 rounded-md border border-blue-200"
                    >
                      {{ user.group.name }}
                    </span>
                  <span v-else class="italic text-gray-500">Без групи</span>
                </td>
              </tr>
              <tr>
                <td class="bg-blue-50 px-4 py-3 font-semibold text-blue-900">Дата створення</td>
                <td class="px-4 py-3">{{ formatDate(user.created_at) }}</td>
              </tr>
              </tbody>
            </table>

            <div class="text-right mt-5">
              <button
                @click="goToSchedule"
                :disabled="!user.group"
                class="px-7 py-2.5 rounded-md font-semibold shadow-sm transition-all duration-200"
                :class="user.group
                  ? 'bg-blue-600 hover:bg-blue-700 text-white'
                  : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
              >
                📅 Переглянути розклад
              </button>
            </div>
          </div>
        </div>

        <!-- ✏️ Редагування профілю -->
        <section class="pt-6 border-t border-blue-200">
          <h2 class="text-2xl font-bold text-blue-800 border-l-4 border-blue-500 pl-3 mb-6">
            Редагування профілю
          </h2>

          <form @submit.prevent="updateProfile" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Прізвище</label>
              <input v-model="form.surname" class="input" type="text" />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Ім’я</label>
              <input v-model="form.name" class="input" type="text" />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">По батькові</label>
              <input v-model="form.patronymic" class="input" type="text" />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Email</label>
              <input v-model="form.email" class="input" type="email" />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Телефон</label>
              <input v-model="form.phone" class="input" type="text" />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Фото профілю</label>
              <input @change="onFileChange" class="input" type="file" />
            </div>

            <div class="col-span-2 text-right">
              <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-10 py-2.5 rounded-md font-semibold shadow-md transition"
              >
                💾 Зберегти зміни
              </button>
            </div>
          </form>
        </section>

        <!-- 🔐 Зміна пароля -->
        <section class="pt-6 border-t border-blue-200">
          <h2 class="text-2xl font-bold text-blue-800 border-l-4 border-blue-500 pl-3 mb-6">
            Зміна пароля
          </h2>

          <form @submit.prevent="changePassword" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Поточний пароль</label>
              <input
                v-model="passwordForm.current_password"
                class="input"
                type="password"
                placeholder="Введіть поточний"
              />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Новий пароль</label>
              <input
                v-model="passwordForm.new_password"
                class="input"
                type="password"
                placeholder="Новий пароль"
              />
            </div>
            <div>
              <label class="font-semibold text-blue-800 mb-1 block">Підтвердження</label>
              <input
                v-model="passwordForm.new_password_confirmation"
                class="input"
                type="password"
                placeholder="Підтвердьте пароль"
              />
            </div>

            <div class="col-span-3 text-right">
              <button
                type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-10 py-2.5 rounded-md font-semibold shadow-md transition"
              >
                🔑 Оновити пароль
              </button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const user = ref({});
const loading = ref(true);

const form = ref({});
const passwordForm = ref({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
});
const photoFile = ref(null);

const fullName = computed(() =>
  [user.value.surname, user.value.name, user.value.patronymic].filter(Boolean).join(" ")
);
const initials = computed(() =>
  (user.value.surname?.[0] || "") + (user.value.name?.[0] || "")
);
const photoUrl = computed(() =>
  user.value.photo ? `/storage/${user.value.photo.replace(/^public\//, '')}` : ""
);


// ✅ отримуємо користувача разом із групою
const fetchUser = async () => {
  try {
    const res = await axios.get("/api/user");
    user.value = res.data;
    form.value = { ...res.data };
  } catch (err) {
    console.error("Помилка при завантаженні профілю:", err);
  } finally {
    loading.value = false;
  }
};

// ✅ оновлення профілю з гнучкою логікою
const updateProfile = async () => {
  const fd = new FormData();

  // Додаємо тільки ті поля, які реально заповнені / змінені
  for (const [key, value] of Object.entries(form.value)) {
    if (value !== null && value !== undefined && value !== "") {
      fd.append(key, value);
    }
  }

  // Якщо вибрали нове фото — додаємо
  if (photoFile.value) {
    fd.append("photo", photoFile.value);
  }

  try {
    const res = await axios.post("/api/user/profile?_method=PUT", fd, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    user.value = res.data.user; // бек повертає одразу з group
    alert("✅ Профіль успішно оновлено!");
  } catch (err) {
    console.error("Помилка при оновленні профілю:", err);
    alert("❌ Помилка при збереженні профілю");
  }
};

// ✅ зміна пароля
const changePassword = async () => {
  await axios.post("/api/user/change-password", passwordForm.value);
  alert("🔑 Пароль змінено!");
  passwordForm.value = { current_password: "", new_password: "", new_password_confirmation: "" };
};

// ✅ обробка фото
const onFileChange = (e) => {
  photoFile.value = e.target.files[0];
};

// ✅ перехід на розклад групи
const goToSchedule = () => {
  if (user.value.group) {
    router.push(`/schedule/group/${user.value.group.id}`);
  } else {
    alert("❗ Користувач не прив’язаний до жодної групи.");
  }
};

const formatDate = (dateStr) => {
  const d = new Date(dateStr);
  return d.toLocaleDateString("uk-UA", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

onMounted(fetchUser);
</script>


<style scoped>
.input {
  border: 1px solid #cfe0f9;
  border-radius: 8px;
  padding: 10px 12px;
  width: 100%;
  color: #1e3a8a;
  background-color: #f9fbff;
  transition: all 0.2s;
}
.input:focus {
  outline: none;
  border-color: #3b82f6;
  background-color: #fff;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}
</style>
