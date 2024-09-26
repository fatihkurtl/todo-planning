<script setup lang="ts">
import { RouterView } from 'vue-router'
import NavbarLayout from './layouts/NavbarLayout.vue'


</script>

<template>
  <NavbarLayout />
  <main class="bg-gray-600">
    <div class="container mx-auto p-4 max-w-6xl min-h-screen">
      <RouterView />
    </div>
  </main>
</template>

<style scoped></style>
