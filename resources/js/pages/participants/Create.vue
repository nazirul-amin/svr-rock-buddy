<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle } from 'lucide-vue-next';

const breadcrumbs = [
  { title: 'Participants', href: route('participants.index') },
  { title: 'Create', href: route('participants.create') }
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
    <Head title="Create Participant" />
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-2xl font-semibold text-primary">Create Participant</h1>
                        </div>

                        <form @submit.prevent="submit" class="max-w-2xl">
                            <div class="mb-4 space-y-2">
                                <Label for="name">Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter participant name"
                                    @change="form.validate('name')"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="mb-6 space-y-2">
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter participant email"
                                    @change="form.validate('email')"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="flex items-center justify-end">
                                <Button variant="default" type="submit" :disabled="form.processing" class="cursor-pointer">
                                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                    <span v-else>Create Participant</span>
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
