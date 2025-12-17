import { $fetch, type FetchError } from 'ofetch'
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type { Booking } from '@/types/booking.types'
import type { ApiError, ApiSuccess } from '@/types/api-response.type'
import { useAuthStore } from './auth.store'

export const useBookingStore = defineStore('bookingStore', () => {
  const authStore = useAuthStore()
  const bookings = ref<Booking[]>([])

  const getBookings = async () => {
    try {
      const res = await $fetch<ApiSuccess<Booking[]>>(`${import.meta.env.VITE_API_URL}/bookings`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          Accept: 'application/json',
        },
      })
      bookings.value = res.data
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'Booking creation failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }

  const createBooking = async (date: string, start_time: string, end_time: string) => {
    try {
      const res = await $fetch<ApiSuccess<Booking>>(`${import.meta.env.VITE_API_URL}/bookings`, {
        method: 'POST',
        body: { date, start_time, end_time },
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          Accept: 'application/json',
        },
      })
      getBookings()
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'Booking creation failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }
  return { bookings, getBookings, createBooking }
})
