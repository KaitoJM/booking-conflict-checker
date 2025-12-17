<template>
  <div class="flex justify-between gap-2 mb-4">
    <UInput
      v-model="searchKey"
      icon="i-lucide-search"
      placeholder="Search users..."
      class="w-100"
      @keyup="handleSearchKeyup()"
    />
  </div>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { useDebounceFn } from '@vueuse/core'
import { ref } from 'vue'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import { useUserStore } from '@/stores/user.store'

const userStore = useUserStore()
const toast = useToast()

const searchKey = ref<string>('')

const handleSearchKeyup = useDebounceFn(() => {
  search()
}, 400)

const loading = ref<boolean>(false)
const search = () => {
  loading.value = true

  try {
    userStore.getUsers(searchKey.value)
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
