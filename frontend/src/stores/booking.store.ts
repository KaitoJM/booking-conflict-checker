import { $fetch, type FetchError } from 'ofetch'
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type { Booking } from '@/types/booking.types'
import type { ApiError, ApiSuccess } from '@/types/api-response.type'
import { useAuthStore } from './auth.store'

export const useBookingStore = defineStore('bookingStore', () => {
  const authStore = useAuthStore()
  const bookings = ref<Booking[]>([])

  const getBookings = async (search: string | null = null, date: string | null = null) => {
    try {
      const res = await $fetch<ApiSuccess<Booking[]>>(`${import.meta.env.VITE_API_URL}/bookings`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          Accept: 'application/json',
        },
        params: { search, date },
      })
      bookings.value = res.data
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'Booking retrieval failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }

  const createBooking = async (
    description: string,
    date: string,
    start_time: string,
    end_time: string,
  ) => {
    try {
      const res = await $fetch<ApiSuccess<Booking>>(`${import.meta.env.VITE_API_URL}/bookings`, {
        method: 'POST',
        body: { description, date, start_time, end_time },
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

  const deleteBooking = async (id: string) => {
    try {
      const res = await $fetch<ApiSuccess<Booking>>(
        `${import.meta.env.VITE_API_URL}/bookings/${id}`,
        {
          method: 'DELETE',
          headers: {
            Authorization: `Bearer ${authStore.token}`,
            Accept: 'application/json',
          },
        },
      )
      getBookings()
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'Booking deletion failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }
  return { bookings, getBookings, createBooking, deleteBooking }
})
