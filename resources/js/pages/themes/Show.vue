<script setup>
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ theme: Object, submissions: Array });

const breadcrumbs = [
    { title: 'Themes', href: route('themes.index') },
    { title: props.theme.name, href: route('themes.show', props.theme) },
];

const confirmDelete = () => {
    router.delete(route('themes.destroy', props.theme.id), {
        onFinish: () => {},
    });
};

const copyPublicLink = () => {
    const url = route('submissions.public.form', props.theme.id, false);
    const fullUrl = window.location.origin + url;
    const tempInput = document.createElement('input');
    tempInput.value = fullUrl;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    alert('Submission link copied to clipboard!');
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
                                <button
                                    @click="copyPublicLink"
                                    class="rounded-md bg-green-600 px-4 py-2 text-white transition duration-200 hover:bg-green-700 focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:outline-none"
                                    type="button"
                                >
                                    Copy Submission Link
                                </button>
                                <a
                                    :href="route('submissions.public.form', props.theme.id)"
                                    target="_blank"
                                    rel="noopener"
                                    class="rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:outline-none"
                                >
                                    Open Submission Form
                                </a>
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
                    <!-- Submissions List -->
                    <div v-if="props.submissions && props.submissions.length" class="mt-12 p-6">
                        <h2 class="mb-4 text-xl font-semibold">Submissions</h2>
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Participant Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Score
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Submitted At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900">
                                    <tr v-for="submission in props.submissions" :key="submission.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ submission.participant?.name || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ submission.participant?.email || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ submission.participant?.score || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ submission.created_at ? new Date(submission.created_at).toLocaleString() : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <Button
                                                variant="default"
                                                class="cursor-pointer"
                                                @click="() => router.visit(route('submissions.show', submission.id))"
                                            >
                                                View
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-else class="mt-12 p-6 text-gray-500 dark:text-gray-400">No submissions yet.</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
