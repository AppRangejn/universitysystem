<template>
  <header
    :class="[
      'fixed top-0 left-0 w-full z-50 transition-all duration-300 backdrop-blur-sm',
      isScrolled
        ? 'bg-white/90 shadow-md text-blue-700'
        : 'bg-gradient-to-r from-blue-700 to-blue-600 text-white'
    ]"
  >
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-3">
      <!-- 🧠 Логотип -->
      <div class="flex items-center gap-3 cursor-pointer select-none" @click="goHome">
        <img src='@/assets/logo.svg' alt="Логотип" class="w-9 h-9" />
        <span class="font-bold text-lg tracking-wide hidden sm:inline">
          GitHub&nbsp;Університет
        </span>
      </div>

      <!-- ☰ Кнопка меню для мобільних -->
      <button
        @click="isMenuOpen = !isMenuOpen"
        class="sm:hidden text-2xl focus:outline-none"
      >
        <span v-if="!isMenuOpen">☰</span>
        <span v-else>✕</span>
      </button>

      <!-- 🌐 Навігація -->
      <nav
        class="hidden sm:flex items-center gap-6 font-medium"
      >
        <RouterLink
          to="/schedule"
          class="hover:text-blue-300 transition-colors duration-200"
          :class="{ 'text-blue-300 font-semibold': $route.name === 'schedule' }"
        >
          Розклад
        </RouterLink>

        <RouterLink
          to="/profile"
          class="hover:text-blue-300 transition-colors duration-200"
          :class="{ 'text-blue-300 font-semibold': $route.name === 'profile' }"
        >
          Особистий кабінет
        </RouterLink>

        <button
          @click="logout"
          class="ml-2 px-5 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 active:scale-95 text-white font-semibold shadow-sm transition-all duration-200"
        >
          Вийти
        </button>
      </nav>
    </div>

    <!-- 📱 Мобільне меню -->
    <transition name="slide-fade">
      <div
        v-if="isMenuOpen"
        class="sm:hidden flex flex-col items-center bg-blue-700/95 text-white py-6 space-y-4 shadow-lg"
      >
        <RouterLink
          to="/schedule"
          @click="closeMenu"
          class="text-lg hover:text-blue-300 transition-colors"
          :class="{ 'text-blue-300 font-semibold': $route.name === 'schedule' }"
        >
          Розклад
        </RouterLink>

        <RouterLink
          to="/profile"
          @click="closeMenu"
          class="text-lg hover:text-blue-300 transition-colors"
          :class="{ 'text-blue-300 font-semibold': $route.name === 'profile' }"
        >
          Особистий кабінет
        </RouterLink>

        <button
          @click="logout"
          class="px-6 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 active:scale-95 text-white font-semibold shadow-sm transition-all"
        >
          Вийти
        </button>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const isScrolled = ref(false);
const isMenuOpen = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};

onMounted(() => window.addEventListener("scroll", handleScroll));
onBeforeUnmount(() => window.removeEventListener("scroll", handleScroll));

const goHome = () => router.push("/");
const logout = () => {
  closeMenu();
  alert("Вихід із системи...");
};
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
