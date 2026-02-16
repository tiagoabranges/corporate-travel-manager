<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import AuthLayout from "../layouts/AuthLayout.vue";

import axios from "axios";

const router = useRouter();

const email = ref("");
const password = ref("");
const error = ref("");

const handleLogin = async () => {
  try {
    const response = await axios.post(
      "http://localhost:8000/api/login",
      {
        email: email.value,
        password: password.value,
      }
    );

    localStorage.setItem("token", response.data.data.token);
    localStorage.setItem(
      "user",
      JSON.stringify(response.data.data.user)
    );

    router.push("/dashboard");
  } catch (err) {
    error.value = "Login inválido";
  }
};
</script>

<template>
  <AuthLayout>
    <div class="bg-white shadow-xl rounded-2xl p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Travel Manager
      </h2>

      <div class="flex flex-col gap-4">
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="input"
        />

        <input
          v-model="password"
          type="password"
          placeholder="Senha"
          class="input"
        />

        <button
          @click="handleLogin"
          class="btn-primary"
        >
          Entrar
        </button>
      </div>

      <p v-if="error" class="text-red-600 text-sm mt-3">
        {{ error }}
      </p>

      <div class="text-center mt-6 text-sm text-gray-500">
        Não tem conta?
        <router-link
          to="/register"
          class="text-blue-600 hover:underline"
        >
          Criar conta
        </router-link>
      </div>
    </div>
  </AuthLayout>
</template>
