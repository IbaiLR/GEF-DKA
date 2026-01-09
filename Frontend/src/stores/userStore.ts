// src/stores/user.ts
import { ref } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', () => {
  const user = ref<any>(null);
  const loading = ref(false);
  const initialized = ref(false);
  const error = ref('');

  async function getUser() {
    loading.value = true;
    error.value = '';

    try {
      const token = localStorage.getItem('token');
      if (!token) {
        user.value = null;
        return;
      }

      const res = await axios.get('http://localhost:8000/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      });

      user.value = res.data.user;
    } catch (err) {
      user.value = null;
      error.value = 'No se pudo cargar el usuario';
    } finally {
      loading.value = false;
      initialized.value = true;
    }
  }

  return { user, loading, initialized, error, getUser };
});

