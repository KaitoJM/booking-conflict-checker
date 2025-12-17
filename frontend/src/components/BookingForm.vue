<template>
  <p class="font-bold text-sm mb-2 text-neutral-400">Create Booking</p>
  <UForm class="border border-accented rounded-lg p-4 flex flex-col gap-2">
    <UFormField label="Book date">
      <UInputDate class="w-full" ref="inputDate" v-model="modelValue">
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
              <UCalendar v-model="modelValue" class="p-2" />
            </template>
          </UPopover>
        </template>
      </UInputDate>
    </UFormField>
    <UFormField label="Start time">
      <UInputTime />
    </UFormField>
    <UFormField label="End time">
      <UInputTime />
    </UFormField>
    <UButton
      variant="outline"
      icon="i-lucide-calendar"
      class="flex justify-center mt-3"
      label="Book Now"
    />
  </UForm>
</template>

<script setup lang="ts">
import { CalendarDate } from '@internationalized/date'
import { shallowRef, useTemplateRef } from 'vue'

const inputDate = useTemplateRef('inputDate')
const modelValue = shallowRef(new CalendarDate(2022, 1, 10))
</script>
