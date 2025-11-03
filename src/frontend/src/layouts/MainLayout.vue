<template>
  <div class="flex flex-col min-h-screen bg-gray-50 text-gray-800 relative">
    <!-- 🔝 Хедер -->
    <Header />

    <!-- 🧠 Основний контент -->
    <main class="flex-grow pt-20">
      <RouterView />
    </main>

    <!-- ⚙️ Плаваюча кнопка адмін панелі -->
    <transition name="fade-scale">
      <router-link
        v-if="userRole === 'admin' && isVisible"
        to="/admin"
        class="fixed bottom-20 right-5 group transition-transform duration-300 z-50"
        title="Адмін панель"
      >
        <div
          class="bg-blue-700 hover:bg-blue-800 border-4 border-yellow-400 rounded-full
                 w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl
                 transition-all duration-300"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="#FFD000"
            class="w-7 h-7 group-hover:rotate-90 transition-transform duration-500 ease-in-out"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.518-.878 3.278.883 2.4 2.4a1.724 1.724 0 001.065 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.878 1.518-.883 3.278-2.4 2.4a1.724 1.724 0 00-2.573 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.518.878-3.278-.883-2.4-2.4a1.724 1.724 0 00-1.065-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.878-1.518.883-3.278 2.4-2.4.95.55 2.147.173 2.573-1.066z"
            />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
      </router-link>
    </transition>

    <!-- 🔻 Футер -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useAuthStore } from "@/stores/useAuth.js";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

const authStore = useAuthStore();
const userRole = computed(() => authStore.user?.role || null);
const isVisible = ref(true);

let lastScrollY = 0;
const handleScroll = () => {
  const currentY = window.scrollY;
  isVisible.value = currentY < lastScrollY;
  lastScrollY = currentY;
};

onMounted(() => window.addEventListener("scroll", handleScroll));
onBeforeUnmount(() => window.removeEventListener("scroll", handleScroll));
</script>


<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.3s ease;
}
.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.8);
}
</style>
