<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
  participant: {
    type: Object,
    required: true
  }
});

const breadcrumbs = [
  { name: 'Participants', href: route('participants.index'), current: false },
  { name: props.participant.name, href: route('participants.show', props.participant), current: false },
  { name: 'Edit', href: route('participants.edit', props.participant), current: true }
];

const form = useForm({
  name: props.participant.name,
  email: props.participant.email,
});

function submit() {
  form.put(route('participants.update', props.participant.id));
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Edit Participant" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-white">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-2xl font-semibold text-primary">Edit Participant</h1>
              <Link
                :href="route('participants.index')"
                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200"
              >
                Back to List
              </Link>
            </div>

            <form @submit.prevent="submit" class="max-w-2xl">
              <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-card-foreground dark:border-gray-600 dark:text-white"
                  required
                />
                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
              </div>

              <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-card-foreground dark:border-gray-600 dark:text-white"
                  required
                />
                <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
              </div>

              <div class="flex items-center justify-end">
                <button
                  type="submit"
                  class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200"
                  :disabled="form.processing"
                >
                  {{ form.processing ? 'Updating...' : 'Update Participant' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
