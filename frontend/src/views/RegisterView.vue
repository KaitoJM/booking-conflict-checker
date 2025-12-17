<template>
  <div class="p-4 flex justify-center items-center h-screen">
    <UPageCard class="w-100" orientation="vertical">
      <UForm @submit.prevent="handleSubmit" class="flex flex-col gap-4 w-full">
        <p class="text-xl font-bold">Welcome!</p>
        <p class="text-sm text-neutral-500">Create an account to get started.</p>
        <div class="flex flex-col gap-2">
          <UFormField label="Name">
            <UInput v-model="name" class="w-full" placeholder="Juan Dela Cruz" />
          </UFormField>
          <UFormField label="Email">
            <UInput v-model="email" class="w-full" placeholder="juandelacruz@example.com" />
          </UFormField>
          <UFormField label="Password">
            <UInput
              v-model="password"
              type="password"
              class="w-full"
              placeholder="Enter your password"
            />
          </UFormField>
          <UFormField label="Confirm Password">
            <UInput
              v-model="cpassword"
              type="password"
              class="w-full"
              placeholder="Enter your password"
            />
          </UFormField>
        </div>
        <UButton type="submit" :loading="loading" class="flex justify-center mt-2"
          >Create Account</UButton
        >
        <USeparator class="my-4" />
        <div class="text-sm text-neutral-500">
          <p>Already have an account?</p>
          <p>
            Login now by clicking
            <router-link to="login" class="text-primary">here.</router-link>
          </p>
        </div>
      </UForm>
    </UPageCard>
  </div>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { ref } from 'vue'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user.store'

const userStore = useUserStore()
const toast = useToast()
const router = useRouter()

const name = ref<string>('')
const email = ref<string>('')
const password = ref<string>('')
const cpassword = ref<string>('')
const loading = ref<boolean>(false)

const handleSubmit = async () => {
  loading.value = true
  if (!validatePassword()) {
    toast.add({
      title: 'Error',
      description: 'Password confirmation did not matched.',
      icon: 'i-lucide-octagon-x',
      color: 'error',
    })

    resetForm()
    loading.value = false
    return
  }

  try {
    await userStore.registerUser(name.value, email.value, password.value)

    toast.add({
      title: 'User successfully registered',
      description: 'Please login to continue.',
      icon: 'i-lucide-octagon-x',
      color: 'success',
    })

    router.push({ name: 'login' })
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

const resetForm = () => {
  name.value = ''
  email.value = ''
  password.value = ''
  cpassword.value = ''
}
const validatePassword = (): boolean => {
  return password.value == cpassword.value
}
</script>
