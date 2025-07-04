<template>
  <div class="max-w-md mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Adicionar Contato</h2>
    <form @submit.prevent="submit">
      <!-- Nome -->
      <div class="mb-4">
        <label for="name" class="block mb-1 font-medium text-gray-700">Nome completo</label>
        <input
          v-model="form.name"
          type="text"
          id="name"
          :class="inputClass(errors.name)"
          placeholder="Ex: João Silva"
        />
        <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          id="email"
          :class="inputClass(errors.email)"
          placeholder="Ex: joao@email.com"
        />
        <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</p>
      </div>

      <!-- Telefone -->
      <div class="mb-6">
        <label for="phone" class="block mb-1 font-medium text-gray-700">Telefone</label>
        <input
          v-model="form.phone"
          @input="formatPhone"
          type="text"
          id="phone"
          :class="inputClass(errors.phone)"
          placeholder="(41) 98899-4422"
        />
        <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</p>
      </div>

      <!-- Botão -->
      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
      >
        Adicionar Contato
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const form = reactive({
  name: '',
  email: '',
  phone: '',
})

const errors = reactive({})

function formatPhone(event) {
  let input = event.target.value.replace(/\D/g, '')
  input = input.substring(0, 11)
  if (input.length <= 10) {
    input = input.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3')
  } else {
    input = input.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3')
  }
  form.phone = input
}

function inputClass(hasError) {
  return [
    'w-full border rounded px-3 py-2 focus:outline-none focus:ring',
    hasError
      ? 'border-red-500 focus:ring-red-300'
      : 'border-gray-300 focus:ring-blue-300',
  ].join(' ')
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

function submit() {
  Object.keys(errors).forEach((key) => (errors[key] = null))

  let hasError = false
  if (!form.name.trim().includes(' ')) {
    errors.name = 'Informe o nome completo'
    hasError = true
  }
  if (!isValidEmail(form.email)) {
    errors.email = 'Informe um email válido'
    hasError = true
  }
  const digits = form.phone.replace(/\D/g, '')
  if (digits.length !== 11) {
    errors.phone = 'O telefone deve ter 11 dígitos'
    hasError = true
  }
  if (hasError) return

  Inertia.post('/contacts', form, {
    onError: (err) => Object.assign(errors, err),
    onSuccess: () => {
      Inertia.visit('/contacts') // navegação SPA sem reload total
    },
  })
}
</script>
