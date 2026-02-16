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

// Usuário logado
const user = JSON.parse(localStorage.getItem("user") || "{}");

// Permissões
const isAdmin = computed(() => user?.role === "admin");
const isOwner = (order: TravelOrder) =>
  order.user_id === user?.id;

// Estado edição pedido
const editingId = ref<number | null>(null);
const editData = ref<Partial<TravelOrder>>({});

// Estado edição status
const statusEdit = ref<Record<number, "approved" | "cancelled" | "">>({});

// Mensagens por pedido
const statusMessages = ref<
  Record<number, { error?: string; success?: string }>
>({});

// Inicializa statusEdit
watch(
  () => props.orders,
  (orders) => {
    const next: Record<number, "approved" | "cancelled" | ""> = {};
    for (const o of orders) {
      next[o.id] = "";
    }
    statusEdit.value = next;
  },
  { immediate: true }
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

// -------- Alterar status (Admin) --------
const changeStatus = async (order: TravelOrder) => {
  const newStatus = statusEdit.value[order.id];

  if (!newStatus || newStatus === order.status) return;

  try {
    statusMessages.value[order.id] = {};

    await updateStatus(order.id, newStatus);

    statusMessages.value[order.id] = {
      success: "Status atualizado com sucesso!",
    };

    emit("refresh");

    setTimeout(() => {
      statusMessages.value[order.id] = {};
    }, 3000);

  } catch (error: any) {
    statusMessages.value[order.id] = {
      error:
        error.response?.data?.message ||
        "Erro ao atualizar status.",
    };
  }
};
</script>

<template>
  <div class="mt-8">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">
      Pedidos
    </h3>

    <div v-if="orders.length === 0" class="text-gray-500">
      Nenhum pedido encontrado.
    </div>

    <div
      v-for="order in orders"
      :key="order.id"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4 hover:shadow-md transition"
    >
      <!-- VISUALIZAÇÃO -->
      <div v-if="editingId !== order.id">

        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-semibold text-gray-800">
              {{ order.destination }}
            </h3>

            <!-- Nome do solicitante (apenas admin) -->
            <p
              v-if="isAdmin"
              class="text-xs text-gray-500 mt-1"
            >
              Solicitado por:
              <span class="font-medium text-gray-700">
                {{ order.user?.name }}
              </span>
            </p>

            <p class="text-sm text-gray-500 mt-1">
              {{ order.departure_date }} → {{ order.return_date }}
            </p>
          </div>

          <!-- Badge de status -->
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

        <!-- Editar / Deletar (apenas dono) -->
        <div
          v-if="isOwner(order)"
          class="flex gap-3 mt-6"
        >
          <button
            @click="startEdit(order)"
            class="btn-secondary"
          >
            Editar
          </button>

          <button
            @click="remove(order.id)"
            class="btn-danger"
          >
            Deletar
          </button>
        </div>

        <!-- Alterar status (admin) -->
        <div
          v-if="isAdmin"
          class="flex flex-col gap-2 mt-4 pt-4 border-t"
        >
          <div class="flex items-center gap-3">
            <select
              v-model="statusEdit[order.id]"
              class="input w-48"
              :disabled="order.status !== 'requested'"
            >
              <option value="" disabled>
                Alterar status
              </option>
              <option value="approved">Aprovar</option>
              <option value="cancelled">Cancelar</option>
            </select>

            <button
              @click="changeStatus(order)"
              class="btn-primary w-auto px-4"
              :disabled="
                order.status !== 'requested' ||
                !statusEdit[order.id]
              "
            >
              Salvar status
            </button>
          </div>

          <!-- Mensagens -->
          <p
            v-if="statusMessages[order.id]?.error"
            class="text-red-600 text-sm"
          >
            {{ statusMessages[order.id].error }}
          </p>

          <p
            v-if="statusMessages[order.id]?.success"
            class="text-green-600 text-sm"
          >
            {{ statusMessages[order.id].success }}
          </p>
        </div>

      </div>

      <!-- EDIÇÃO -->
      <div v-else class="flex flex-col gap-3">
        <input
          v-model="editData.requester_name"
          class="input"
        />

        <input
          v-model="editData.destination"
          class="input"
        />

        <input
          v-model="editData.departure_date"
          type="date"
          class="input"
        />

        <input
          v-model="editData.return_date"
          type="date"
          class="input"
        />

        <div class="flex gap-3 mt-2">
          <button
            @click="saveEdit(order.id)"
            class="btn-primary"
          >
            Salvar
          </button>

          <button
            @click="cancelEdit"
            class="btn-secondary"
          >
            Cancelar
          </button>
        </div>
      </div>

    </div>
  </div>
</template>
