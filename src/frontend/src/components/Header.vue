<template>
  <header
    :class="[
      'fixed top-0 left-0 w-full z-50 transition-all duration-300 backdrop-blur-sm',
      isScrolled
        ? 'bg-white/90 shadow-md text-blue-700'
        : 'bg-gradient-to-r from-blue-700 to-blue-600 text-white'
    ]"
  >
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-3 transition-all duration-300">
      <!-- 🧠 Логотип -->
      <div
        class="flex items-center gap-3 cursor-pointer select-none transition-all duration-300"
        @click="goHome"
      >
        <img
          :src="isScrolled ? darkLogo : lightLogo"
          alt="Логотип"
          class="w-9 h-9 transition-all duration-300"
        />
        <span
          class="font-bold text-lg tracking-wide hidden sm:inline transition-all duration-300"
          :class="isScrolled ? 'text-blue-700' : 'text-white'"
        >
          GitHub&nbsp;Університет
        </span>
      </div>

      <!-- ☰ Кнопка меню для мобільних -->
      <button
        @click="isMenuOpen = !isMenuOpen"
        class="sm:hidden text-3xl focus:outline-none transition-all duration-300"
        :class="isScrolled ? 'text-blue-700' : 'text-white'"
      >
        <span v-if="!isMenuOpen">☰</span>
        <span v-else>✕</span>
      </button>

      <!-- 🌐 Навігація -->
      <nav class="hidden sm:flex items-center gap-8 font-semibold transition-all duration-300">
        <!-- 🔹 Розклад -->
        <RouterLink
          to="/schedule"
          class="transition-all duration-300"
          :class="linkClasses('schedule')"
        >
          Розклад
        </RouterLink>

        <!-- 🔹 Особистий кабінет або Гостьовий доступ -->
        <RouterLink
          v-if="auth.user"
          to="/profile"
          class="transition-all duration-300"
          :class="linkClasses('profile')"
        >
          Особистий кабінет
        </RouterLink>

        <RouterLink
          v-else
          to="/guest"
          class="transition-all duration-300"
          :class="linkClasses('guest')"
        >
          Гостьовий доступ
        </RouterLink>

        <!-- 🔹 Авторизація / Вихід -->
        <template v-if="auth.user">
          <button
            @click="logout"
            class="ml-2 px-5 py-2 rounded-lg font-semibold shadow-sm transition-all duration-300"
            :class="isScrolled
              ? 'bg-blue-600 hover:bg-blue-700 text-white'
              : 'bg-white text-blue-700 hover:bg-blue-100'"
          >
            Вийти
          </button>
        </template>

        <template v-else>
          <RouterLink
            to="/login"
            class="px-4 py-2 rounded-lg font-semibold transition-all duration-300"
            :class="isScrolled
              ? 'bg-blue-600 hover:bg-blue-700 text-white'
              : 'bg-white text-blue-700 hover:bg-blue-100'"
          >
            Увійти
          </RouterLink>

          <RouterLink
            to="/register"
            class="px-4 py-2 rounded-lg font-semibold transition-all duration-300"
            :class="isScrolled
              ? 'border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white'
              : 'border border-white text-white hover:bg-white hover:text-blue-700'"
          >
            Зареєструватися
          </RouterLink>
        </template>
      </nav>
    </div>

    <!-- 📱 Мобільне меню -->
    <transition name="slide-fade">
      <div
        v-if="isMenuOpen"
        class="sm:hidden flex flex-col items-center py-6 space-y-4 shadow-lg transition-all duration-300"
        :class="isScrolled ? 'bg-white/95 text-blue-700' : 'bg-blue-700/95 text-white'"
      >
        <RouterLink
          to="/schedule"
          @click="closeMenu"
          class="text-lg font-semibold transition-all duration-300"
          :class="linkClasses('schedule', true)"
        >
          Розклад
        </RouterLink>

        <RouterLink
          v-if="auth.user"
          to="/profile"
          @click="closeMenu"
          class="text-lg font-semibold transition-all duration-300"
          :class="linkClasses('profile', true)"
        >
          Особистий кабінет
        </RouterLink>

        <RouterLink
          v-else
          to="/guest"
          @click="closeMenu"
          class="text-lg font-semibold transition-all duration-300"
          :class="linkClasses('guest', true)"
        >
          Гостьовий доступ
        </RouterLink>

        <template v-if="auth.user">
          <button
            @click="logout"
            class="px-6 py-2 rounded-lg font-semibold shadow-sm transition-all"
            :class="isScrolled
              ? 'bg-blue-600 hover:bg-blue-700 text-white'
              : 'bg-white text-blue-700 hover:bg-blue-100'"
          >
            Вийти
          </button>
        </template>

        <template v-else>
          <RouterLink
            to="/login"
            @click="closeMenu"
            class="px-6 py-2 rounded-lg font-semibold transition-all"
            :class="isScrolled
              ? 'bg-blue-600 hover:bg-blue-700 text-white'
              : 'bg-white text-blue-700 hover:bg-blue-100'"
          >
            Увійти
          </RouterLink>

          <RouterLink
            to="/register"
            @click="closeMenu"
            class="px-6 py-2 rounded-lg font-semibold transition-all"
            :class="isScrolled
              ? 'border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white'
              : 'border border-white text-white hover:bg-white hover:text-blue-700'"
          >
            Зареєструватися
          </RouterLink>
        </template>
      </div>
    </transition>
  </header>
</template>

<script setup>
import lightLogo from "@/assets/logo.svg";
import darkLogo from "@/assets/logo-dark.svg";
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "@/stores/useAuth"; // ✅ Підключаємо Pinia

const router = useRouter();
const route = useRoute();
const auth = useAuthStore(); // ✅ Використовуємо стан авторизації

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
const logout = async () => {
  closeMenu();
  await auth.logout();
  router.push("/");
};

// 🧠 Динамічні класи для посилань (активне / звичайне / скрол)
const linkClasses = (name, isMobile = false) => {
  const active = route.name === name;
  const base = "transition-colors duration-300";
  if (isScrolled.value) {
    return active
      ? `${base} text-blue-600 border-b-2 border-blue-600 pb-1`
      : `${base} text-blue-700 hover:text-blue-500`;
  } else {
    return active
      ? `${base} text-blue-200 border-b-2 border-white pb-1`
      : `${base} text-white hover:text-blue-300`;
  }
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
