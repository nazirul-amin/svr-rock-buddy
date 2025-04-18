<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious, type CarouselApi } from '@/components/ui/carousel';
import { Head, router } from '@inertiajs/vue3';
import { watchOnce } from '@vueuse/core';
import { ref } from 'vue';

defineProps({
    submissions: Array,
    theme: Object,
    top3: Array,
});

const emblaMainApi = ref<CarouselApi>();
const emblaThumbnailApi = ref<CarouselApi>();
const selectedIndex = ref(0);

function onSelect() {
    if (!emblaMainApi.value || !emblaThumbnailApi.value) return;
    selectedIndex.value = emblaMainApi.value.selectedScrollSnap();
    emblaThumbnailApi.value.scrollTo(emblaMainApi.value.selectedScrollSnap());
}

function onThumbClick(index: number) {
    if (!emblaMainApi.value || !emblaThumbnailApi.value) return;
    emblaMainApi.value.scrollTo(index);
}

watchOnce(emblaMainApi, (emblaMainApi) => {
    if (!emblaMainApi) return;

    onSelect();
    emblaMainApi.on('select', onSelect);
    emblaMainApi.on('reInit', onSelect);
});
</script>
<template>
    <Head title="News & Highlights" />
    <div class="bg-muted-foreground min-h-screen w-full p-8">
        <div class="mx-auto max-w-4xl">
            <div class="flex justify-between space-x-4">
                <h1 class="text-primary mb-8 text-3xl font-bold">News & Highlights for {{ theme?.name }}</h1>
                <Button variant="default" @click="router.visit('/')" class="cursor-pointer">Go Back</Button>
            </div>
            <div v-if="theme" class="mb-12">
                <h2 class="text-muted mb-4 text-xl font-semibold">Top 3 Entry</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div v-for="(entry, idx) in top3" :key="entry.id" class="flex flex-col items-center rounded-lg bg-white p-4 shadow">
                        <div class="text-primary mb-2 text-2xl font-bold">#{{ idx + 1 }}</div>
                        <img :src="entry.image" alt="submission" class="mb-2 h-48 w-full rounded object-cover" />
                        <div class="font-semibold text-gray-800">{{ entry.participant?.name }}</div>
                        <div class="text-gray-500">Score: {{ entry.score }}</div>
                    </div>
                </div>
            </div>
            <h2 class="text-muted mt-12 mb-4 text-xl font-semibold">All Submissions</h2>

            <div class="mx-auto w-full">
                <Carousel class="relative mx-auto w-3/4" @init-api="(val) => (emblaMainApi = val)">
                    <CarouselContent>
                        <CarouselItem v-for="(submission, index) in submissions" :key="index">
                            <div class="p-1">
                                <Card>
                                    <CardContent class="flex items-center justify-center">
                                        <img :src="submission.image" alt="submission" class="w-full rounded object-cover" />
                                    </CardContent>
                                </Card>
                            </div>
                        </CarouselItem>
                    </CarouselContent>
                    <CarouselPrevious />
                    <CarouselNext />
                </Carousel>

                <Carousel class="relative w-full" @init-api="(val) => (emblaThumbnailApi = val)">
                    <CarouselContent class="ml-0 flex gap-1">
                        <CarouselItem
                            v-for="(submission, index) in submissions"
                            :key="index"
                            class="basis-1/4 cursor-pointer pl-0"
                            @click="onThumbClick(index)"
                        >
                            <div class="p-1" :class="index === selectedIndex ? '' : 'opacity-50'">
                                <Card>
                                    <CardContent class="flex aspect-square items-center justify-center p-6">
                                        <img :src="submission.image" alt="submission" class="mb-2 h-48 w-full rounded object-cover" />
                                    </CardContent>
                                </Card>
                            </div>
                        </CarouselItem>
                    </CarouselContent>
                </Carousel>
            </div>
        </div>
    </div>
</template>
