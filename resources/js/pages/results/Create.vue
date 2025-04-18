<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle } from 'lucide-vue-next';

defineProps({
    themes: {
        type: Object,
        required: true,
    },
});

const breadcrumbs = [
    { title: 'Results', href: route('results.index') },
    { title: 'Create', href: route('results.create') },
];

const form = useForm('post', route('results.store'), {
    name: '',
    is_active: false,
    theme_ids: [],
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Create Results" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Create Results</h1>
                        </div>
                        <form @submit.prevent="submit" class="max-w-2xl">
                            <div class="mb-4 space-y-2">
                                <Label for="name">Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter group name"
                                    @change="form.validate('name')"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="mb-4 space-y-2">
                                <Label for="theme_ids">Themes</Label>
                                <select id="theme_ids" v-model="form.theme_ids" multiple class="input w-full rounded border border-gray-300 p-2">
                                    <option v-for="theme in themes" :key="theme.id" :value="theme.id">
                                        {{ theme.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.theme_ids" />
                            </div>
                            <div class="mb-6 space-y-2">
                                <Label for="is_active">Active</Label>
                                <input id="is_active" type="checkbox" v-model="form.is_active" />
                                <InputError :message="form.errors.is_active" />
                            </div>
                            <Button type="submit" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                                Create
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
