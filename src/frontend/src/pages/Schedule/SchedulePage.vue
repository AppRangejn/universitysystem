<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white text-gray-800">
    <div class="max-w-7xl mx-auto px-6 py-10">
      <h1 class="text-4xl font-bold text-blue-800 mb-6">
        GitHub Університет
      </h1>
      <p class="text-xl text-gray-700 mb-10">Розклад академічних груп</p>

      <div v-for="faculty in faculties" :key="faculty.id" class="mb-5 bg-blue-100 rounded-xl overflow-hidden shadow-md">
        <button
          class="w-full flex justify-between items-center text-left px-5 py-4 text-lg font-semibold text-blue-900 hover:bg-blue-200 transition"
          @click="toggleFaculty(faculty.id)"
        >
          {{ faculty.name }}
          <span :class="openFaculty === faculty.id ? 'rotate-180' : ''" class="transition-transform">▼</span>
        </button>

        <transition name="fade">
          <div v-if="openFaculty === faculty.id" class="bg-white px-6 py-4 grid grid-cols-2 md:grid-cols-4 gap-4 border-t">
            <div
              v-for="course in faculty.courses"
              :key="course.id"
            >
              <h3 class="font-bold text-blue-700 mb-2">{{ course.name }}</h3>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="group in course.groups"
                  :key="group.id"
                  @click="goToGroup(group.id)"
                  class="px-3 py-1 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 text-sm font-medium transition"
                >
                  {{ group.name }}
                </button>
              </div>
            </div>

          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const faculties = ref([]);
const openFaculty = ref(null);
const router = useRouter();

const toggleFaculty = (id) => {
  openFaculty.value = openFaculty.value === id ? null : id;
};

const goToGroup = (id) => {
  router.push(`/schedule/group/${id}`);
};

onMounted(async () => {
  const res = await axios.get("/api/faculties");
  faculties.value = res.data;
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
