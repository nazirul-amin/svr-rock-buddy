<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

import InputError from '@/components/InputError.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';

const props = defineProps({ submission: Object, scores: Array });

const form = useForm('post', route('submissions.score', props.submission), {
    score: '',
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

function getExtension(path) {
    return path ? path.split('.').pop().toLowerCase() : '';
}
function isImage(path) {
    return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(getExtension(path));
}
function isVideo(path) {
    return ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'].includes(getExtension(path));
}

const breadcrumbs = [
    { title: 'Themes', href: route('themes.index') },
    { title: props.submission.theme?.name || 'Theme', href: route('themes.show', props.submission.theme_id) },
    { title: 'Submission', href: '#' },
];
</script>

<template>
    <Head :title="`${props.submission.theme?.name || 'Theme'} Submission`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto w-full sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Submission Details</h1>
                            <Button
                                variant="default"
                                class="cursor-pointer"
                                @click="() => router.visit(route('themes.show', props.submission.theme_id))"
                            >
                                &larr; Back to Theme
                            </Button>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-6 dark:bg-gray-800">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Participant Name</h2>
                                    <p class="mt-1 text-xl">{{ props.submission.participant?.name || '-' }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Participant Email</h2>
                                    <p class="mt-1 text-xl">{{ props.submission.participant?.email || '-' }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Theme</h2>
                                    <p class="mt-1 text-xl">{{ props.submission.theme?.name || '-' }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Score</h2>
                                    <p class="mt-1 text-xl">{{ props.submission.score ?? '-' }}</p>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Submitted At</h2>
                                    <p class="mt-1 text-xl">
                                        {{ props.submission.created_at ? new Date(props.submission.created_at).toLocaleString() : '-' }}
                                    </p>
                                </div>
                            </div>
                            <div v-if="props.submission.path">
                                <h2 class="text-lg font-medium text-gray-600 dark:text-gray-300">Attachment</h2>
                                <div class="mt-2">
                                    <template v-if="isImage(props.submission.path)">
                                        <img
                                            :src="`/storage/${props.submission.path}`"
                                            alt="Submission Image"
                                            class="max-h-96 max-w-full rounded border"
                                        />
                                    </template>
                                    <template v-else-if="isVideo(props.submission.path)">
                                        <video :src="`/storage/${props.submission.path}`" controls class="max-h-96 max-w-full rounded border" />
                                    </template>
                                    <template v-else>
                                        <a :href="`/storage/${props.submission.path}`" target="_blank" class="text-blue-600 underline"
                                            >Download/View</a
                                        >
                                    </template>
                                </div>
                            </div>

                            <!-- Scoring List -->
                            <div class="mt-8">
                                <div class="mb-4 flex justify-between p-2">
                                    <h2 class="mb-2 text-lg font-semibold">Markah</h2>
                                    <Dialog>
                                        <DialogTrigger asChild>
                                            <Button variant="default" class="cursor-pointer">Add Score</Button>
                                        </DialogTrigger>
                                        <DialogContent className="sm:max-w-[425px]">
                                            <DialogHeader>
                                                <DialogTitle>Score Submission</DialogTitle>
                                                <DialogDescription> Please enter a score for this submission. </DialogDescription>
                                                <Input
                                                    v-model="form.score"
                                                    type="number"
                                                    min="0"
                                                    max="15"
                                                    step="0.01"
                                                    class="w-full"
                                                    placeholder="Enter score (e.g. 9.5)"
                                                    @change="form.validate('score')"
                                                    required
                                                />
                                                <InputError :message="form.errors.score" />
                                            </DialogHeader>
                                            <DialogFooter>
                                                <Button type="button" variant="destructive" @click="submit()" class="cursor-pointer"
                                                    >Add Score</Button
                                                >
                                            </DialogFooter>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 rounded-lg bg-white dark:divide-gray-700 dark:bg-gray-900">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-300">User</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-300">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="score in props.scores" :key="score.id">
                                            <td class="px-4 py-2 whitespace-nowrap">{{ score.user.name }} ({{ score.user.email }})</td>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ score.score }}</td>
                                        </tr>
                                        <tr v-if="props.scores.length === 0">
                                            <td colspan="2" class="px-4 py-2 text-center text-gray-400">No scores yet.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
