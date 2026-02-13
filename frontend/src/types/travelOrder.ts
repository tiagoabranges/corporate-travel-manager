export interface TravelOrder {
  id: number;
  user_id: number;
  requester_name: string;
  destination: string;
  departure_date: string;
  return_date: string;
  status: "requested" | "approved" | "cancelled";
}
