<template>
  <UDashboardNavbar
    title="Admin Portal"
    :toggle="{
      color: 'primary',
      variant: 'subtle',
      class: 'rounded-full',
    }"
  >
    <template #right>
      <div class="flex gap-3 items-center">
        <UPopover
          :content="{
            align: 'center',
            side: 'bottom',
            sideOffset: 8,
          }"
        >
          <UAvatar src="https://i.pravatar.cc/150?img=3" alt="User Avatar" size="md" />
          <template #content>
            <div class="p-4 flex flex-col w-60 items-center">
              <UAvatar
                src="https://i.pravatar.cc/150?img=3"
                alt="User Avatar"
                size="3xl"
                class="mb-2"
              />
              <p class="font-semibold">John Doe</p>
              <p class="text-sm text-gray-500">johndoe@example.com</p>
              <USeparator class="my-3" />
              <UButton
                @click="handleLogoutClick()"
                variant="outline"
                color="error"
                class="w-full flex items-center justify-center gap-2"
              >
                Logout
              </UButton>
            </div>
          </template>
        </UPopover>
      </div>
    </template>
  </UDashboardNavbar>
</template>

<script setup lang="ts">
import ConfirmationDialog from '../ConfirmationDialog.vue'
import { useOverlay } from '@nuxt/ui/runtime/composables/useOverlay.js'
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'

const overlay = useOverlay()
const logoutModal = overlay.create(ConfirmationDialog)
const auth = useAuthStore()
const router = useRouter()

const handleLogoutClick = () => {
  logoutModal.open({
    title: 'Logout',
    message: 'Are you sure you want to logout of the system?',
    onOk: () => {
      auth.logout()
      router.push({ name: 'home' })
    },
  })
}
</script>
