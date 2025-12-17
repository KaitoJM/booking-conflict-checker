<template>
  <p class="font-bold text-sm mb-4 text-neutral-400">Create Booking</p>
  <UForm
    @submit.prevent="handleSubmit"
    class="border border-accented rounded-lg p-4 flex flex-col gap-2"
  >
    <UFormField label="Booking Description">
      <UTextarea v-model="description" @click="editing = true" class="w-full" />
    </UFormField>
    <UFormField label="Book date">
      <UInputDate class="w-full" ref="inputDate" v-model="date" @click="editing = true">
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
    <div class="flex gap-4">
      <UFormField label="Start time">
        <UInputTime v-model="start_time" @click="editing = true" />
      </UFormField>
      <UFormField label="End time">
        <UInputTime v-model="end_time" @click="editing = true" />
      </UFormField>
    </div>
    <UAlert
      v-if="isAvailable && !editing"
      title="Available"
      description="This time and date is available."
      icon="i-lucide-check"
      variant="soft"
      class="mt-2"
    />
    <UAlert
      v-if="!isAvailable && !editing"
      title="Not Available"
      description="This time and date is not available."
      icon="i-lucide-x"
      variant="soft"
      color="error"
      class="mt-2"
    />
    <UButton
      v-if="!isAvailable || editing"
      :loading="loading"
      @click="handleCheckClick()"
      variant="outline"
      color="neutral"
      class="flex justify-center mt-3"
      label="Check Availability"
    />
    <UButton
      v-if="isAvailable && !editing"
      :loading="loading"
      type="submit"
      variant="outline"
      icon="i-lucide-calendar"
      class="flex justify-center mt-3"
      label="Book Now"
    />
  </UForm>
  <div class="flex flex-col gap-2 mt-4">
    <UAlert
      v-if="bookingAvailablity.conflicts.value.length"
      title="Conflicts"
      color="error"
      variant="soft"
    >
      <template #description>
        <ul>
          <li
            v-for="(conflict, conflictKey) in bookingAvailablity.conflicts.value"
            :key="`conflict-item${conflictKey}-${conflict.id}`"
            class="flex items-center gap-2"
          >
            <UIcon name="i-lucide-calendar-x" class="size-4" />
            {{ conflict.date }} : {{ formatTime(conflict.start_time) }} -
            {{ formatTime(conflict.end_time) }}
          </li>
        </ul>
      </template>
    </UAlert>
    <UAlert
      v-if="bookingAvailablity.overlaps.value.length"
      title="Overlaps"
      color="error"
      variant="soft"
    >
      <template #description>
        <ul>
          <li
            v-for="(overlap, overlapKey) in bookingAvailablity.overlaps.value"
            :key="`overlap-item${overlapKey}-${overlap.id}`"
            class="flex items-center gap-2"
          >
            <UIcon name="i-lucide-calendar-x" class="size-4" />
            {{ overlap.date }} : {{ formatTime(overlap.start_time) }} -
            {{ formatTime(overlap.end_time) }}
          </li>
        </ul>
      </template>
    </UAlert>
    <UAlert
      v-if="bookingAvailablity.gaps.value.length"
      :title="`Available time (GAP) on the selected date (${
        bookingAvailablity.summary.value.total_gaps
      })`"
      color="success"
      variant="soft"
    >
      <template #description>
        <ul>
          <li
            v-for="(gap, gapKey) in bookingAvailablity.gaps.value"
            :key="`gap-item${gapKey}-${gap.start_time}`"
            class="flex items-center gap-2"
          >
            <UIcon name="i-lucide-clock" class="size-4" />
            {{ formatTime(gap.start_time) }} - {{ formatTime(gap.end_time) }}
          </li>
        </ul>
      </template>
    </UAlert>
  </div>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { useBookingStore } from '@/stores/booking.store'
import { CalendarDate, getLocalTimeZone, today } from '@internationalized/date'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import { computed, ref, shallowRef, useTemplateRef } from 'vue'
import { useBookingAvailabilityAnalyser } from '@/composables/useBookingAvailabilityAnalyser'

const bookingStore = useBookingStore()
const inputDate = useTemplateRef('inputDate')
const toast = useToast()
const bookingAvailablity = useBookingAvailabilityAnalyser()

const editing = ref<boolean>(true)
const isAvailable = computed<boolean>(() => {
  // Available only if NO conflicts and NO overlaps
  return !(
    bookingAvailablity.summary.value?.has_conflict || bookingAvailablity.summary.value?.has_overlaps
  )
})

const description = ref<string>('')
const date = shallowRef(today(getLocalTimeZone()))
const start_time = ref<string>('')
const end_time = ref<string>('')

const loading = ref<boolean>(false)
const handleCheckClick = async () => {
  try {
    loading.value = true
    const dateToString = date.value.toString()
    const startTimeToString = start_time.value.toString()
    const endTimeToString = end_time.value.toString()
    await bookingAvailablity.check(dateToString, startTimeToString, endTimeToString)
  } catch (error) {
    const fetchError = error as FetchError<any>

    toast.add({
      title: 'Error',
      description: fetchError.data?.message ?? fetchError.message ?? 'Something went wrong',
      icon: 'i-lucide-check',
      color: 'error',
    })
  } finally {
    loading.value = false
    editing.value = false
  }
}

const handleSubmit = async () => {
  try {
    loading.value = true
    const dateToString = date.value.toString()
    const startTimeToString = start_time.value.toString()
    const endTimeToString = end_time.value.toString()

    await bookingStore.createBooking(
      description.value,
      dateToString,
      startTimeToString,
      endTimeToString,
    )
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
    editing.value = false
  }
}

function formatTime(time: string) {
  const dateObj = new Date(`1970-01-01T${time}`)
  return dateObj.toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true,
  })
}
</script>
