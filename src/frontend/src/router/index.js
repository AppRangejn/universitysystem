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
        meta: { title: "Головна — GitHub Університет" },
      },
      {
        path: "schedule",
        name: "schedule",
        component: SchedulePage,
        meta: { title: "Розклад — GitHub Університет" },
        children: [
          {
            path: "student",
            name: "student-schedule",
            component: StudentSchedule,
            meta: { title: "Розклад студента — GitHub Університет" },
          },
          {
            path: "teacher",
            name: "teacher-schedule",
            component: TeacherSchedule,
            meta: { title: "Розклад викладача — GitHub Університет" },
          },
          {
            path: "admin",
            name: "admin-schedule",
            component: AdminSchedule,
            meta: { title: "Адмін панель — GitHub Університет" },
          },
        ],
      },
      {
        path: "profile",
        name: "profile",
        component: ProfilePage,
        meta: { title: "Профіль — GitHub Університет" },
      },
    ],
  },

  // --- Авторизація (без layout) ---
  {
    path: "/login",
    name: "login",
    component: LoginPage,
    meta: { title: "Вхід — GitHub Університет" },
  },
  {
    path: "/register",
    name: "register",
    component: () => import("@/pages/Auth/RegisterPage.vue"),
    meta: { title: "Реєстрація — GitHub Університет" },
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

// 🧭 Динамічна зміна заголовка сторінки
router.afterEach((to) => {
  document.title = to.meta.title || "GitHub Університет";
});

export default router;
