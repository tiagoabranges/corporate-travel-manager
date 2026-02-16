<script setup lang="ts">
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

const logout = () => {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  router.push("/");
};

const isActive = (path: string) => {
  return route.path === path;
};
</script>
<template>
 <div class="min-h-screen flex bg-gradient-to-br from-blue-50 to-gray-100">


    <aside class="w-64 bg-blue-900 text-white flex flex-col">

      <div class="p-6 font-semibold text-lg text-white">
  Travel Manager
</div>


      <nav class="flex-1 px-4 space-y-2">
        <button
          @click="router.push('/dashboard')"
          :class="[
  'w-full text-left px-4 py-2 rounded-lg text-sm transition',
  isActive('/dashboard')
    ? 'bg-blue-700 text-white'
    : 'text-blue-100 hover:bg-blue-800'
]"

        >
          Dashboard
        </button>

        <button
          @click="router.push('/profile')"
          :class="[
            'w-full text-left px-4 py-2 rounded-lg text-sm transition',
            isActive('/profile')
              ? 'bg-blue-700 text-white'
    : 'text-blue-100 hover:bg-blue-800'
          ]"
        >
          Perfil
        </button>
      </nav>

      <div class="p-4 border-t">
        <button
          @click="logout"
          class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-sm hover:bg-red-100 transition"
        >
          Logout
        </button>
      </div>
    </aside>

    <main class="flex-1 p-10">
      <router-view />
    </main>

  </div>
</template>
