<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs = [
    { title: 'Participants', href: route('participants.index') },
    { title: 'Create', href: route('participants.create') },
];

const form = useForm('post', route('participants.store'), {
    name: '',
    email: '',
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const bulkFile = ref(null);
const bulkProcessing = ref(false);
const bulkError = ref('');

const submitBulk = () => {
    bulkProcessing.value = true;
    bulkError.value = '';
    const formData = new FormData();
    formData.append('file', bulkFile.value);
    router.post(route('participants.bulk'), formData, {
        forceFormData: true,
        onFinish: () => {
            bulkProcessing.value = false;
        },
        onSuccess: () => {
            bulkFile.value = null;
        },
        onError: (errors) => {
            bulkError.value = errors.file || 'Bulk upload failed';
        },
    });
};
</script>

<template>
    <Head title="Create Participant" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="dark:bg-card overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-white">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-primary text-2xl font-semibold">Create Participant</h1>
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

                        <!-- Bulk Registration Form -->
                        <form @submit.prevent="submitBulk" enctype="multipart/form-data" class="mt-8 max-w-2xl">
                            <div class="mb-4 space-y-2">
                                <Label for="bulk_file">Bulk Register (CSV or Excel)</Label>
                                <Input
                                    id="bulk_file"
                                    type="file"
                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                    @change="(e) => (bulkFile = e.target.files[0])"
                                    required
                                />
                            </div>
                            <Button variant="default" type="submit" :disabled="bulkProcessing" class="cursor-pointer">
                                <LoaderCircle v-if="bulkProcessing" class="h-4 w-4 animate-spin" />
                                <span v-else>Bulk Register</span>
                            </Button>
                            <InputError :message="bulkError" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
