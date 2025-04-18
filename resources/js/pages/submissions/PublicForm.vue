<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { computed, ref } from 'vue';

const props = defineProps({
    theme: Object,
});

const form = useForm('post', route('submissions.public.store', props.theme), {
    email: '',
    file: null,
});

const submit = () =>
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (error) => {
            console.error(error);
        },
    });

const filePreview = ref(null);
const isImage = computed(() => {
    if (!form.file) return false;
    return form.file.type.startsWith('image/');
});

function onFileChange(e) {
    const file = e.target.files[0];
    form.file = file;
    if (file) {
        filePreview.value = URL.createObjectURL(file);
    } else {
        filePreview.value = null;
    }
}
</script>

<template>
    <Head :title="`Submit Entry for ${theme.name}`" />
    <div class="flex min-h-screen items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="dark:bg-card w-full max-w-md rounded bg-white p-8 shadow">
            <h1 class="text-primary mb-4 text-2xl font-bold">Submit Entry for {{ theme.name }}</h1>
            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="mb-1 block font-medium">Your Email</label>
                    <Input v-model="form.email" type="email" placeholder="Email" required @change="form.validate('email')" />
                    <InputError :message="form.errors.email" />
                </div>
                <div>
                    <label class="mb-1 block font-medium">Upload Image/Video</label>
                    <Input type="file" accept="image/*,video/*" @change="onFileChange" required />
                    <InputError :message="form.errors.file" />
                    <div v-if="filePreview" class="mt-2">
                        <img v-if="isImage" :src="filePreview" class="mb-2 max-h-48 rounded border" />
                        <video v-else controls :src="filePreview" class="mb-2 max-h-48 rounded border" />
                    </div>
                </div>
                <Button variant="default" type="submit" :disabled="form.processing" class="w-full cursor-pointer">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    <span v-else>Submit</span>
                </Button>
            </form>
        </div>
    </div>
</template>
