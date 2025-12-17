<template>
  <AdminBookingFilter />
  <UTable :data="data" :columns="columns" class="flex-1" />
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { computed, h, onMounted, ref, resolveComponent } from 'vue'
import type { TableColumn } from '@nuxt/ui'
import type { Booking } from '@/types/booking.types'
import { useBookingStore } from '@/stores/booking.store'
import { useOverlay } from '@nuxt/ui/runtime/composables/useOverlay.js'
import ConfirmationDialog from '../ConfirmationDialog.vue'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import AdminBookingFilter from './AdminBookingFilter.vue'

const bookingStore = useBookingStore()

const data = computed<Booking[]>(() => bookingStore.bookings)

onMounted(() => {
  bookingStore.getBookings()
})

const UButton = resolveComponent('UButton')
const UIcon = resolveComponent('UIcon')
const columns: TableColumn<Booking>[] = [
  {
    accessorKey: 'id',
    header: '#',
    cell: ({ row }) => `#${row.getValue('id')}`,
  },
  {
    accessorKey: 'description',
    header: 'Booking',
    cell: ({ row }) => {
      return row.getValue('description')
    },
  },
  {
    accessorKey: 'user_id',
    header: 'User',
    cell: ({ row }) => {
      return h(
        'div',
        {
          class: 'flex flex-col',
        },
        [
          h('p', { class: 'font-bold' }, row.original.user?.name),
          h('p', { class: 'text-xs' }, row.original.user?.email),
        ],
      )
    },
  },
  {
    accessorKey: 'date',
    header: 'Schedule',
    cell: ({ row }) => {
      const BookingDate = new Date(row.getValue('date')).toLocaleString('en-US', {
        day: 'numeric',
        month: 'short',
      })

      const startTime = row.original.start_time
      const startDateObj = new Date(`1970-01-01T${startTime}`)
      const startDate = startDateObj.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      })

      const endTime = row.original.end_time
      const endDateObj = new Date(`1970-01-01T${endTime}`)
      const endDate = endDateObj.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      })
      return h('div', { class: 'flex items-start gap-2' }, [
        h(UIcon, {
          class: 'size-5',
          name: 'i-lucide-calendar',
        }),
        h(
          'div',
          {
            class: 'flex flex-col',
          },
          [
            h('p', { class: 'font-bold' }, BookingDate),
            h('p', { class: 'text-xs' }, `${startDate} - ${endDate}`),
          ],
        ),
      ])
    },
  },
  {
    accessorKey: 'id',
    header: () => h('div', { class: 'text-right' }, 'Action'),
    cell: ({ row }) => {
      const id = row.original.id
      return h('div', { class: 'flex items-center gap-2 justify-end' }, [
        h(UButton, {
          loading: deleting.value == id,
          variant: 'outline',
          color: 'error',
          size: 'sm',
          icon: 'i-lucide-trash',
          onClick: () => handleDeleteBooking(id),
        }),
      ])
    },
  },
]

const overlay = useOverlay()
const deleteModal = overlay.create(ConfirmationDialog)
const handleDeleteBooking = (id: string) => {
  deleteModal.open({
    title: 'Delete Booking',
    message: 'Are you sure you want to delete this booking?',
    onOk: () => {
      deleteBooking(id)
    },
  })
}

const toast = useToast()
const deleting = ref<string>('')
const deleteBooking = async (id: string) => {
  deleting.value = id
  try {
    await bookingStore.deleteBooking(id)
    toast.add({
      title: 'Success',
      description: 'Booking has been deleted.',
      icon: 'i-lucide-check',
      color: 'success',
    })
  } catch (error) {
    const fetchError = error as FetchError<any>

    toast.add({
      title: 'Error',
      description: fetchError.data?.message ?? fetchError.message ?? 'Something went wrong',
      icon: 'i-lucide-octagon-x',
      color: 'error',
    })
  } finally {
    deleting.value = ''
  }
}
</script>
