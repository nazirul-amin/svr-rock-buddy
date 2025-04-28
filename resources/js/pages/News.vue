<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem } from '@/components/ui/carousel';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useAudio } from '@/composables/useAudio';
import { launchConfettiParty, launchSadParty } from '@/composables/useParty';
import { Head } from '@inertiajs/vue3';
import Autoplay from 'embla-carousel-autoplay';
import { Crown, Frown } from 'lucide-vue-next';
import { marked } from 'marked';
import { computed, onMounted, ref } from 'vue';

interface SubmissionItem {
    id: number;
    image: string | null;
    theme: { id: string; name: string };
    score: number;
}
interface Top3Entry {
    participant: { id: string; name: string };
    submissions: SubmissionItem[];
    score: number;
}
interface HighlightedResult {
    overall_rank: number;
    total_score: number;
    submissions: SubmissionItem[];
    suggestion?: string;
}

const props = defineProps<{
    submissions: SubmissionItem[];
    top3: Top3Entry[];
    highlightedResult: HighlightedResult | null;
    title: string | string[];
}>();

const { submissions, top3, highlightedResult, title } = props;

const showDialog = ref(!!highlightedResult);

const highlightData = computed(() => {
    if (highlightedResult) {
        return {
            rank: highlightedResult.overall_rank,
            total_score: highlightedResult.total_score,
            submissions: highlightedResult.submissions,
            suggestion: highlightedResult.suggestion ?? null,
        };
    }
    return null;
});

const { play: playConfetti } = useAudio('/audio/win.mp3');
const { play: playSad } = useAudio('/audio/lose.mp3');

const scoreClass = (score: number): string => {
    if (score >= 8) return 'text-green-500';
    if (score >= 4) return 'text-yellow-500';
    return 'text-red-500';
};

const isVideo = (url: string | null): boolean => !!url && /\.(mp4|webm|ogg|mov)$/i.test(url);

// render markdown suggestions
const formatMarkdown = (md: string): string => marked.parse(md);

onMounted(() => {
    if (highlightData.value && highlightData.value.rank === 1) {
        showDialog.value = true;
        setTimeout(() => launchConfettiParty(), 2000);
        playConfetti();
    } else if (highlightData.value && highlightData.value.rank != 1) {
        launchSadParty();
        playSad();
    }
});
</script>
<template>
    <Head title="News & Highlights" />
    <div class="bg-secondary min-h-screen w-full px-4 py-6">
        <div class="mx-auto max-w-4xl">
            <section class="mb-8 rounded-lg bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 p-6 text-center text-white">
                <h2 class="mb-2 text-2xl font-extrabold md:text-3xl">Rock Buddy Highlights</h2>
                <blockquote class="italic">Dive into the community’s best submissions and get inspired!</blockquote>
            </section>
            <div class="flex flex-col justify-between space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                <h1 class="mb-6 text-2xl font-bold text-white drop-shadow-md md:text-3xl">{{ title?.length == 1 ? title[0] : title }}</h1>
            </div>
            <div class="mb-8">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                    <div
                        v-for="(entry, idx) in top3"
                        :key="entry.participant.id"
                        :class="[
                            'flex flex-col items-center rounded-lg bg-white p-3 shadow-md transition-transform hover:scale-105 hover:shadow-xl',
                            idx === 0 ? 'ring-4 ring-yellow-400' : '',
                        ]"
                    >
                        <div class="text-primary mb-2 text-2xl font-bold">#{{ idx + 1 }}</div>
                        <Carousel class="relative mb-2 w-full" :key="'overall-' + entry.participant.id + '-carousel'">
                            <CarouselContent>
                                <CarouselItem v-for="(submission, imgIdx) in entry.submissions" :key="imgIdx">
                                    <template v-if="isVideo(submission.image)">
                                        <video :src="submission.image" controls class="h-48 w-full rounded object-cover shadow" />
                                    </template>
                                    <template v-else>
                                        <img :src="submission.image" alt="submission" class="h-48 w-full rounded object-cover shadow" />
                                    </template>
                                </CarouselItem>
                            </CarouselContent>
                        </Carousel>
                        <div class="font-semibold text-gray-800">{{ entry?.participant?.name }}</div>
                        <div class="text-gray-500">Total Score: {{ entry?.score }}</div>
                    </div>
                </div>
            </div>

            <div class="mx-auto mb-8 w-full">
                <Carousel
                    class="relative mx-auto w-full"
                    :plugins="[
                        Autoplay({
                            delay: 5000,
                        }),
                    ]"
                >
                    <CarouselContent>
                        <CarouselItem v-for="(submission, index) in submissions" :key="index" class="flex-shrink-0">
                            <div
                                class="mx-auto h-auto w-full transform p-2 transition-transform duration-300 hover:scale-105 hover:shadow-lg sm:h-[600px] sm:w-[600px]"
                            >
                                <Card class="h-full">
                                    <CardContent
                                        class="flex h-full w-full transform items-center justify-center transition-transform hover:scale-105"
                                    >
                                        <template v-if="isVideo(submission.image)">
                                            <video :src="submission.image" controls class="h-full w-full rounded object-cover" />
                                        </template>
                                        <template v-else>
                                            <img :src="submission.image" alt="submission" class="h-full w-full rounded object-cover" />
                                        </template>
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
        <DialogContent class="mx-4 max-h-[90vh] w-full overflow-y-auto sm:mx-auto sm:max-w-2xl">
            <DialogHeader>
                <DialogTitle class="flex items-center space-x-2">
                    <Crown class="text-yellow-500" v-if="highlightData.rank === 1" />
                    <Frown class="text-blue-500" v-else />
                    <span class="text-xl font-semibold">{{ highlightData.rank === 1 ? 'You won!' : 'Too bad' }}</span>
                </DialogTitle>
            </DialogHeader>
            <div v-if="highlightData" class="flex w-full flex-col items-start space-y-4 p-4 sm:flex-row sm:space-y-0 sm:space-x-8">
                <div class="flex-1 space-y-4 p-4">
                    <div v-for="submission in highlightData.submissions" :key="submission.id" class="mb-4 pb-2">
                        <div class="flex items-center gap-2" :class="{ 'flex-col': highlightData.submissions.length == 1 }">
                            <template v-if="isVideo(submission.image)">
                                <video
                                    :src="submission.image"
                                    controls
                                    class="rounded border object-cover"
                                    :class="{ 'h-48 w-48': highlightData.submissions.length == 1, 'h-32 w-32': highlightData.submissions.length > 1 }"
                                />
                            </template>
                            <template v-else>
                                <img
                                    v-if="submission.image"
                                    :src="submission.image"
                                    alt="submission"
                                    class="rounded border object-cover"
                                    :class="{ 'h-48 w-48': highlightData.submissions.length == 1, 'h-32 w-32': highlightData.submissions.length > 1 }"
                                />
                            </template>
                            <div :class="{ 'flex flex-col items-center': highlightData.submissions.length == 1 }">
                                <div class="font-semibold">Theme: {{ submission.theme?.name }}</div>
                                <div>
                                    Score: <span class="font-bold" :class="scoreClass(submission.score)">{{ submission.score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-1 flex-col justify-between p-4">
                    <div>
                        <div v-if="highlightData.rank" class="mb-2 text-2xl font-semibold">
                            Your Ranking: <span class="font-bold">#{{ highlightData.rank }}</span>
                        </div>
                        <div v-if="highlightData.total_score !== undefined && highlightData.submissions.length > 1" class="mb-2">
                            Total Score: <span class="font-bold" :class="scoreClass(highlightData.total_score)">{{ highlightData.total_score }}</span>
                        </div>
                    </div>
                    <div v-if="highlightData.rank === 1" class="max-h-80 overflow-y-auto rounded bg-green-50 p-4 whitespace-pre-line text-black">
                        <h3 class="mb-2 font-semibold">Next Steps</h3>
                        <p class="text-sm">
                            Congratulations on your first place win! We appreciate your participation and look forward to your next submission!
                        </p>
                    </div>
                    <div v-else-if="highlightData.suggestion" class="max-h-80 overflow-y-auto rounded bg-yellow-50 p-4 text-black">
                        <h3 class="mb-2 font-semibold">Improvement Suggestions</h3>
                        <div v-html="formatMarkdown(highlightData.suggestion)" class="prose text-sm" />
                    </div>
                </div>
            </div>
            <DialogFooter class="flex flex-col-reverse items-center space-y-2 sm:flex-row sm:space-y-0 sm:space-x-4">
                <p class="inline-flex items-center">
                    {{ highlightData.rank === 1 ? "Congratulations! don't forget to claim your prize" : 'Almost there! Try again next month' }}
                </p>
                <Button variant="outline" size="sm" @click="showDialog = false" class="sm:ml-auto">Close</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
