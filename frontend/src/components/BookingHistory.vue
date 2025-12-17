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
    cell: ({ row }) => {
      const time = row.getValue('start_time') // "HH:mm:ss"
      const dateObj = new Date(`1970-01-01T${time}`) // combine with arbitrary date
      return dateObj.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true, // this makes AM/PM
      })
    },
  },
  {
    accessorKey: 'end_time',
    header: 'End Time',
    cell: ({ row }) => {
      const time = row.getValue('end_time')
      const dateObj = new Date(`1970-01-01T${time}`)
      return dateObj.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      })
    },
  },
]
</script>
