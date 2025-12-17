import { $fetch, type FetchError } from 'ofetch'
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type { User } from '@/types/user.types'
import type { ApiError, ApiSuccess } from '@/types/api-response.type'
import { useAuthStore } from './auth.store'

export const useUserStore = defineStore('userStore', () => {
  const users = ref<User[]>([])
  const authStore = useAuthStore()

  const getUsers = async (search: string | null = null) => {
    try {
      const res = await $fetch<ApiSuccess<User[]>>(`${import.meta.env.VITE_API_URL}/users`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          Accept: 'application/json',
        },
        params: { search },
      })
      users.value = res.data
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'User retrieval failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }

  const registerUser = async (name: string, email: string, password: string) => {
    try {
      const res = await $fetch<ApiSuccess<User>>(`${import.meta.env.VITE_API_URL}/register`, {
        method: 'POST',
        body: { name, email, password },
      })
      users.value.push(res.data)
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'User creation failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }

  const deleteUser = async (id: string) => {
    try {
      const res = await $fetch<ApiSuccess<User>>(`${import.meta.env.VITE_API_URL}/users/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          Accept: 'application/json',
        },
      })
      getUsers()
    } catch (error) {
      const err = error as FetchError<any>

      throw {
        message: err.data?.message ?? 'User deletion failed',
        errors: err.data?.errors,
        statusCode: err.status,
      } as ApiError
    }
  }

  return { users, getUsers, registerUser, deleteUser }
})
