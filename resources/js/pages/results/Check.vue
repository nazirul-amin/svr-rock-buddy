<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps({
    theme: String,
    result: Object,
});

const form = useForm('post', route('results.check.submit', props.result), {
    email: '',
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (error) => {
            console.error(error);
        },
    });
};
</script>

<template>
    <Head :title="`See Your Results for ${theme}`" />
    <div class="bg-secondary text-muted flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow">
            <h1 class="text-primary mb-4 text-2xl font-bold">See Your Results</h1>
            <p class="mb-6 text-gray-700">
                Enter your email to see your submission and score for <span class="font-semibold">{{ theme?.length == 1 ? theme[0] : theme }}</span
                >.
            </p>
            <form @submit.prevent="submit" class="space-y-4">
                <Input v-model="form.email" type="email" placeholder="Your email" class="!bg-white" required />
                <InputError :message="form.errors.email" />
                <Button :disabled="form.processing" type="submit" class="w-full">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    <span v-else>{{ form.processing ? 'Checking...' : 'See My Result' }}</span>
                </Button>
            </form>
        </div>
    </div>
</template>
