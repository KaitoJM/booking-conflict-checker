<template>
  <UHeader title="Booking Conflict Checker">
    <template #right>
      <div class="flex gap-4 items-center">
        <UUser
          :name="auth.user?.name"
          :description="auth.user?.email"
          :avatar="{
            icon: 'i-lucide-user',
          }"
        />
        <UButton variant="ghost" @click="handleLogout()">Logout</UButton>
      </div>
    </template>
  </UHeader>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store'
import { useOverlay } from '@nuxt/ui/runtime/composables/useOverlay.js'
import ConfirmationDialog from './ConfirmationDialog.vue'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const overlay = useOverlay()
const logoutModal = overlay.create(ConfirmationDialog)
const handleLogout = () => {
  logoutModal.open({
    title: 'Logout',
    message: 'Are you sure you want to logout?',
    onOk: () => {
      logout()
    },
  })
}

const logout = () => {
  auth.logout()
  router.push({ name: 'login' })
}
</script>
