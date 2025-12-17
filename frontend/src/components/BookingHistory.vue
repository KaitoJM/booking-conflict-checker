<template>
  <p class="font-bold text-sm mb-2 text-neutral-400">Booking History</p>
  <UTable :data="data" :columns="columns" class="flex-1" />
</template>

<script setup lang="ts">
import { computed, h, onMounted, ref, resolveComponent } from 'vue'
import type { TableColumn } from '@nuxt/ui'
import type { Booking } from '@/types/booking.types'
import { useBookingStore } from '@/stores/booking.store'

const bookingStore = useBookingStore()

const data = computed<Booking[]>(() => bookingStore.bookings)

onMounted(() => {
  bookingStore.getBookings()
})

const columns: TableColumn<Booking>[] = [
  {
    accessorKey: 'id',
    header: '#',
    cell: ({ row }) => `#${row.getValue('id')}`,
  },
  {
    accessorKey: 'date',
    header: 'Date',
    cell: ({ row }) => {
      return new Date(row.getValue('date')).toLocaleString('en-US', {
        day: 'numeric',
        month: 'short',
      })
    },
  },
  {
    accessorKey: 'start_time',
    header: 'Start Time',
  },
  {
    accessorKey: 'end_time',
    header: 'End Time',
  },
]
</script>
