<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Participant {
  id: number;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
}

const props = defineProps<{
  participant: Participant;
}>();

const breadcrumbs = [
  { name: 'Participants', href: route('participants.index'), current: false },
  { name: props.participant.name, href: route('participants.show', props.participant), current: true }
];

const showDeleteModal = ref(false);

const confirmDelete = () => {
  router.delete(route('participants.destroy', props.participant.id), {
    onFinish: () => {
      showDeleteModal.value = false;
    },
  });
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head :title="`${participant.name} - Participant Details`" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-white">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-2xl font-semibold text-primary">Participant Details</h1>
              <div class="flex space-x-2">
                <Link
                  :href="route('participants.index')"
                  class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200"
                >
                  Back to List
                </Link>
                <Link
                  :href="route('participants.edit', participant.id)"
                  class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200"
                >
                  Edit
                </Link>
              </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Name</h2>
                  <p class="mt-1 text-xl">{{ participant.name }}</p>
                </div>
                <div>
                  <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Email</h2>
                  <p class="mt-1 text-xl">{{ participant.email }}</p>
                </div>
                <div>
                  <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Created At</h2>
                  <p class="mt-1 text-xl">{{ new Date(participant.created_at).toLocaleString() }}</p>
                </div>
                <div>
                  <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Updated At</h2>
                  <p class="mt-1 text-xl">{{ new Date(participant.updated_at).toLocaleString() }}</p>
                </div>
              </div>
            </div>

            <div class="mt-6">
              <Link
                :href="route('participants.destroy', participant.id)"
                method="delete"
                as="button"
                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200"
                onclick="return confirm('Are you sure you want to delete this participant?')"
              >
                Delete Participant
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
      <div class="bg-white dark:bg-card rounded-lg shadow-xl p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4 dark:text-white">Confirm Deletion</h3>
        <p class="mb-6 dark:text-muted-foreground">Are you sure you want to delete this participant? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="px-4 py-2 bg-destructive text-white rounded-md hover:bg-destructive/90 transition duration-200"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
