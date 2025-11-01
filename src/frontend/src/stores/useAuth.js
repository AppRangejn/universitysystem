import { defineStore } from "pinia";
import axios from "axios";

// ⚙️ Налаштування axios
axios.defaults.baseURL = "http://localhost:8081";
axios.defaults.withCredentials = true;
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    loading: false,
    error: null,
  }),

  actions: {
    // 👤 Отримати поточного користувача
    async getUser() {
      try {
        const res = await axios.get("/api/user");
        this.user = res.data;
        console.log("🧭 Поточний користувач:", this.user);
      } catch {
        this.user = null;
      }
    },

    // 🔐 Вхід
    async login(credentials) {
      this.loading = true;
      this.error = null;
      try {
        await axios.get("/sanctum/csrf-cookie");
        await axios.post("/api/login", credentials);
        await this.getUser();
        console.log("✅ Вхід виконано:", this.user);
      } catch (err) {
        console.error("❌ Помилка входу:", err);
        this.error =
          err.response?.data?.message ||
          (err.response?.status === 404
            ? "Маршрут /api/login не знайдено на сервері"
            : "Помилка авторизації");
      } finally {
        this.loading = false;
      }
    },

    // 🧾 Реєстрація
    async register(data) {
      this.loading = true;
      this.error = null;
      try {
        await axios.get("/sanctum/csrf-cookie");
        const res = await axios.post("/api/register", data);

        // 🧠 Після успішної реєстрації — зберігаємо користувача
        this.user = res.data.user || null;

        if (!this.user) {
          await this.getUser();
        }

        console.log("✅ Реєстрація успішна:", this.user);
      } catch (err) {
        console.error("❌ Помилка реєстрації:", err);
        this.error =
          Object.values(err.response?.data?.errors || {})
            .flat()
            .join("\n") ||
          err.response?.data?.message ||
          "Помилка реєстрації";
      } finally {
        this.loading = false;
      }
    },

    // 🚪 Вихід
    async logout() {
      try {
        await axios.post("/api/logout");
        this.user = null;
        console.log("👋 Вихід виконано");
      } catch (err) {
        console.error("Помилка при виході:", err);
      }
    },
  },
});
