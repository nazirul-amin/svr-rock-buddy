<script setup>
import Table from '@/components/Table.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const breadcrumbs = [{ title: 'Results', href: route('results.index') }];

defineProps({
    results: {
        type: Object,
    },
});

const confirmDelete = (result) => {
    router.delete(route('results.destroy', result));
};
</script>

<template>
    <Head title="Results" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Results</h1>
                            <Link
                                :href="route('results.create')"
                                class="bg-primary hover:bg-primary/90 focus:ring-primary cursor-pointer rounded-md px-4 py-2 text-white transition duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                            >
                                Add Results
                            </Link>
                        </div>
                        <Table
                            :columns="[
                                { key: 'name', label: 'Name' },
                                { key: 'themes', label: 'Themes' },
                                { key: 'is_active', label: 'Active' },
                            ]"
                            :rows="results.data"
                            :key-field="'id'"
                        >
                            <template #cell-themes="{ row: result }">
                                <ul>
                                    <li v-for="theme in result.themes" :key="theme.id">{{ theme.name }}</li>
                                </ul>
                            </template>
                            <template #cell-is_active="{ row: result }">
                                <Badge :variant="result.is_active ? 'default' : 'destructive'" class="cursor-pointer">{{
                                    result.is_active ? 'Active' : 'Inactive'
                                }}</Badge>
                            </template>
                            <template #actions="{ row: result }">
                                <div class="flex items-center justify-end space-x-2">
                                    <Button variant="default" class="cursor-pointer" @click="() => router.visit(route('results.show', result))">
                                        View
                                    </Button>
                                    <Button
                                        class="cursor-pointer bg-gray-500 text-white transition duration-200 hover:bg-gray-600"
                                        @click="() => router.visit(route('results.edit', result))"
                                    >
                                        Edit
                                    </Button>
                                    <Dialog>
                                        <DialogTrigger asChild>
                                            <Button variant="destructive" class="cursor-pointer">Delete</Button>
                                        </DialogTrigger>
                                        <DialogContent className="sm:max-w-[425px]">
                                            <DialogHeader>
                                                <DialogTitle>Delete Result</DialogTitle>
                                                <DialogDescription>
                                                    Are you sure you want to delete this result? This action cannot be undone.
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter>
                                                <Button type="button" variant="destructive" @click="confirmDelete(result)">Delete</Button>
                                            </DialogFooter>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </template>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
