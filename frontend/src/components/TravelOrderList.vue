<script setup lang="ts">
import { ref, computed, watch } from "vue";
import type { TravelOrder } from "../types/travelOrder";
import { deleteOrder, updateOrder, updateStatus } from "../api/travelOrders";

const statusMap: Record<string, string> = {
  requested: "Solicitado",
  approved: "Aprovado",
  cancelled: "Cancelado",
};

const props = defineProps<{
  orders: TravelOrder[];
}>();

const emit = defineEmits<{
  (e: "refresh"): void;
}>();

// Role
const user = JSON.parse(localStorage.getItem("user") || "{}");
const isAdmin = computed(() => user?.role === "admin");

// Estado de edição (edit pedido)
const editingId = ref<number | null>(null);
const editData = ref<Partial<TravelOrder>>({});

// Estado de edição (status) - 1 por pedido
const statusEdit = ref<Record<number, "approved" | "cancelled" | "">>({});

// 🔥 Sempre que orders mudar, inicializa statusEdit com o status atual
watch(
  () => props.orders,
  (orders) => {
    const next: Record<number, "approved" | "cancelled" | ""> = {};
    for (const o of orders) {
      // se já existir no estado (usuário já mexeu), preserva
      next[o.id] = statusEdit.value[o.id] ?? (o.status as any) ?? "";
    }
    statusEdit.value = next;
  },
  { immediate: true },
);

// -------- Editar pedido --------
const startEdit = (order: TravelOrder) => {
  editingId.value = order.id;
  editData.value = { ...order };
};

const cancelEdit = () => {
  editingId.value = null;
  editData.value = {};
};

const saveEdit = async (id: number) => {
  await updateOrder(id, {
    requester_name: editData.value.requester_name,
    destination: editData.value.destination,
    departure_date: editData.value.departure_date,
    return_date: editData.value.return_date,
  });

  cancelEdit();
  emit("refresh");
};

// -------- Deletar --------
const remove = async (id: number) => {
  const confirmed = confirm("Deseja realmente excluir este pedido?");
  if (!confirmed) return;

  await deleteOrder(id);
  emit("refresh");
};

// -------- Admin: alterar status --------
const changeStatus = async (order: TravelOrder) => {
  const newStatus = statusEdit.value[order.id];

  // não envia se vazio ou se não mudou
  if (!newStatus || newStatus === order.status) return;

  await updateStatus(order.id, newStatus);
  emit("refresh");
};
</script>
<template>
  <div class="mt-8">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">
      Pedidos
    </h3>

    <div
      v-if="orders.length === 0"
      class="text-gray-500"
    >
      Nenhum pedido encontrado.
    </div>

 <div
  v-for="order in orders"
  :key="order.id"
  class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4 hover:shadow-md transition"
>
  <div class="flex justify-between items-start">
    <div>
      <h3 class="text-lg font-semibold text-gray-800">
        {{ order.destination }}
      </h3>

      <p class="text-sm text-gray-500 mt-1">
        {{ order.departure_date }} → {{ order.return_date }}
      </p>
    </div>

    <span
      :class="[
        'text-xs font-medium px-3 py-1 rounded-full',
        order.status === 'approved'
          ? 'bg-green-100 text-green-700'
          : order.status === 'cancelled'
          ? 'bg-red-100 text-red-700'
          : 'bg-yellow-100 text-yellow-700'
      ]"
    >
      {{ statusMap[order.status] }}
    </span>
  </div>

  <div class="flex gap-3 mt-6">
    <button @click="startEdit(order)" class="btn-secondary">
      Editar
    </button>

    <button @click="remove(order.id)" class="btn-danger">
      Deletar
    </button>
  </div>
</div>

  </div>
</template>

