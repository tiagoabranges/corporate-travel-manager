import api from "./axios";
import type { User } from "../types/user";

interface LoginResponse {
  token: string;
  user: User;
}

export const login = async (
  email: string,
  password: string
): Promise<LoginResponse> => {
  const response = await api.post("/login", { email, password });

  return response.data.data;
};
