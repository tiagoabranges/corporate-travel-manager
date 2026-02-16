<script setup lang="ts">
import { ref } from "vue";
import { createOrder } from "../api/travelOrders";
import type { CreateTravelOrderDTO } from "../types/travelOrder";

const emit = defineEmits<{
  (e: "created"): void;
}>();

const requester_name = ref("");
const destination = ref("");
const departure_date = ref("");
const return_date = ref("");

const success = ref("");
const error = ref("");

const submit = async () => {
  try {
    const data: CreateTravelOrderDTO = {
      requester_name: requester_name.value,
      destination: destination.value,
      departure_date: departure_date.value,
      return_date: return_date.value,
    };

    await createOrder(data);

    success.value = "Pedido criado com sucesso!";
    error.value = "";

    requester_name.value = "";
    destination.value = "";
    departure_date.value = "";
    return_date.value = "";

    emit("created");
  } catch (err) {
    error.value = "Erro ao criar pedido.";
    success.value = "";
  }
};
</script>

<template>
 <div class="bg-white rounded-2xl shadow-md border border-blue-100 p-6"
>

  <h3 class="text-lg font-semibold mb-4 text-gray-800">
  Criar novo pedido
</h3>


    <div class="flex flex-col gap-3">
      <input
        v-model="requester_name"
        placeholder="Nome do solicitante"
        class="input"
      />

      <input
        v-model="destination"
        placeholder="Destino"
        class="input"
      />
      

      <input
        v-model="departure_date"
        type="date"
        class="input"
      />

      <input
        v-model="return_date"
        type="date"
        class="input"
      />

      <button
        @click="submit"
        class="btn-primary mt-2"
      >
        Criar
      </button>
    </div>

    <p v-if="success" class="text-green-600 mt-3 text-sm">
      {{ success }}
    </p>

    <p v-if="error" class="text-red-600 mt-3 text-sm">
      {{ error }}
    </p>
  </div>
</template>
