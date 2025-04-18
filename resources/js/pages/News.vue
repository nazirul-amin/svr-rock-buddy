<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem } from '@/components/ui/carousel';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useAudio } from '@/composables/useAudio';
import { launchConfettiParty, launchSadParty } from '@/composables/useParty';
import { Head, router } from '@inertiajs/vue3';
import Autoplay from 'embla-carousel-autoplay';
import { Crown, Frown } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    submissions: Object,
    top3: Array,
    highlightedResult: Object,
    title: String,
});

const showDialog = ref(!!props.highlightedResult);

const highlightData = computed(() => {
    if (props.highlightedResult) {
        return {
            rank: props.highlightedResult.overall_rank,
            total_score: props.highlightedResult.total_score,
            submissions: props.highlightedResult.submissions,
        };
    }
    return null;
});

const { play: playConfetti } = useAudio('/audio/win.mp3');
const { play: playSad } = useAudio('/audio/lose.mp3');

onMounted(() => {
    if (highlightData.value && highlightData.value.rank === 1) {
        showDialog.value = true;
        launchConfettiParty();
        playConfetti();
    } else if (highlightData.value && highlightData.value.rank != 1) {
        launchSadParty();
        playSad();
    }
});
</script>
<template>
    <Head title="News & Highlights" />
    <div class="bg-muted-foreground min-h-screen w-full p-8">
        <div class="mx-auto max-w-4xl">
            <div class="flex justify-between space-x-4">
                <h1 class="text-primary mb-8 text-3xl font-bold">News & Highlights for {{ title?.length == 1 ? title[0] : title }}</h1>
                <Button variant="default" @click="router.visit('/')" class="cursor-pointer">Go Back</Button>
            </div>
            <div class="mb-12">
                <h2 class="text-muted mb-4 text-xl font-semibold">Top Three</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div v-for="(entry, idx) in top3" :key="entry.participant.id" class="flex flex-col items-center rounded-lg bg-white p-4 shadow">
                        <div class="text-primary mb-2 text-2xl font-bold">#{{ idx + 1 }}</div>
                        <Carousel class="relative mb-2 w-full" :key="'overall-' + entry.participant.id + '-carousel'">
                            <CarouselContent>
                                <CarouselItem v-for="(submission, imgIdx) in entry.submissions" :key="imgIdx">
                                    <img :src="submission.image" alt="submission" class="h-48 w-full rounded object-cover" />
                                </CarouselItem>
                            </CarouselContent>
                        </Carousel>
                        <div class="font-semibold text-gray-800">{{ entry?.participant?.name }}</div>
                        <div class="text-gray-500">Total Score: {{ entry?.score }}</div>
                    </div>
                </div>
            </div>
            <h2 class="text-muted mt-12 mb-4 text-xl font-semibold">All Submissions</h2>

            <div class="mx-auto w-full">
                <Carousel
                    class="relative mx-auto w-full"
                    :plugins="[
                        Autoplay({
                            delay: 2000,
                        }),
                    ]"
                >
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
                </Carousel>
            </div>
        </div>
    </div>
    <Dialog v-model:open="showDialog">
        <DialogContent class="max-w-md">
            <DialogHeader>
                <DialogTitle> </DialogTitle>
            </DialogHeader>
            <div v-if="highlightData" class="flex flex-col items-center gap-4">
                <div v-if="highlightData.rank" class="mb-2 text-lg font-semibold">
                    Your Ranking: <span class="font-bold">#{{ highlightData.rank }}</span>
                </div>
                <div v-if="highlightData.total_score !== undefined && highlightData.submissions.length > 1" class="mb-2">
                    Total Score: <span class="font-bold">{{ highlightData.total_score }}</span>
                </div>
                <div class="mt-4 w-full">
                    <div v-for="submission in highlightData.submissions" :key="submission.id" class="mb-4 pb-2">
                        <div class="flex items-center gap-2" :class="{ 'flex-col': highlightData.submissions.length == 1 }">
                            <img
                                v-if="submission.image"
                                :src="submission.image"
                                alt="submission"
                                class="rounded border object-cover"
                                :class="{ 'h-48 w-48': highlightData.submissions.length == 1, 'h-32 w-32': highlightData.submissions.length > 1 }"
                            />
                            <div :class="{ 'flex flex-col items-center': highlightData.submissions.length == 1 }">
                                <div class="font-semibold">Theme: {{ submission.theme?.name || submission.theme?.title }}</div>
                                <div>
                                    Score: <span class="font-bold">{{ submission.score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <p class="inline-flex items-center">
                    {{ highlightData.rank === 1 ? 'Congratulations!' : 'Almost there! Try again next month' }}
                    <Frown class="ml-2 text-blue-400" v-if="highlightData.rank !== 1" />
                </p>
                <span class="inline-flex items-center p-1 text-yellow-500" v-if="highlightData.rank === 1"><Crown class="mr-1" /> Winner</span>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
