<script setup>
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
  participant: {
    type: Object,
  },
});

const breadcrumbs = [
  { title: 'Participants', href: route('participants.index') },
  { title: props.participant.name, href: route('participants.show', props.participant) }
];

const confirmDelete = () => {
  router.delete(route('participants.destroy', props.participant.id), {
    onFinish: () => {},
  });
};
</script>

<template>
  <Head :title="`${participant.name} - Participant Details`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-white">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-2xl font-semibold text-primary">Participant Details</h1>
              <div class="flex space-x-2">
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
              <Dialog>
                <DialogTrigger asChild>
                  <Button variant="destructive" class="cursor-pointer">Delete Participant</Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[425px]">
                  <DialogHeader>
                    <DialogTitle>Delete participant</DialogTitle>
                    <DialogDescription>
                      Are you sure you want to delete this participant? This action cannot be undone.
                    </DialogDescription>
                  </DialogHeader>
                  <DialogFooter>
                    <Button type="button" variant="destructive" @click="confirmDelete(participant)">Delete</Button>
                  </DialogFooter>
                </DialogContent>
              </Dialog>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
