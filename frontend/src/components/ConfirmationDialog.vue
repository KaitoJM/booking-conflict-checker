<template>
  <UModal :close="{ onClick: () => emit('close', false) }" :title="title">
    <template #body>
      <p>{{ message }}</p>
      <p v-if="submessage" class="text-sm opacity-70">{{ submessage }}</p>
    </template>
    <template #footer>
      <div class="flex justify-end w-full items-center gap-2">
        <UButton color="neutral" variant="outline" label="Cancel" @click="emit('close', false)" />
        <UButton label="Proceed" variant="outline" @click="handleProceedClick()" />
      </div>
    </template>
  </UModal>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  message: string
  submessage?: string
}>()

const emit = defineEmits<{ close: [boolean]; ok: [boolean] }>()

const handleProceedClick = () => {
  emit('ok', true)
  emit('close', false)
}
</script>
