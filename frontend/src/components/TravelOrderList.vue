<script setup lang="ts">
import { ref } from "vue";
import type { TravelOrder } from "../types/travelOrder";
import { deleteOrder, updateOrder } from "../api/travelOrders";

const props = defineProps<{
  orders: TravelOrder[];
}>();

const emit = defineEmits<{
  (e: "refresh"): void;
}>();

const editingId = ref<number | null>(null);
const editData = ref<Partial<TravelOrder>>({});

const startEdit = (order: TravelOrder) => {
  editingId.value = order.id;
  editData.value = { ...order };
};

const cancelEdit = () => {
  editingId.value = null;
};

const saveEdit = async (id: number) => {
  await updateOrder(id, {
    requester_name: editData.value.requester_name,
    destination: editData.value.destination,
    departure_date: editData.value.departure_date,
    return_date: editData.value.return_date,
  });

  editingId.value = null;
  emit("refresh");
};

const remove = async (id: number) => {
  await deleteOrder(id);
  emit("refresh");
};
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
      style="margin-bottom:12px; padding:10px; border:1px solid #ccc;"
    >
      <div v-if="editingId !== order.id">
        <p><strong>Destino:</strong> {{ order.destination }}</p>
        <p>
          <strong>Período:</strong>
          {{ order.departure_date }} → {{ order.return_date }}
        </p>
        <p><strong>Status:</strong> {{ order.status }}</p>

        <button @click="startEdit(order)">Editar</button>
        <button @click="remove(order.id)">Deletar</button>
      </div>

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
