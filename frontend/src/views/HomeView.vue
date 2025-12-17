<script setup lang="ts">
import BookingForm from '@/components/BookingForm.vue'
import BookingHistory from '@/components/BookingHistory.vue'
</script>

<template>
  <UPage class="max-w-250 mx-auto">
    <UPageBody>
      <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-80">
          <BookingForm />
        </div>
        <div class="flex-1">
          <BookingHistory />
        </div>
      </div>
    </UPageBody>
  </UPage>
</template>
