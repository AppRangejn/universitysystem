<template>
  <div
    class="relative min-h-screen flex flex-col justify-center items-center text-white bg-gradient-to-br from-[#004AAD] via-[#005ECB] to-[#003B8E] overflow-hidden"
  >
    <!-- 🔵 Фонові ореоли -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute w-[400px] h-[400px] bg-blue-400/20 blur-[150px] top-[-100px] left-[-150px] rounded-full animate-pulse"></div>
      <div class="absolute w-[350px] h-[350px] bg-blue-200/10 blur-[160px] bottom-[-100px] right-[-150px] rounded-full animate-pulse delay-500"></div>
    </div>

    <!-- 🏫 Основна секція -->
    <div
      class="relative z-10 text-center w-[90%] max-w-3xl bg-white/10 backdrop-blur-2xl p-10 md:p-14 rounded-3xl shadow-[0_0_60px_rgba(0,0,0,0.4)] border border-white/20"
    >
      <!-- 🎓 Логотип -->
      <div class="flex flex-col items-center mb-8">
        <img
          src="https://pngimg.com/uploads/github/github_PNG20.png"
          alt="GitHub Logo"
          class="w-24 invert brightness-1000 drop-shadow-[0_0_8px_rgba(255,255,255,0.3)] mx-auto mb-4"
        />
        <h1 class="text-2xl md:text-5xl font-extrabold mb-4 text-white leading-tight">
          Освітня платформа
        </h1>
        <p class="text-blue-100/90 text-lg">
          Система, що об’єднує студентів, викладачів і знання
        </p>
      </div>

      <!-- 🔹 Вкладки -->
      <div
        class="flex flex-wrap justify-center gap-3 sm:gap-5 mb-10 bg-white/10 p-3 rounded-full backdrop-blur-xl border border-white/20"
      >
        <button
          v-for="tab in tabs"
          :key="tab.name"
          @click="activeTab = tab.name"
          class="px-6 py-2 text-base sm:text-lg font-semibold rounded-full transition-all duration-300"
          :class="[
            activeTab === tab.name
              ? 'bg-[#FFFFFF] text-[#004AAD] shadow-md scale-105'
              : 'text-white/80 hover:text-white hover:bg-white/10'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- 📘 Контент -->
      <transition name="fade" mode="out-in">
        <div v-if="activeTab === 'schedule'" key="schedule" class="text-lg">
          <p class="mb-6 text-blue-100">
            Перегляньте свій
            <span class="text-white font-semibold">розклад занять</span> —
            швидко, зручно і сучасно.
            Тут ви знайдете інформацію про пари, викладачів та аудиторії.
          </p>
          <button
            @click="goTo('schedule')"
            class="px-8 py-4 rounded-full bg-white text-[#004AAD] font-semibold shadow-lg hover:bg-blue-100 transition-all duration-500"
          >
            Перейти до розкладу
          </button>
        </div>

        <div v-else-if="activeTab === 'cabinet'" key="cabinet" class="text-lg text-blue-100/90">
          <p class="mb-6">
            Вітаємо у вашому
            <span class="font-semibold text-white">особистому кабінеті</span>!
            Тут можна переглядати дані профілю, відкрити свій розклад.
          </p>
          <button
            @click="goTo('cabinet')"
            class="px-8 py-4 rounded-full bg-white text-[#004AAD] font-semibold shadow-lg hover:bg-blue-100 transition-all duration-500"
          >
            Перейти до кабінету
          </button>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const tabs = [
  { name: "schedule", label: "Розклад" },
  { name: "cabinet", label: "Особистий кабінет" },
];

const activeTab = ref("schedule");

// 🚀 Натискання на кнопку "Перейти"
const goTo = (target) => {
  if (target === "schedule") {
    router.push("/schedules");
  } else if (target === "cabinet") {
    router.push("/profile");
  }
};
</script>

<style scoped>
@keyframes gradientMove {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.animate-gradientMove {
  background-size: 200% 200%;
  animation: gradientMove 18s ease-in-out infinite;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.6s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* 📱 Адаптив */
@media (max-width: 640px) {
  .rounded-3xl {
    border-radius: 1.5rem;
  }
  h1 {
    font-size: 2rem;
  }
  p {
    font-size: 1rem;
  }
  button {
    font-size: 0.95rem;
  }
}
</style>
