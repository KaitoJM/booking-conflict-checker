<template>
  <div>
    <UButton
      :icon="isDark ? 'i-lucide-moon' : 'i-lucide-sun'"
      color="neutral"
      variant="outline"
      :aria-label="`Switch to ${isDark ? 'light' : 'dark'} mode`"
      @click="isDark = !isDark"
    >
      {{ isDark ? 'Dark' : 'Light' }} Mode
    </UButton>

    <template #fallback>
      <div class="size-8"></div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { useColorMode } from '@nuxt/ui/runtime/inertia/stubs.js'
import { computed } from 'vue'

const colorMode = useColorMode()

const isDark = computed({
  get() {
    return colorMode.value === 'dark'
  },
  set(_isDark) {
    colorMode.preference = _isDark ? 'dark' : 'light'
  },
})
</script>
