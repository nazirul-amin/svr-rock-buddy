<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps({
    result: {
        type: Object,
        required: true,
    },
    themes: {
        type: Array,
        required: true,
    },
});

const breadcrumbs = [
    { title: 'Results', href: route('results.index') },
    { title: props.result.name, href: route('results.show', props.result) },
    { title: 'Edit', href: route('results.edit', props.result) },
];

const form = useForm('put', route('results.update', props.result), {
    name: props.result.name,
    is_active: props.result.is_active ? true : false,
    theme_ids: props.result.themes.map((theme) => theme.id) || [],
});

const submit = () =>
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
</script>

<template>
    <Head title="Edit Results" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Edit Results</h1>
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
                            <div class="mb-6 space-y-2">
                                <Label for="is_active">Active</Label>
                                <Checkbox id="is_active" v-model="form.is_active" :tabindex="4" />
                                <InputError :message="form.errors.is_active" />
                            </div>
                            <div class="mb-6 space-y-2">
                                <Label for="theme_ids">Themes</Label>
                                <select id="theme_ids" v-model="form.theme_ids" multiple class="input w-full rounded border border-gray-300 p-2">
                                    <option v-for="theme in themes" :key="theme.id" :value="theme.id">
                                        {{ theme.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.theme_ids" />
                            </div>
                            <div class="mb-4 flex space-x-2">
                                <Button type="submit" :disabled="form.processing">
                                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                                    Save Changes
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
