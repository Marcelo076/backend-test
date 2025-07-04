<template>
  <div class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Lista de Contatos</h1>
      <InertiaLink
        href="/contacts/create"
        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded shadow"
      >
        + Adicionar Contato
      </InertiaLink>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full table-auto">
        <thead
          class="bg-gray-100 text-left text-sm font-semibold text-gray-700 uppercase"
        >
          <tr>
            <th class="px-6 py-3">Nome</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Telefone</th>
            <th class="px-6 py-3 text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="contact in contactsLocal.data"
            :key="contact.id"
            class="border-b hover:bg-gray-50"
          >
            <td class="px-6 py-4">{{ contact.name }}</td>
            <td class="px-6 py-4">{{ contact.email }}</td>
            <td class="px-6 py-4">{{ contact.phone }}</td>
            <td class="px-6 py-4 text-center">
              <button
                @click="removeContact(contact.id)"
                class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-1 rounded"
              >
                Remover
              </button>
            </td>
          </tr>
          <tr v-if="contactsLocal.data.length === 0">
            <td colspan="4" class="text-center py-4">Nenhum contato encontrado.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-4 flex justify-between text-sm text-gray-600">
      <span>Página {{ contactsLocal.current_page }} de {{ contactsLocal.last_page }}</span>
      <div class="space-x-2">
        <button
          v-if="contactsLocal.prev_page_url"
          @click="goTo(contactsLocal.prev_page_url)"
          class="text-blue-600 hover:underline"
        >
          Anterior
        </button>
        <button
          v-if="contactsLocal.next_page_url"
          @click="goTo(contactsLocal.next_page_url)"
          class="text-blue-600 hover:underline"
        >
          Próxima
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link as InertiaLink } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  contacts: Object,
})

// Criamos uma cópia reativa local dos contatos para manipular direto
const contactsLocal = ref({ ...props.contacts })

// Sempre que props.contacts mudar (ex: navegação), atualizamos local
watch(() => props.contacts, (newContacts) => {
  contactsLocal.value = { ...newContacts }
})

function removeContact(id) {
  if (!confirm('Tem certeza que deseja remover este contato?')) {
    return
  }

  Inertia.delete(`/contacts/${id}`, {
    onSuccess: () => {
      // Remove localmente da lista para atualizar UI instantaneamente
      contactsLocal.value.data = contactsLocal.value.data.filter(
        (c) => c.id !== id
      )
    },
    onError: () => {
      alert('Erro ao tentar remover o contato.')
    },
  })
}

function goTo(url) {
  Inertia.visit(url)
}
</script>
