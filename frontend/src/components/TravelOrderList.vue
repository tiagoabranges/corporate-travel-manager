<script setup lang="ts">
import type { TravelOrder } from "../types/travelOrder";

defineProps<{
  orders: TravelOrder[];
}>();
</script>

<template>
  <div>
    <h3>Meus Pedidos</h3>

    <div v-if="orders.length === 0">
      Nenhum pedido encontrado.
    </div>

    <div
      v-for="order in orders"
      :key="order.id"
      style="margin-bottom:10px; padding:8px; border:1px solid #ccc;"
    >
      <p><strong>Destino:</strong> {{ order.destination }}</p>
      <p>
        <strong>Período:</strong>
        {{ order.departure_date }} → {{ order.return_date }}
      </p>
      <p><strong>Status:</strong> {{ order.status }}</p>
    </div>
  </div>
</template>
