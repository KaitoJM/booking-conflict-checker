<template>
  <div class="p-4 flex justify-center items-center h-screen">
    <UPageCard class="w-100" orientation="vertical">
      <UForm class="flex flex-col gap-4 w-full">
        <p class="text-xl font-bold">Welcome!</p>
        <p class="text-sm text-neutral-500">Create an account to get started.</p>
        <div class="flex flex-col gap-2">
          <UFormField label="Name">
            <UInput class="w-full" placeholder="Juan Dela Cruz" />
          </UFormField>
          <UFormField label="Email">
            <UInput class="w-full" placeholder="juandelacruz@example.com" />
          </UFormField>
          <UFormField label="Password">
            <UInput type="password" class="w-full" placeholder="Enter your password" />
          </UFormField>
        </div>
        <UButton class="flex justify-center mt-2">Create Account</UButton>
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
