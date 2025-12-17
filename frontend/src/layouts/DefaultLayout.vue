<script setup lang="ts">
import Header from '@/components/Header.vue'
</script>

<template>
  <Header />
  <UMain class="p-4">
    <router-view />
  </UMain>
  <UFooter />
</template>
