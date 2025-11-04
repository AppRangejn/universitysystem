import { createRouter, createWebHistory } from "vue-router";

// 🧱 Layouts
import MainLayout from "@/layouts/MainLayout.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";

// 🏠 Головна
import HomePage from "@/pages/HomePage.vue";

// 📅 Розклад
import SchedulePage from "@/pages/Schedule/SchedulePage.vue";
import StudentSchedule from "@/pages/Schedule/StudentSchedule.vue";
import TeacherSchedule from "@/pages/Schedule/TeacherSchedule.vue";
import AdminSchedule from "@/pages/Schedule/AdminSchedule.vue";
import GroupSchedulePage from "@/pages/Schedule/GroupSchedulePage.vue";

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
        path: "schedules",
        name: "schedules",
        component: SchedulePage,
        meta: { title: "Розклад — GitHub Університет" },
      },
      {
        path: "schedule/group/:id",
        name: "group-schedule",
        component: GroupSchedulePage,
        props: true,
        meta: { title: "Розклад групи — GitHub Університет" },
      },
      {
        path: "schedule/student",
        name: "student-schedule",
        component: StudentSchedule,
        meta: { title: "Розклад студента — GitHub Університет" },
      },
      {
        path: "schedule/teacher",
        name: "teacher-schedule",
        component: TeacherSchedule,
        meta: { title: "Розклад викладача — GitHub Університет" },
      },
      {
        path: "schedule/admin",
        name: "admin-schedule-old",
        component: AdminSchedule,
        meta: { title: "Адмін розклад — GitHub Університет" },
      },
      {
        path: "profile",
        name: "profile",
        component: ProfilePage,
        meta: { title: "Профіль — GitHub Університет" },
      },
    ],
  },

  // --- ⚙️ Адмін layout ---
  {
    path: "/admin",
    component: AdminLayout,
    meta: { requiresAdmin: true },
    children: [
      {
        path: "",
        name: "admin-dashboard",
        component: () => import("@/pages/Admin/AdminDashboard.vue"),
        meta: { title: "Адмін панель — GitHub Університет" },
      },
      {
        path: "schedule",
        name: "admin-schedule",
        component: () => import("@/pages/Schedule/AdminSchedule.vue"),
        meta: { title: "Розклади — GitHub Університет" },
      },
      {
        path: "users",
        name: "admin-users",
        component: () => import("@/pages/Admin/AdminUsers.vue"),
        meta: { title: "Користувачі — GitHub Університет" },
      },
      {
        path: "groups",
        name: "admin-groups",
        component: () => import("@/pages/Admin/AdminGroups.vue"),
        meta: { title: "Групи — GitHub Університет" },
      },
      {
        path: "courses",
        name: "admin-courses",
        component: () => import("@/pages/Admin/AdminCourses.vue"),
        meta: { title: "Курси - GitHub Університет" },
      },
      {
        path: "faculties",
        name: "admin-faculties",
        component: () => import("@/pages/Admin/AdminFaculties.vue"),
        meta: { title: "Факультети — GitHub Університет" },
      },
      {
        path: "schedules",
        name: "admin-schedules",
        component: () => import("@/pages/Admin/AdminSchedules.vue"),
        meta: { title: "Розклади — GitHub Університет" },
      },
      {
        path: "schedule-settings",
        name: "admin-schedule-settings",
        component: () => import("@/pages/Admin/AdminScheduleSettings.vue"),
        meta: { title: "Налаштування розкладу — GitHub Університет" },
      },
    ],
  },

  // --- Авторизація ---
  {
    path: "/login",
    name: "login",
    component: LoginPage,
    meta: { title: "Вхід — GitHub Університет" },
  },
  {
    path: "/register",
    name: "register",
    component: RegisterPage,
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

// 🧭 Захист для адмінських маршрутів
router.beforeEach((to, from, next) => {
  const userRole = localStorage.getItem("userRole");

  if (to.meta.requiresAdmin && userRole !== "admin") {
    next("/login");
  } else {
    next();
  }
});

// 🧠 Динамічна зміна заголовка сторінки
router.afterEach((to) => {
  document.title = to.meta.title || "GitHub Університет";
});

export default router;
