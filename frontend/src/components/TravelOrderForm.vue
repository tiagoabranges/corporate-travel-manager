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
  <div>
    <h3>Criar Pedido</h3>

    <div style="display:flex; flex-direction:column; gap:8px; max-width:300px;">
      <input v-model="requester_name" placeholder="Nome do solicitante" />
      <input v-model="destination" placeholder="Destino" />
      <input v-model="departure_date" type="date" />
      <input v-model="return_date" type="date" />

      <button @click="submit">Criar</button>
    </div>

    <p v-if="success" style="color:green">{{ success }}</p>
    <p v-if="error" style="color:red">{{ error }}</p>
  </div>
</template>
