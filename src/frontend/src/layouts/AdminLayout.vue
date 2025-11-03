<template>
  <div class="flex h-screen bg-gray-100 text-gray-900">
    <!-- 🧭 Бокове меню -->
    <aside class="w-64 bg-blue-800 text-white flex flex-col shadow-lg">
      <div class="p-5 font-bold text-xl border-b border-blue-600 flex items-center gap-2">
        ⚙️ <span>Адмін панель</span>
      </div>

      <nav class="flex-grow p-4 space-y-2">
        <RouterLink
          to="/admin"
          class="block px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          📊 Головна
        </RouterLink>
        <RouterLink
          to="/admin/schedule"
          class="block px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          📚 Розклад
        </RouterLink>
        <RouterLink
          to="/admin/users"
          class="block px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          👥 Користувачі
        </RouterLink>
        <RouterLink
          to="/admin/groups"
          class="block px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          🏫 Групи
        </RouterLink>
      </nav>

      <div class="p-4 border-t border-blue-700 text-sm text-blue-200">
        <RouterLink to="/" class="hover:underline flex items-center gap-1">
          ← Повернутись
        </RouterLink>
      </div>
    </aside>

    <!-- 🧠 Контент -->
    <main class="flex-grow overflow-y-auto p-8 bg-gray-50">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
</script>

<style scoped>
.router-link-active {
  background-color: #2563eb;
}
</style>
