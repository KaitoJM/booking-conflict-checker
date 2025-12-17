<template>
  <div>
    <p class="text-xs uppercase font-semibold px-2 block mb-1 text-gray-400">Main Menu</p>
    <ul>
      <li>
        <router-link
          :to="{ name: 'adminBookings' }"
          :class="[
            'flex items-center gap-2 py-1 px-2',
            isActive('adminBookings')
              ? 'bg-primary-700 text-white rounded-md font-medium'
              : 'opacity-50 hover:opacity-100 rounded-md',
          ]"
        >
          <UIcon name="i-lucide-calendar" class="size-4 text-primary" />
          Bookings
        </router-link>
      </li>
      <li>
        <router-link
          :to="{ name: 'adminUsers' }"
          :class="[
            'flex items-center gap-2 py-1 px-2',
            isActive('adminUsers')
              ? 'bg-primary-700 text-white rounded-md font-medium'
              : 'opacity-50 hover:opacity-100 rounded-md',
          ]"
        >
          <UIcon name="i-lucide-users" class="size-4 text-primary" />
          Users
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'

const route = useRoute()
const isActive = (navName: string): boolean => {
  return route.name == navName
}
</script>
