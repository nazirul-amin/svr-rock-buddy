<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle } from 'lucide-vue-next';

const breadcrumbs = [
    { title: 'Themes', href: route('themes.index') },
    { title: 'Create', href: route('themes.create') },
];

const form = useForm('post', route('themes.store'), {
    name: '',
    from: '',
    to: '',
    poster: '',
    descriptions: '',
});

const submit = () =>
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
</script>

<template>
    <Head title="Create Theme" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Create Theme</h1>
                        </div>
                        <form @submit.prevent="submit" class="max-w-2xl">
                            <div class="mb-4 space-y-2">
                                <Label for="name">Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter theme name"
                                    @change="form.validate('name')"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="mb-4 space-y-2">
                                <Label for="from">From</Label>
                                <Input id="from" v-model="form.from" type="date" @change="form.validate('from')" required />
                                <InputError :message="form.errors.from" />
                            </div>
                            <div class="mb-4 space-y-2">
                                <Label for="to">To</Label>
                                <Input id="to" v-model="form.to" type="date" @change="form.validate('to')" required />
                                <InputError :message="form.errors.to" />
                            </div>
                            <div class="mb-4 space-y-2">
                                <Label for="poster">Poster</Label>
                                <Input
                                    id="poster"
                                    type="file"
                                    accept="image/*"
                                    @change="
                                        (e) => {
                                            form.poster = e.target.files[0];
                                            form.validate('poster');
                                        }
                                    "
                                />
                                <InputError :message="form.errors.poster" />
                            </div>
                            <div class="mb-6 space-y-2">
                                <Label for="descriptions">Descriptions</Label>
                                <Input
                                    id="descriptions"
                                    v-model="form.descriptions"
                                    type="text"
                                    placeholder="Enter descriptions"
                                    @change="form.validate('descriptions')"
                                />
                                <InputError :message="form.errors.descriptions" />
                            </div>
                            <div class="flex items-center justify-end">
                                <Button variant="default" type="submit" :disabled="form.processing" class="cursor-pointer">
                                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                    <span v-else>Create Theme</span>
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
