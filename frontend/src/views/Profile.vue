<script setup lang="ts">
import { ref } from "vue";

const user = JSON.parse(localStorage.getItem("user") || "{}");

const name = ref(user?.name || "");
const email = ref(user?.email || "");
const success = ref("");

const saveProfile = () => {
  const updatedUser = {
    ...user,
    name: name.value,
    email: email.value,
  };

  localStorage.setItem("user", JSON.stringify(updatedUser));
  success.value = "Perfil atualizado com sucesso!";
};
</script>

<template>
  <div class="space-y-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-800">
        Perfil
      </h2>
      <p class="text-sm text-gray-500">
        Edite suas informações pessoais
      </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6 max-w-xl">
      <div class="flex flex-col gap-4">
        <input v-model="name" class="input" placeholder="Nome" />
        <input v-model="email" class="input" placeholder="Email" />

        <button @click="saveProfile" class="btn-primary">
          Salvar alterações
        </button>

        <p v-if="success" class="text-green-600 text-sm">
          {{ success }}
        </p>
      </div>
    </div>
  </div>
</template>
