<template>
  <UHeader title="Booking Conflict Checker">
    <template #right>
      <UUser
        :name="auth.user?.name"
        :description="auth.user?.email"
        :avatar="{
          icon: 'i-lucide-user',
        }"
      />
    </template>
  </UHeader>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store'

const auth = useAuthStore()
</script>
