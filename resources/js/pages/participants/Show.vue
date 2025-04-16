<script setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    participant: {
        type: Object,
    },
});

const breadcrumbs = [
    { title: 'Participants', href: route('participants.index') },
    { title: props.participant.name, href: route('participants.show', props.participant) },
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
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Participant Details</h1>
                            <div class="flex space-x-2">
                                <Link
                                    :href="route('participants.edit', participant.id)"
                                    class="bg-primary hover:bg-primary/90 focus:ring-primary rounded-md px-4 py-2 text-white transition duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                                >
                                    Edit
                                </Link>
                            </div>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-800">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
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
