<script setup>
defineProps({
    meta: Object,
    links: Array,
});
</script>

<template>
    <div class="mt-6" v-if="links && links.length > 3">
        <div class="flex items-center justify-between">
            <div>Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} entries</div>
            <div class="flex gap-1">
                <a
                    v-for="(link, index) in links"
                    :key="index"
                    :href="link.url"
                    v-html="link.label"
                    class="rounded-md px-3 py-1"
                    :class="{
                        'bg-primary text-white': link.active,
                        'dark:bg-card-foreground bg-gray-100 text-gray-600 dark:text-gray-300': !link.active && link.url,
                        'cursor-not-allowed bg-gray-50 text-gray-400 dark:bg-gray-800 dark:text-gray-500': !link.url,
                    }"
                    :tabindex="link.url ? 0 : -1"
                />
            </div>
        </div>
    </div>
</template>
