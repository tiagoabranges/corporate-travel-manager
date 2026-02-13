import api from "./axios";
import type { TravelOrder, CreateTravelOrderDTO } from "../types/travelOrder";

interface ApiResponse<T> {
  success: boolean;
  data: T;
}

interface Paginated<T> {
  data: T[];
  current_page: number;
  last_page: number;
  total: number;
}

export const getOrders = async (): Promise<TravelOrder[]> => {
  const response = await api.get<ApiResponse<Paginated<TravelOrder>>>(
    "/travel-orders"
  );

  return response.data.data.data;
};

export const createOrder = async (
  data: CreateTravelOrderDTO
): Promise<TravelOrder> => {
  const response = await api.post<ApiResponse<TravelOrder>>(
    "/travel-orders",
    data
  );

  return response.data.data;
};

export const updateStatus = async (
  id: number,
  status: "approved" | "cancelled"
): Promise<TravelOrder> => {
  const response = await api.patch<ApiResponse<TravelOrder>>(
    `/travel-orders/${id}/status`,
    { status }
  );

  return response.data.data;
};
