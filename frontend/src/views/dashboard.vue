<script setup lang="ts">
import { ref, onMounted } from "vue";
import { getOrders, updateStatus } from "../api/travelOrders";
import type { TravelOrder } from "../types/travelOrder";
import type { User } from "../types/user";

const orders = ref<TravelOrder[]>([]);
const user = JSON.parse(localStorage.getItem("user") || "{}") as User;

const loadOrders = async () => {
  orders.value = await getOrders();
};

const approve = async (id: number) => {
  await updateStatus(id, "approved");
  await loadOrders();
};

onMounted(loadOrders);
</script>
