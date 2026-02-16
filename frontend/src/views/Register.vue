<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { register } from "../api/auth";
import AuthLayout from "../layouts/AuthLayout.vue";


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
  <AuthLayout>
    <div class="bg-white shadow-xl rounded-2xl p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Criar Conta
      </h2>

      <div class="flex flex-col gap-4">
        <input v-model="name" placeholder="Nome" class="input" />
        <input v-model="email" type="email" placeholder="Email" class="input" />
        <input v-model="password" type="password" placeholder="Senha" class="input" />

        <button @click="handleRegister" class="btn-primary">
          Cadastrar
        </button>
      </div>

      <p v-if="error" class="text-red-600 text-sm mt-3">
        {{ error }}
      </p>

      <p v-if="success" class="text-green-600 text-sm mt-3">
        {{ success }}
      </p>

      <div class="text-center mt-6 text-sm text-gray-500">
        Já tem conta?
        <router-link
          to="/"
          class="text-blue-600 hover:underline"
        >
          Fazer login
        </router-link>
      </div>
    </div>
  </AuthLayout>
</template>
