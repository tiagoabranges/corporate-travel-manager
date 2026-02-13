<script setup lang="ts">
import { ref, onMounted } from "vue";
import TravelOrderForm from "../components/TravelOrderForm.vue";
import TravelOrderList from "../components/TravelOrderList.vue";
import { getOrders } from "../api/travelOrders";
import type { TravelOrder } from "../types/travelOrder";

import { useRouter } from "vue-router";

const router = useRouter();

const logout = () => {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  router.push("/");
};


const orders = ref<TravelOrder[]>([]);

const loadOrders = async () => {
  try {
    orders.value = await getOrders();
  } catch (err) {
    console.error("Erro ao carregar pedidos", err);
  }
};

onMounted(loadOrders);
</script>

<template>
  <div>
    <h2>Dashboard</h2>
    <button @click="logout">Logout</button>

    <TravelOrderForm @created="loadOrders" />

    <hr />

   <TravelOrderList
  :orders="orders"
  @refresh="loadOrders"
/>

  </div>
</template>
