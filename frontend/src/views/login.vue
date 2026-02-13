<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { login } from "../api/auth";
import type { User } from "../types/user";

const router = useRouter();

const email = ref<string>("");
const password = ref<string>("");
const error = ref<string>("");

const handleLogin = async () => {
  try {
    const data = await login(email.value, password.value);

    localStorage.setItem("token", data.token);
    localStorage.setItem("user", JSON.stringify(data.user));

    router.push("/dashboard");
  } catch (err) {
    error.value = "Credenciais inválidas";
  }
};
</script>
