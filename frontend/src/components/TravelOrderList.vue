<script setup lang="ts">
import { ref, onMounted } from "vue";
import { getOrders } from "../api/travelOrders";
import type { TravelOrder } from "../types/travelOrder";

const orders = ref<TravelOrder[]>([]);

const loadOrders = async () => {
  orders.value = await getOrders();
};

onMounted(loadOrders);
</script>

<template>
  <div>
    <h3>Meus Pedidos</h3>

    <div v-if="orders.length === 0">
      Nenhum pedido encontrado.
    </div>

    <div v-for="order in orders" :key="order.id">
      <p>
        <strong>{{ order.destination }}</strong> |
        {{ order.departure_date }} → {{ order.return_date }} |
        Status: {{ order.status }}
      </p>
    </div>
  </div>
</template>
