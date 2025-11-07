<template>
  <div class="flex justify-center items-center min-h-screen bg-gradient-to-br from-blue-700 to-blue-500">
    <div
      class="bg-white/10 backdrop-blur-xl p-8 rounded-2xl shadow-2xl w-full max-w-md text-center text-white border border-white/20"
    >
      <img
        src="@/assets/logo.svg"
        alt="Логотип"
        class="w-16 h-16 mx-auto mb-4 cursor-pointer hover:scale-110 transition-transform duration-300"
        @click="goHome"
      />

      <h2 class="text-3xl font-bold mb-6">🔐 Вхід</h2>

      <form @submit.prevent="submitLogin" class="space-y-4">
        <input
          v-model="form.email"
          type="email"
          placeholder="Email"
          class="w-full px-4 py-2 rounded-lg bg-white/20 focus:bg-white/30 outline-none text-white placeholder-white/70"
          required
        />
        <input
          v-model="form.password"
          type="password"
          placeholder="Пароль"
          class="w-full px-4 py-2 rounded-lg bg-white/20 focus:bg-white/30 outline-none text-white placeholder-white/70"
          required
        />

        <button
          type="submit"
          class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition disabled:opacity-50"
          :disabled="auth.loading"
        >
          {{ auth.loading ? "⏳ Вхід..." : "Увійти" }}
        </button>
      </form>

      <p v-if="auth.error" class="text-red-300 mt-3">{{ auth.error }}</p>

      <p class="mt-6 text-sm">
        Немає акаунта?
        <RouterLink to="/register" class="text-yellow-300 hover:underline">
          Зареєструватися
        </RouterLink>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/useAuth";

const router = useRouter();
const auth = useAuthStore();

const form = reactive({
  email: "",
  password: "",
});

const submitLogin = async () => {
  await auth.login(form);
};

watch(
  () => auth.user,
  (user) => {
    if (user) router.push("/profile");
  }
);

const goHome = () => router.push("/");
</script>
