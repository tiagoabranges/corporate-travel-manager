<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
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
  <div>
    <h2>Login</h2>
    <input v-model="email" placeholder="Email" />
    <input v-model="password" type="password" placeholder="Senha" />
    <button @click="handleLogin">Entrar</button>
    <p>
  Não tem conta?
  <router-link to="/register">Cadastrar</router-link>
</p>

    <p v-if="error">{{ error }}</p>
  </div>
</template>
