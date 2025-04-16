<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';

const breadcrumbs = [
  { name: 'Participants', href: route('participants.index'), current: false },
  { name: 'Create', href: route('participants.create'), current: true }
];

const form = useForm('post', route('participants.store'), {
    name: '',
    email: '',
});

const submit = () => form.submit({
    preserveScroll: true,
    onSuccess: () => form.reset(),
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Create Participant" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-2xl font-semibold text-primary">Create Participant</h1>
                            <a
                                :href="route('participants.index')"
                                class="px-4 py-2 bg-gray-300 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 transition duration-200"
                            >
                                Back to List
                            </a>
                        </div>

                        <form @submit.prevent="submit" class="max-w-2xl">
                            <div class="mb-4">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Name</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    @change="form.validate('name')"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-800"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    @change="form.validate('email')"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-gray-800"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="flex items-center">
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">Processing...</span>
                                    <span v-else>Create Participant</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
