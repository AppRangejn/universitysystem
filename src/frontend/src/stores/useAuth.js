import { defineStore } from "pinia";
import axios from "axios";

// ⚙️ Глобальна конфігурація axios
axios.defaults.baseURL = "http://localhost:8081"; // ← заміни на свій бекенд, якщо треба
axios.defaults.withCredentials = true;
axios.defaults.xsrfCookieName = "XSRF-TOKEN";
axios.defaults.xsrfHeaderName = "X-XSRF-TOKEN";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("authToken") || null,
    loading: false,
    error: null,
  }),

  actions: {
    // 🧭 Встановити токен в axios
    setAuthHeader(token) {
      if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      } else {
        delete axios.defaults.headers.common["Authorization"];
      }
    },

    // 👤 Отримати поточного користувача
    async getUser() {
      try {
        const res = await axios.get("/api/user");
        this.user = res.data;

        // Якщо бекенд повертає токен разом із користувачем — збережи його
        if (res.data?.token && !this.token) {
          this.token = res.data.token;
          localStorage.setItem("authToken", this.token);
          this.setAuthHeader(this.token);
        }

        console.log("✅ Отримано користувача:", this.user);
      } catch (err) {
        console.warn("⚠️ Не вдалося отримати користувача:", err.response?.status);
        this.user = null;
      }
    },

    // 🔐 Вхід
    async login(credentials) {
      this.loading = true;
      this.error = null;
      try {
        await axios.get("/sanctum/csrf-cookie");
        const res = await axios.post("/api/login", credentials);

        // 🎟️ Якщо API повертає токен
        if (res.data?.token) {
          this.token = res.data.token;
          localStorage.setItem("authToken", this.token);
          this.setAuthHeader(this.token);
        }

        await this.getUser();

        if (this.user?.role) {
          localStorage.setItem("userRole", this.user.role);
        }

        console.log("✅ Авторизація успішна:", this.user);
      } catch (err) {
        console.error("❌ Помилка входу:", err);
        this.error =
          err.response?.data?.message ||
          "Помилка входу. Перевірте email і пароль.";
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

        this.user = res.data.user;
        if (res.data?.token) {
          this.token = res.data.token;
          localStorage.setItem("authToken", this.token);
          this.setAuthHeader(this.token);
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

    // 🔄 Автоматичне відновлення сесії
    async restoreSession() {
      const savedToken = localStorage.getItem("authToken");
      if (savedToken) {
        this.token = savedToken;
        this.setAuthHeader(savedToken);
        await this.getUser();
      }
    },

    // 🚪 Вихід
    async logout() {
      try {
        await axios.post("/api/logout");
      } catch (err) {
        console.warn("⚠️ Сервер недоступний при виході:", err);
      } finally {
        this.user = null;
        this.token = null;
        localStorage.removeItem("authToken");
        localStorage.removeItem("userRole");
        this.setAuthHeader(null);
        console.log("👋 Вихід виконано");
      }
    },
  },
});
