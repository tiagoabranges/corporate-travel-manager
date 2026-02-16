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
  <div class="space-y-8">

    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">
          Dashboard
        </h2>
        <p class="text-sm text-gray-500">
          Gerencie seus pedidos de viagem
        </p>
      </div>

      <div class="bg-blue-600 text-white px-6 py-4 rounded-2xl shadow-lg">
  <p class="text-xs text-blue-100">Total de pedidos</p>
  <p class="text-2xl font-bold">
    {{ orders.length }}
  </p>
</div>

    </div>

    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-1">
        <TravelOrderForm @created="loadOrders" />
      </div>

      <div class="lg:col-span-2">
        <TravelOrderList
          :orders="orders"
          @refresh="loadOrders"
        />
      </div>
    </div>

  </div>
</template>
