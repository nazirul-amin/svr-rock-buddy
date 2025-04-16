<script setup>
import Pagination from '@/components/Pagination.vue';
import Table from '@/components/Table.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs = [{ title: 'Participants', href: route('participants.index') }];

defineProps({
    participants: {
        type: Object,
    },
});

const participantToDelete = ref(null);

const confirmDelete = (participant) => {
    router.delete(route('participants.destroy', participant), {
        onFinish: () => {
            participantToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Participants" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Participants</h1>
                            <Link
                                :href="route('participants.create')"
                                class="bg-primary hover:bg-primary/90 focus:ring-primary rounded-md px-4 py-2 text-white transition duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                            >
                                Add Participant
                            </Link>
                        </div>

                        <Table
                            :columns="[
                                { key: 'name', label: 'Name' },
                                { key: 'email', label: 'Email' },
                            ]"
                            :rows="participants.data"
                            :key-field="'id'"
                        >
                            <template #actions="{ row: participant }">
                                <div class="flex items-center justify-end space-x-2">
                                    <Button
                                        variant="default"
                                        class="cursor-pointer"
                                        @click="() => router.visit(route('participants.show', participant.id))"
                                    >
                                        View
                                    </Button>
                                    <Button
                                        class="cursor-pointer bg-gray-500 text-white transition duration-200 hover:bg-gray-600"
                                        @click="() => router.visit(route('participants.edit', participant.id))"
                                    >
                                        Edit
                                    </Button>
                                    <Dialog>
                                        <DialogTrigger asChild>
                                            <Button variant="destructive" class="cursor-pointer">Delete</Button>
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
                            </template>
                        </Table>
                        <Pagination :meta="participants" :links="participants.links" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
