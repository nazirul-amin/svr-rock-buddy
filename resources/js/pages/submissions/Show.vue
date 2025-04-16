<script setup>
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({ submission: Object });

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
