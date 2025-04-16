<script setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ theme: Object });

const breadcrumbs = [
    { title: 'Themes', href: route('themes.index') },
    { title: props.theme.name, href: route('themes.show', props.theme) },
];

const confirmDelete = () => {
    router.delete(route('themes.destroy', props.theme.id), {
        onFinish: () => {},
    });
};
</script>

<template>
    <Head :title="`${props.theme.name} - Theme Details`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Theme Details</h1>
                            <div class="flex space-x-2">
                                <Link
                                    :href="route('themes.edit', props.theme.id)"
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
                                    <p class="mt-1 text-xl">{{ props.theme.name }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">From</h2>
                                    <p class="mt-1 text-xl">{{ props.theme.from }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">To</h2>
                                    <p class="mt-1 text-xl">{{ props.theme.to }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Poster</h2>
                                    <div class="mt-1">
                                        <img
                                            v-if="props.theme.poster"
                                            :src="`/storage/${props.theme.poster}`"
                                            alt="Poster"
                                            class="h-32 rounded border object-contain"
                                        />
                                        <span v-else class="text-gray-400 italic">No poster uploaded</span>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Descriptions</h2>
                                    <p class="mt-1 text-xl">{{ props.theme.descriptions || '-' }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Created At</h2>
                                    <p class="mt-1 text-xl">{{ props.theme.created_at ? new Date(props.theme.created_at).toLocaleString() : '-' }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Updated At</h2>
                                    <p class="mt-1 text-xl">{{ props.theme.updated_at ? new Date(props.theme.updated_at).toLocaleString() : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <Dialog>
                                <DialogTrigger asChild>
                                    <Button variant="destructive" class="cursor-pointer">Delete Theme</Button>
                                </DialogTrigger>
                                <DialogContent className="sm:max-w-[425px]">
                                    <DialogHeader>
                                        <DialogTitle>Delete theme</DialogTitle>
                                        <DialogDescription>
                                            Are you sure you want to delete this theme? This action cannot be undone.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <DialogFooter>
                                        <Button
                                            variant="secondary"
                                            @click="$event.target.closest('.dialog-content').dispatchEvent(new CustomEvent('close'))"
                                            >Cancel</Button
                                        >
                                        <Button variant="destructive" @click="confirmDelete">Delete</Button>
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
