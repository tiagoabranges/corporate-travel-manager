<template>
 <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200">

    <!-- Navbar -->
    <header class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800">
          Travel Manager
        </h1>

       <button
  @click="$emit('logout')"
  class="bg-red-50 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-100 transition"
>
  Logout
</button>

      </div>
    </header>

    <!-- Conteúdo -->
    <main class="max-w-7xl mx-auto px-6 py-8">
      <slot />
    </main>
  </div>
</template>
