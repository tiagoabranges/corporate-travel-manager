export type OrderStatus = "requested" | "approved" | "cancelled";

export interface TravelOrder {
  id: number;
  user_id: number;
  requester_name: string;
  destination: string;
  departure_date: string;
  return_date: string;
  status: OrderStatus;
}

export interface CreateTravelOrderDTO {
  requester_name: string;
  destination: string;
  departure_date: string;
  return_date: string;
}
