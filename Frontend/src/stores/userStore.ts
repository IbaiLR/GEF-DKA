// src/stores/user.ts
import api from "@/services/api.js";
import type { User } from "@/types/User";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useUserStore = defineStore("user", () => {
  const user = ref<User | null>(null);
  const loading = ref(false);
  const initialized = ref(false);
  const error = ref("");

  async function getUser() {
    loading.value = true;
    error.value = "";

    try {
      const token = localStorage.getItem("token") ?? "";
      if (!token) {
        user.value = null;
        return false;
      }

      const res = await api.get("/api/auth", {
        headers: { Authorization: `Bearer ${token}` },
      });

      user.value = res.data.user;
      return true;
    } catch (err: any) {
      if (err.response?.status === 401) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
      }

      user.value = null;
      return false;
    } finally {
      loading.value = false;
      initialized.value = true;
    }
  }

  return { user, loading, initialized, error, getUser };
});
