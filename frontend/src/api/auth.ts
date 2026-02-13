import api from "./axios";
import type { User } from "../types/user";

export interface LoginResponse {
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

export const register = async (
  name: string,
  email: string,
  password: string
): Promise<User> => {
  const response = await api.post("/register", {
    name,
    email,
    password,
  });

  return response.data.data;
};
