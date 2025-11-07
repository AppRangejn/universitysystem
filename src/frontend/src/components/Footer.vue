<template>
  <footer class="bg-blue-800 text-white py-8 mt-auto relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 flex flex-col items-center space-y-6">

      <!-- 🔗 Верхній ряд — логотип і навігація -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full">
        <div class="flex items-center gap-3 mb-4 sm:mb-0 cursor-pointer select-none" @click="goHome">
          <img src="@/assets/logo.svg" alt="Логотип" class="w-10 h-10" />
          <span class="font-semibold text-lg">GitHub&nbsp;Університет</span>
        </div>

        <!-- 🔹 Навігація -->
        <nav class="flex flex-wrap justify-center gap-6 text-sm font-medium">
          <RouterLink
            to="/schedule"
            class="hover:text-blue-300 transition"
            :class="{ 'text-blue-300 font-semibold': $route.name === 'schedules' }"
          >
            Розклад
          </RouterLink>
          <RouterLink
            to="/profile"
            class="hover:text-blue-300 transition"
            :class="{ 'text-blue-300 font-semibold': $route.name === 'profile' }"
          >
            Особистий кабінет
          </RouterLink>
        </nav>

        <!-- 📞 Кнопка контактів -->
        <button
          @click="openContacts"
          class="hidden sm:block px-5 py-2 border border-white rounded-lg hover:bg-white hover:text-blue-800 transition"
        >
          Контакти
        </button>
      </div>

      <!-- 🌐 Соцмережі -->
      <div class="flex gap-5 text-xl">
        <a href="#" class="hover:text-blue-300 transition"><i class="fa-brands fa-telegram"></i></a>
        <a href="#" class="hover:text-blue-300 transition"><i class="fa-brands fa-facebook"></i></a>
        <a href="#" class="hover:text-blue-300 transition"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" class="hover:text-blue-300 transition"><i class="fa-brands fa-youtube"></i></a>
      </div>

      <!-- 🔸 Лінія -->
      <hr class="w-full border-white/30" />

      <!-- 🧾 Авторство -->
      <div class="text-xs text-white/70 text-center">
        © 2025 GitHub Університет — навчальна платформа. Усі права захищено.
      </div>

      <!-- ⬆ Кнопка "Вгору" -->
      <button
        @click="scrollToTop"
        class="fixed bottom-6 right-6 bg-orange-500 hover:bg-orange-600 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center text-xl transition-transform hover:scale-110"
      >
        ↑
      </button>
    </div>
  </footer>
</template>

<script setup>
import { useRouter } from "vue-router";
const router = useRouter();

const goHome = () => router.push("/");
const openContacts = () => alert("Контактна інформація буде додана пізніше.");
const scrollToTop = () => window.scrollTo({ top: 0, behavior: "smooth" });
</script>

<style scoped>
footer {
  font-family: "Inter", sans-serif;
}
</style>
