<script setup lang="ts">
import BookingForm from '@/components/BookingForm.vue'
import BookingHistory from '@/components/BookingHistory.vue'
</script>

<template>
  <UPage class="max-w-250 mx-auto">
    <UPageBody>
      sdfhkdjfhdkjfh
      <div class="flex gap-8">
        <div class="w-80">
          <BookingForm />
        </div>
        <div class="flex-1">
          <BookingHistory />
        </div>
      </div>
    </UPageBody>
  </UPage>
</template>
