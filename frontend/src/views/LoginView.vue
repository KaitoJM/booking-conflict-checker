<template>
  <div class="p-4 flex justify-center items-center h-screen">
    <UPageCard class="w-100" orientation="vertical">
      <UForm @submit.prevent="handleSubmit()" class="flex flex-col gap-4 w-full">
        <p class="text-xl font-bold">Welcome back!</p>
        <UFormField label="Email">
          <UInput v-model="email" class="w-full" placeholder="Enter your email" />
        </UFormField>
        <UFormField label="Password">
          <UInput
            v-model="password"
            type="password"
            class="w-full"
            placeholder="Enter your password"
          />
        </UFormField>
        <UButton type="submit" :loading="loading" class="flex justify-center mt-2">Login</UButton>
        <USeparator class="my-4" />
        <div class="text-sm text-neutral-500">
          <p>Doesn't have an account yet?</p>
          <p>
            Create new account by clicking
            <router-link to="register" class="text-primary">here.</router-link>
          </p>
        </div>
      </UForm>
    </UPageCard>
  </div>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { useAuthStore } from '@/stores/auth.store'
import { ref } from 'vue'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const toast = useToast()
const router = useRouter()

const email = ref<string>('')
const password = ref<string>('')
const loading = ref<boolean>(false)

const handleSubmit = async () => {
  loading.value = true
  try {
    await authStore.login(email.value, password.value)
    router.push({ name: 'home' })
  } catch (error) {
    const fetchError = error as FetchError<any>

    if (fetchError.statusCode == 401) {
      toast.add({
        title: 'Invalid Credentials',
        description: 'Your email or password is incorrect',
        icon: 'i-lucide-lock',
        color: 'error',
      })
    } else {
      toast.add({
        title: 'Error',
        description: fetchError.data?.message ?? fetchError.message ?? 'Something went wrong',
        icon: 'i-lucide-octagon-x',
        color: 'error',
      })
    }
  } finally {
    loading.value = false
  }
}
</script>
