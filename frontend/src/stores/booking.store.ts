import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type { Booking } from '@/types/booking.types'

export const useBookingStore = defineStore('bookingStore', () => {
  const booking = ref<Booking[]>([])

  const createBooking = () => {}
  return { booking }
})
