import { computed, ref } from 'vue'
import { $fetch, type FetchError } from 'ofetch'
import type { Booking } from '@/types/booking.types'
import type { ApiError } from '@/types/api-response.type'
import { useAuthStore } from '@/stores/auth.store'

export interface Gap {
  start_time: string
  end_time: string
  duration: string
}

export interface InputReport {
  date: string
  start_time: string
  end_time: string
}

export interface SummaryReport {
  has_conflict: boolean
  has_overlaps: boolean
  total_gaps: number
}

interface ApiBookingCheckResponse {
  input: {
    date: string
    start_time: string
    end_time: string
  }
  conflicts: Booking[]
  overlaps: Booking[]
  gaps: Gap[]
  summary: {
    has_conflict: boolean
    has_overlaps: boolean
    total_gaps: number
  }
}

export function useBookingAvailabilityAnalyser() {
  const authStore = useAuthStore()

  const conflicts = ref<Booking[]>([])
  const overlaps = ref<Booking[]>([])
  const gaps = ref<Gap[]>([])
  const inputs = ref<InputReport>()
  const summary = ref<SummaryReport>({
    has_conflict: true,
    has_overlaps: true,
    total_gaps: 0,
  })

  async function check(date: string, start: string, end: string) {
    try {
      const response = await $fetch<ApiBookingCheckResponse>(
        `${import.meta.env.VITE_API_URL}/bookings-conflicts`,
        {
          method: 'GET',
          headers: { Authorization: `Bearer ${authStore.token}` },
          params: { date, start_time: start, end_time: end },
        },
      )

      conflicts.value = response.conflicts
      overlaps.value = response.overlaps
      gaps.value = response.gaps
      inputs.value = response.input
      summary.value = response.summary
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'Login failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }

  return { conflicts, overlaps, gaps, inputs, summary, check }
}
