<script setup lang="ts">
import NavBar from '@/components/admin/NavBar.vue'
import Navigation from '@/components/admin/Navigation.vue'
</script>

<template>
  <UDashboardGroup class="h-screen overflow-hidden">
    <UDashboardSidebar>
      <Logo class="my-2" />
      <navigation />
    </UDashboardSidebar>
    <UDashboardPanel class="flex flex-col">
      <nav-bar />
      <UMain class="p-6 flex-1 overflow-auto">
        <router-view />
      </UMain>
    </UDashboardPanel>
  </UDashboardGroup>
</template>
