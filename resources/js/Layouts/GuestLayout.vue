<script setup>
import { Head } from "@inertiajs/vue3";
</script>

<template>
  <Head title="Dharma & Astrid Wedding" />
  <div class="min-h-screen bg-gray-100 flex items-center justify-center relative">
    <div class="bg-white max-w-md min-h-screen w-full">
    
      <slot />
    </div>
  </div>
</template>
