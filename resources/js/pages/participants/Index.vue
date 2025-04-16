<script setup>
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

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="dark:bg-card divide-y divide-gray-200 bg-white dark:divide-gray-700">
                                    <tr
                                        v-for="participant in participants.data"
                                        :key="participant.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">{{ participant.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ participant.email }}</td>
                                        <td class="px-6 py-4 text-right text-sm whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-2">
                                                <Button
                                                    variant="default"
                                                    class="cursor-pointer"
                                                    @click="
                                                        () => {
                                                            router.visit(route('participants.show', participant.id));
                                                        }
                                                    "
                                                >
                                                    View
                                                </Button>
                                                <Button
                                                    class="cursor-pointer bg-gray-500 text-white transition duration-200 hover:bg-gray-600"
                                                    @click="
                                                        () => {
                                                            router.visit(route('participants.edit', participant.id));
                                                        }
                                                    "
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
                                                            <Button type="button" variant="destructive" @click="confirmDelete(participant)"
                                                                >Delete</Button
                                                            >
                                                        </DialogFooter>
                                                    </DialogContent>
                                                </Dialog>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!participants.data.length">
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No participants found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6" v-if="participants.links && participants.links.length > 3">
                            <div class="flex items-center justify-between">
                                <div>Showing {{ participants.from }} to {{ participants.to }} of {{ participants.total }} entries</div>
                                <div class="flex gap-1">
                                    <a
                                        v-for="(link, index) in participants.links"
                                        :key="index"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="rounded-md px-3 py-1"
                                        :class="{
                                            'bg-primary text-white': link.active,
                                            'dark:bg-card-foreground bg-gray-100 text-gray-600 dark:text-gray-300': !link.active && link.url,
                                            'cursor-not-allowed bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-500': !link.url,
                                        }"
                                        :tabindex="link.url ? 0 : -1"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
