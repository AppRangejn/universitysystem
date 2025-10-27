import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  server: {
    host: true, // 👈 дозволяє працювати в Docker
    port: 5173,
    watch: {
      usePolling: true, // 👈 для live reload у контейнері
    },
  },
});
