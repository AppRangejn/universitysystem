<template>
  <div class="flex flex-col min-h-screen bg-gray-50 text-gray-800">
    <!-- 🔝 Хедер -->
    <Header />

    <!-- 🧠 Основний контент -->
    <main class="flex-grow pt-20">
      <RouterView />
    </main>

    <!-- 🔻 Футер -->
    <Footer />
  </div>
</template>

<script setup>
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
</script>

<style scoped>
/* Щоб хедер завжди був зверху */
main {
  scroll-behavior: smooth;
}
</style>
