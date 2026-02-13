import api from "./axios";
import type { TravelOrder } from "../types/travelOrder";

interface PaginatedResponse<T> {
  data: T[];
}

export const getOrders = async (): Promise<TravelOrder[]> => {
  const response = await api.get("/travel-orders");

  return response.data.data.data;
};

export const createOrder = async (
  order: Omit<TravelOrder, "id" | "user_id" | "status">
) => {
  return api.post("/travel-orders", order);
};

export const updateStatus = async (
  id: number,
  status: "approved" | "cancelled"
) => {
  return api.patch(`/travel-orders/${id}/status`, { status });
};
