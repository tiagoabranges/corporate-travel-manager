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
      class="bg-white shadow rounded-xl p-4 mb-4 border border-gray-100"
    >
      <!-- VISUALIZAÇÃO -->
      <div v-if="editingId !== order.id">
        <p class="text-gray-700">
          <span class="font-semibold">Destino:</span>
          {{ order.destination }}
        </p>

        <p class="text-gray-700">
          <span class="font-semibold">Período:</span>
          {{ order.departure_date }} → {{ order.return_date }}
        </p>

        <p class="text-gray-700">
          <span class="font-semibold">Status:</span>
          {{ statusMap[order.status] || order.status }}
        </p>

        <div class="flex gap-2 mt-3">
          <button @click="startEdit(order)" class="btn-secondary">
            Editar
          </button>

          <button @click="remove(order.id)" class="btn-danger">
            Deletar
          </button>
        </div>

        <!-- ADMIN -->
        <div v-if="isAdmin" class="mt-4 flex items-center gap-2">
          <select
            v-model="statusEdit[order.id]"
            :disabled="order.status === 'approved'"
            class="input"
          >
            <option value="" disabled>Alterar status</option>
            <option value="approved">Aprovar</option>
            <option value="cancelled">Cancelar</option>
          </select>

          <button
            @click="changeStatus(order)"
            class="btn-primary"
            :disabled="
              order.status === 'approved' ||
              !statusEdit[order.id] ||
              statusEdit[order.id] === order.status
            "
          >
            Salvar Status
          </button>
        </div>
      </div>

      <!-- EDIÇÃO -->
      <div v-else class="flex flex-col gap-2">
        <input v-model="editData.requester_name" class="input" />
        <input v-model="editData.destination" class="input" />
        <input v-model="editData.departure_date" type="date" class="input" />
        <input v-model="editData.return_date" type="date" class="input" />

        <div class="flex gap-2 mt-2">
          <button @click="saveEdit(order.id)" class="btn-primary">
            Salvar
          </button>
          <button @click="cancelEdit" class="btn-secondary">
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

