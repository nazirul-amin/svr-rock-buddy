<script setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs = [{ title: 'Themes', href: route('themes.index') }];

defineProps({
    themes: {
        type: Object,
    },
});

const themeToDelete = ref(null);

const confirmDelete = (theme) => {
    router.delete(route('themes.destroy', theme.id), {
        onFinish: () => {
            themeToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Themes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Themes</h1>
                            <Link
                                :href="route('themes.create')"
                                class="bg-primary hover:bg-primary/90 focus:ring-primary rounded-md px-4 py-2 text-white transition duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                            >
                                Add Theme
                            </Link>
                        </div>
                        <div v-if="themes.data.length === 0" class="text-gray-500">No themes found.</div>
                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            From
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            To
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Poster
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Descriptions
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="dark:bg-card divide-y divide-gray-200 bg-white dark:divide-gray-700">
                                    <tr v-for="theme in themes.data" :key="theme.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ theme.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ theme.from }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ theme.to }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img
                                                v-if="theme.poster"
                                                :src="`/storage/${theme.poster}`"
                                                alt="Poster"
                                                class="h-12 w-12 rounded border object-cover"
                                            />
                                            <span v-else class="text-gray-400 italic">No poster</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ theme.descriptions }}</td>
                                        <td class="px-6 py-4 text-right text-sm whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-2">
                                                <Button
                                                    variant="default"
                                                    class="cursor-pointer"
                                                    @click="
                                                        () => {
                                                            router.visit(route('themes.show', theme.id));
                                                        }
                                                    "
                                                >
                                                    View
                                                </Button>
                                                <Button
                                                    class="cursor-pointer bg-gray-500 text-white transition duration-200 hover:bg-gray-600"
                                                    @click="
                                                        () => {
                                                            router.visit(route('themes.edit', theme.id));
                                                        }
                                                    "
                                                >
                                                    Edit
                                                </Button>
                                                <Dialog>
                                                    <DialogTrigger asChild>
                                                        <Button variant="destructive" class="cursor-pointer" @click="themeToDelete.value = theme"
                                                            >Delete</Button
                                                        >
                                                    </DialogTrigger>
                                                    <DialogContent className="sm:max-w-[425px]">
                                                        <DialogHeader>
                                                            <DialogTitle>Delete theme</DialogTitle>
                                                            <DialogDescription>
                                                                Are you sure you want to delete this theme? This action cannot be undone.
                                                            </DialogDescription>
                                                        </DialogHeader>
                                                        <DialogFooter>
                                                            <Button type="button" variant="destructive" @click="confirmDelete(theme)">Delete</Button>
                                                        </DialogFooter>
                                                    </DialogContent>
                                                </Dialog>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
