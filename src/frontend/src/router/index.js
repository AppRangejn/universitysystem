import { createRouter, createWebHistory } from "vue-router";

// 🧱 Layout
import MainLayout from "@/layouts/MainLayout.vue";

// 🏠 Головна
import HomePage from "@/pages/HomePage.vue";

// 📅 Розклад
import SchedulePage from "@/pages/Schedule/SchedulePage.vue";
import StudentSchedule from "@/pages/Schedule/StudentSchedule.vue";
import TeacherSchedule from "@/pages/Schedule/TeacherSchedule.vue";
import AdminSchedule from "@/pages/Schedule/AdminSchedule.vue";

// 👤 Профіль
import ProfilePage from "@/pages/Profile/ProfilePage.vue";

// 🔐 Авторизація
import LoginPage from "@/pages/Auth/LoginPage.vue";
import RegisterPage from "@/pages/Auth/RegisterPage.vue";

const routes = [
  // --- Основний layout ---
  {
    path: "/",
    component: MainLayout,
    children: [
      {
        path: "",
        name: "home",
        component: HomePage,
      },
      {
        path: "schedule",
        name: "schedule",
        component: SchedulePage,
        children: [
          {
            path: "student",
            name: "student-schedule",
            component: StudentSchedule,
          },
          {
            path: "teacher",
            name: "teacher-schedule",
            component: TeacherSchedule,
          },
          {
            path: "admin",
            name: "admin-schedule",
            component: AdminSchedule,
          },
        ],
      },
      {
        path: "profile",
        name: "profile",
        component: ProfilePage,
      },
    ],
  },

  // --- Авторизація (без layout) ---
  {
    path: "/login",
    name: "login",
    component: LoginPage,
  },
  {
    path: "/register",
    name: "register",
    component: RegisterPage,
  },

  // --- 404 ---
  {
    path: "/:pathMatch(.*)*",
    redirect: "/",
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  },
});

export default router;
