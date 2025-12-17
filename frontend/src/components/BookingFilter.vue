<template>
  <div class="border border-dashed border-accented p-4 rounded-lg flex flex-col gap-2">
    <UInput
      v-model="searchKey"
      icon="i-lucide-search"
      placeholder="Search bookings..."
      class="w-full"
      @keyup="handleSearchKeyup()"
    />
    <div class="flex gap-2 items-center">
      <span class="text-sm">Filter by Date</span>
      <UInputDate v-model="date" variant="outline" />
      <UButton :loading="loading" label="Filter" variant="outline" @click="search()" />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { useDebounceFn } from '@vueuse/core'
import { useBookingStore } from '@/stores/booking.store'
import { ref } from 'vue'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'

const bookingStore = useBookingStore()
const toast = useToast()

const searchKey = ref<string>('')
const date = ref<string>('')

const handleSearchKeyup = useDebounceFn(() => {
  search()
}, 400)

const loading = ref<boolean>(false)
const search = () => {
  loading.value = true

  try {
    bookingStore.getBookings(searchKey.value, date.value.toString())
  } catch (error) {
    const fetchError = error as FetchError<any>

    toast.add({
      title: 'Error',
      description: fetchError.data?.message ?? fetchError.message ?? 'Something went wrong',
      icon: 'i-lucide-octagon-x',
      color: 'error',
    })
  } finally {
    loading.value = false
  }
}
</script>
