<script setup lang="ts">
import { ref } from "vue";
import type { User } from "../types/user";

const raw = localStorage.getItem("user") || "{}";
const user = JSON.parse(raw) as User;

const name = ref(user?.name ?? "");
const email = ref(user?.email ?? "");
const loading = ref(false);
const error = ref("");
const success = ref("");

const saveProfile = async () => {
  error.value = "";
  success.value = "";
  loading.value = true;

  try {
    const token = localStorage.getItem("token");

    const res = await fetch("http://localhost:8000/api/me", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
      }),
    });

    const data = await res.json();

    if (!res.ok) {
      error.value = data?.message || "Erro ao atualizar perfil";
      return;
    }

    // ApiResponse::success retorna { success: true, data: ... }
    const updatedUser = data?.data;

    if (!updatedUser) {
      error.value = "Resposta inválida do servidor";
      return;
    }

    localStorage.setItem("user", JSON.stringify(updatedUser));
    success.value = "Perfil atualizado com sucesso!";
  } catch (e: any) {
    error.value = e.message || "Erro inesperado";
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="space-y-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-800">Perfil</h2>
      <p class="text-sm text-gray-500">
        Edite suas informações pessoais
      </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6 max-w-xl">
      <div class="flex flex-col gap-4">

        <input v-model="name" class="input" placeholder="Nome" />

        <input v-model="email" class="input" placeholder="Email" />

        <button
          @click="saveProfile"
          :disabled="loading"
          class="btn-primary"
        >
          <span v-if="loading">Salvando...</span>
          <span v-else>Salvar alterações</span>
        </button>

        <p v-if="success" class="text-green-600 text-sm">
          {{ success }}
        </p>

        <p v-if="error" class="text-red-600 text-sm">
          {{ error }}
        </p>

      </div>
    </div>
  </div>
</template>
