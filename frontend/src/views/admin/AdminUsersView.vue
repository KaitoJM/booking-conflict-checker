<template>
  <div class="flex flex-col md:flex-row gap-8">
    <div class="flex-1">
      <AdminUserFilter />
      <UTable :data="data" :columns="columns" class="flex-1" />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { FetchError } from 'ofetch'
import { computed, h, onMounted, ref, resolveComponent } from 'vue'
import type { TableColumn } from '@nuxt/ui'
import { useOverlay } from '@nuxt/ui/runtime/composables/useOverlay.js'
import ConfirmationDialog from '@/components/ConfirmationDialog.vue'
import { useToast } from '@nuxt/ui/runtime/composables/useToast.js'
import { useUserStore } from '@/stores/user.store'
import type { User } from '@/types/user.types'
import AdminUserFilter from '@/components/admin/AdminUserFilter.vue'

const userStore = useUserStore()

const data = computed<User[]>(() => userStore.users)

onMounted(() => {
  userStore.getUsers()
})

const UButton = resolveComponent('UButton')
const UIcon = resolveComponent('UIcon')
const columns: TableColumn<User>[] = [
  {
    accessorKey: 'id',
    header: '#',
    cell: ({ row }) => `#${row.getValue('id')}`,
  },
  {
    accessorKey: 'name',
    header: 'User',
    cell: ({ row }) => {
      return h(
        'div',
        {
          class: 'flex flex-col',
        },
        [
          h('p', { class: 'font-bold' }, row.original.name),
          h('p', { class: 'text-xs' }, row.original.email),
        ],
      )
    },
  },
  {
    accessorKey: 'id',
    header: () => h('div', { class: 'text-right' }, 'Action'),
    cell: ({ row }) => {
      const id = row.original.id
      return h('div', { class: 'flex items-center gap-2 justify-end' }, [
        h(UButton, {
          loading: deleting.value == id,
          variant: 'outline',
          color: 'error',
          size: 'sm',
          icon: 'i-lucide-trash',
          onClick: () => handleDeleteUser(id),
        }),
      ])
    },
  },
]

const overlay = useOverlay()
const deleteModal = overlay.create(ConfirmationDialog)
const handleDeleteUser = (id: string) => {
  deleteModal.open({
    title: 'Delete User',
    message: 'Are you sure you want to delete this user?',
    onOk: () => {
      deleteUser(id)
    },
  })
}

const toast = useToast()
const deleting = ref<string>('')
const deleteUser = async (id: string) => {
  deleting.value = id
  try {
    await userStore.deleteUser(id)
    toast.add({
      title: 'Success',
      description: 'User has been deleted.',
      icon: 'i-lucide-check',
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
    deleting.value = ''
  }
}
</script>
