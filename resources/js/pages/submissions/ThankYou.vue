<script setup>
defineProps({ theme: Object });
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="dark:bg-card w-full max-w-md rounded bg-white p-8 text-center shadow">
            <h1 class="text-primary mb-4 text-2xl font-bold">Thank you for your submission!</h1>
            <p>
                We have received your entry for <b>{{ theme.name }}</b
                >.
            </p>
            <p class="mt-4">You may close this window.</p>
        </div>
    </div>
</template>
