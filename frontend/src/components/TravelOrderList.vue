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
  <div>
    <h3>Pedidos</h3>

    <div v-if="orders.length === 0">Nenhum pedido encontrado.</div>

    <div
      v-for="order in orders"
      :key="order.id"
      style="margin-bottom: 12px; padding: 10px; border: 1px solid #ccc"
    >
      <!-- VISUALIZAÇÃO -->
      <div v-if="editingId !== order.id">
        <p><strong>Destino:</strong> {{ order.destination }}</p>
        <p>
          <strong>Período:</strong>
          {{ order.departure_date }} → {{ order.return_date }}
        </p>
        <p>
          <strong>Status:</strong>
          {{ statusMap[order.status] || order.status }}
        </p>

        <button @click="startEdit(order)">Editar</button>
        <button @click="remove(order.id)">Deletar</button>

        <!-- ADMIN -->
        <div v-if="isAdmin" style="margin-top: 8px">
          <select
            v-model="statusEdit[order.id]"
            :disabled="order.status === 'approved'"
          >
            <!-- placeholder -->
            <option value="" disabled>Alterar status</option>

            <!-- opções válidas -->
            <option value="approved" :disabled="order.status === 'approved'">
              Aprovar
            </option>

            <option value="cancelled" :disabled="order.status === 'cancelled'">
              Cancelar
            </option>
          </select>

          <button
            @click="changeStatus(order)"
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
      <div v-else>
        <input v-model="editData.requester_name" />
        <input v-model="editData.destination" />
        <input v-model="editData.departure_date" type="date" />
        <input v-model="editData.return_date" type="date" />

        <button @click="saveEdit(order.id)">Salvar</button>
        <button @click="cancelEdit">Cancelar</button>
      </div>
    </div>
  </div>
</template>
