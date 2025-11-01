<template>
  <div
    class="min-h-screen bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 text-white flex flex-col items-center py-12 px-4 md:px-8"
  >
    <!-- 🧑‍🎓 Заголовок -->
    <div class="text-center mb-10">
      <h1 class="text-4xl font-extrabold mb-3">
        Вітаємо, {{ userFullName }}!
      </h1>
      <p class="text-blue-100 text-lg">
        Ви увійшли до власного електронного кабінету 🎓
      </p>
    </div>

    <!-- 📸 Фото профілю -->
    <div class="flex flex-col items-center mb-10">
      <img
        v-if="user.photo"
        :src="photoUrl"
        alt="Фото користувача"
        class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-white/30"
      />
      <div
        v-else
        class="w-32 h-32 flex items-center justify-center rounded-full bg-white/10 text-4xl font-bold border border-white/30"
      >
        {{ userInitials }}
      </div>
      <p class="mt-3 text-white/70 text-sm">
        {{ user.role === 'student'
        ? 'Студент'
        : user.role === 'teacher'
          ? 'Викладач'
          : user.role === 'admin'
            ? 'Адміністратор'
            : 'Гість' }}
      </p>
    </div>

    <!-- 📘 Основна інформація -->
    <div
      class="w-full max-w-4xl bg-white/10 backdrop-blur-2xl rounded-3xl shadow-lg border border-white/20 p-8"
    >
      <h2 class="text-2xl font-bold mb-6 border-b border-white/30 pb-3">
        Інформація профілю
      </h2>

      <table class="w-full text-left text-white/90 border-collapse">
        <tbody>
        <tr>
          <td class="py-3 font-semibold w-1/3">ПІБ</td>
          <td>{{ userFullName }}</td>
        </tr>
        <tr>
          <td class="py-3 font-semibold">Email</td>
          <td>{{ user.email }}</td>
        </tr>
        <tr v-if="user.phone">
          <td class="py-3 font-semibold">Телефон</td>
          <td>{{ user.phone }}</td>
        </tr>
        <tr>
          <td class="py-3 font-semibold">Роль</td>
          <td>
              <span
                :class="{
                  'bg-green-600/80': user.role === 'student',
                  'bg-yellow-600/80': user.role === 'teacher',
                  'bg-red-600/80': user.role === 'admin',
                  'bg-gray-500/70': user.role === 'guest',
                }"
                class="px-3 py-1 rounded-md text-sm uppercase font-semibold"
              >
                {{ user.role }}
              </span>
          </td>
        </tr>
        <tr>
          <td class="py-3 font-semibold">Дата створення акаунта</td>
          <td>{{ formatDate(user.created_at) }}</td>
        </tr>
        <tr v-if="user.updated_at !== user.created_at">
          <td class="py-3 font-semibold">Останнє оновлення</td>
          <td>{{ formatDate(user.updated_at) }}</td>
        </tr>
        </tbody>
      </table>

      <!-- 🔘 Кнопка виходу -->
      <div class="text-center mt-8">
        <button
          @click="logout"
          class="px-8 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-semibold shadow-md transition"
        >
          Вийти з акаунта
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useAuthStore } from "@/stores/useAuth";
import { useRouter } from "vue-router";

const auth = useAuthStore();
const router = useRouter();

const user = computed(() => auth.user || {});

// 🧾 Повне ім’я
const userFullName = computed(() => {
  const parts = [user.value.surname, user.value.name, user.value.patronymic];
  return parts.filter(Boolean).join(" ");
});

// 🖼️ Фото або ініціали
const userInitials = computed(() => {
  const s = user.value.surname?.[0] || "";
  const n = user.value.name?.[0] || "";
  return (s + n).toUpperCase();
});

const photoUrl = computed(() => {
  if (!user.value.photo) return null;
  return user.value.photo.startsWith("http")
    ? user.value.photo
    : `/storage/${user.value.photo}`;
});

const formatDate = (dateStr) => {
  if (!dateStr) return "—";
  const d = new Date(dateStr);
  return d.toLocaleString("uk-UA", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const logout = async () => {
  await auth.logout();
  router.push("/login");
};
</script>

<style scoped>
table td {
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}
</style>
