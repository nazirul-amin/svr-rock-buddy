<script setup>
import { Button } from '@/components/ui/button';
import { Head, router } from '@inertiajs/vue3';
const props = defineProps({ participants: Array });
</script>
<template>
    <Head title="Overall Participant Rankings" />
    <div class="bg-primary w-full p-8">
        <div class="mx-auto w-1/2">
            <div class="flex justify-between space-x-4">
                <h1 class="mb-6 text-2xl font-bold">Overall Participant Rankings</h1>
                <Button variant="secondary" @click="router.visit('/')" class="cursor-pointer">Go Back</Button>
            </div>
            <table class="w-full divide-y divide-gray-200 rounded-lg bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Participant</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(participant, idx) in props.participants" :key="participant.id" class="text-black">
                        <td class="px-4 py-2">{{ idx + 1 }}</td>
                        <td class="px-4 py-2">{{ participant.name }}</td>
                        <td class="px-4 py-2">{{ participant.score }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
