<script setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ result: Object });

const breadcrumbs = [
    { title: 'Results', href: route('results.index') },
    { title: props.result.name, href: route('results.show', props.result) },
];

const showCopiedToast = ref(false);
const copiedMessage = ref('');

const copyLink = (route, label = 'Link') => {
    const url = route;
    const fullUrl = window.location.origin + url;
    const tempInput = document.createElement('input');
    tempInput.value = fullUrl;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    copiedMessage.value = `${label} copied!`;
    showCopiedToast.value = true;
    setTimeout(() => {
        showCopiedToast.value = false;
    }, 1500);
};

const confirmDelete = () => {
    router.delete(route('results.destroy', props.result.id), {
        onFinish: () => {},
    });
};
</script>

<template>
    <Head :title="`${result.name} - Result Details`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Result Details</h1>
                            <div class="flex space-x-2">
                                <Link
                                    :href="route('results.edit', result.id)"
                                    class="bg-primary hover:bg-primary/90 focus:ring-primary rounded-md px-4 py-2 text-white transition duration-200 focus:ring-2 focus:ring-offset-2 focus:outline-none"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="copyLink(route('results.check', result, false), 'Result link')"
                                    class="cursor-pointer rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:outline-none"
                                    type="button"
                                >
                                    Copy Result Link
                                </button>
                                <a
                                    :href="route('results.check', result)"
                                    target="_blank"
                                    rel="noopener"
                                    class="cursor-pointer rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:outline-none"
                                >
                                    Open Result
                                </a>
                            </div>
                            <Dialog>
                                <DialogContent>
                                    <DialogHeader>
                                        <DialogTitle>Delete Result</DialogTitle>
                                    </DialogHeader>
                                    <DialogDescription>Are you sure you want to delete this result?</DialogDescription>
                                    <DialogFooter>
                                        <Button variant="secondary" @click="$emit('close')">Cancel</Button>
                                        <Button variant="destructive" @click="confirmDelete">Delete</Button>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-800">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Name</h2>
                                    <p class="mt-1 text-xl">{{ result.name }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Status</h2>
                                    <p class="mt-1 text-xl">
                                        <span :class="result.is_active ? 'text-green-600' : 'text-red-600'">
                                            {{ result.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Themes</h2>
                                    <ul class="mt-1 text-xl">
                                        <li v-for="theme in result.themes" :key="theme.id">{{ theme.name }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <transition name="fade">
            <div v-if="showCopiedToast" class="bg-primary fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded px-4 py-2 text-white shadow-lg">
                {{ copiedMessage }}
            </div>
        </transition>
    </AppLayout>
</template>
