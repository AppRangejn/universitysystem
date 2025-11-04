import './assets/main.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { useAuthStore } from '@/stores/useAuth'; // ✅ правильна назва

import App from './App.vue';
import router from './router';

const app = createApp(App);

const pinia = createPinia();
app.use(pinia);
app.use(router);

// 🧠 Відновлення сесії перед рендером
const auth = useAuthStore();
await auth.restoreSession();

app.mount('#app');
