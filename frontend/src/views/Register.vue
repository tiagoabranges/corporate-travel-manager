<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { register } from "../api/auth";

const router = useRouter();

const name = ref<string>("");
const email = ref<string>("");
const password = ref<string>("");
const error = ref<string>("");
const success = ref<string>("");

const handleRegister = async () => {
  try {
    await register(name.value, email.value, password.value);

    success.value = "Usuário criado com sucesso!";
    error.value = "";

    setTimeout(() => {
      router.push("/");
    }, 1000);
  } catch (err) {
    error.value = "Erro ao registrar usuário.";
    success.value = "";
  }
};
</script>

<template>
  <div>
    <h2>Cadastro</h2>

    <input v-model="name" placeholder="Nome" />
    <input v-model="email" placeholder="Email" />
    <input v-model="password" type="password" placeholder="Senha" />

    <button @click="handleRegister">Cadastrar</button>

    <p v-if="error" style="color:red">{{ error }}</p>
    <p v-if="success" style="color:green">{{ success }}</p>
  </div>
</template>
