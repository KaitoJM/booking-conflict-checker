<template>
  <p class="font-bold text-sm mb-2 text-neutral-400">Create Booking</p>
  <UForm
    @submit.prevent="handleSubmit"
    class="border border-accented rounded-lg p-4 flex flex-col gap-2"
  >
    <UFormField label="Book date">
      <UInputDate class="w-full" ref="inputDate" v-model="date">
        <template #trailing>
          <UPopover :reference="inputDate?.inputsRef[3]?.$el">
            <UButton
              color="neutral"
              variant="link"
              size="sm"
              icon="i-lucide-calendar"
              aria-label="Select a date"
              class="px-0"
            />

            <template #content>
              <UCalendar v-model="date" class="p-2" />
            </template>
          </UPopover>
        </template>
      </UInputDate>
    </UFormField>
    <UFormField label="Start time">
      <UInputTime v-model="start_time" />
    </UFormField>
    <UFormField label="End time">
      <UInputTime v-model="end_time" />
    </UFormField>
    <UButton
      :loading="loading"
      type="submit"
      variant="outline"
      icon="i-lucide-calendar"
      class="flex justify-center mt-3"
      label="Book Now"
    />
  </UForm>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { useBookingStore } from '@/stores/booking.store'
import { CalendarDate } from '@internationalized/date'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import { ref, shallowRef, useTemplateRef } from 'vue'

const bookingStore = useBookingStore()
const inputDate = useTemplateRef('inputDate')
const toast = useToast()

const date = shallowRef(new CalendarDate(2022, 1, 10))
const start_time = ref<string>('')
const end_time = ref<string>('')

const loading = ref<boolean>(false)
const handleSubmit = async () => {
  try {
    loading.value = true
    const dateToString = date.value.toString()
    const startTimeToString = start_time.value.toString()
    const endTimeToString = end_time.value.toString()

    await bookingStore.createBooking(dateToString, startTimeToString, endTimeToString)
    toast.add({
      title: 'Booked!',
      description: 'Your booking has been confirmed.',
      icon: 'i-lucide-octagon-x',
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
    loading.value = false
  }
}
</script>
