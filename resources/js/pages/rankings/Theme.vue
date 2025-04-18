<script setup>
import { Head } from '@inertiajs/vue3';
const props = defineProps({ theme: Object, participants: Array });
</script>
<template>
    <Head :title="`Ranking for ${props.theme.name}`" />
    <div class="mx-auto max-w-2xl py-8">
        <h1 class="mb-6 text-2xl font-bold">Ranking for {{ props.theme.name }}</h1>
        <table class="min-w-full divide-y divide-gray-200 rounded-lg bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Participant</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Score</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(participant, idx) in props.participants" :key="participant.id">
                    <td class="px-4 py-2">{{ idx + 1 }}</td>
                    <td class="px-4 py-2">{{ participant.name }}</td>
                    <td class="px-4 py-2">{{ participant.total_score }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
