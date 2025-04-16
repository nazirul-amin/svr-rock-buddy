<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs = [
	{ title: 'Participants', href: route('participants.index') }
];

const props = defineProps({
	participants: {
		type: Object,
	}
});

const participantToDelete = ref(null);

const confirmDelete = (participant) => {
	router.delete(route('participants.destroy', participant), {
		onFinish: () => {
			participantToDelete.value = null;
		},
	});
};
</script>

<template>
	<Head title="Participants" />
	
    <AppLayout :breadcrumbs="breadcrumbs">
		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-white">
					<div class="flex items-center justify-between mb-6">
					<h1 class="text-2xl font-semibold text-primary">Participants</h1>
					<Link
						:href="route('participants.create')"
						class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200"
					>
						Add Participant
					</Link>
					</div>

					<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
						<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
							<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
						</tr>
						</thead>
						<tbody class="bg-white dark:bg-card divide-y divide-gray-200 dark:divide-gray-700">
						<tr v-for="participant in participants.data" :key="participant.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
							<td class="px-6 py-4 whitespace-nowrap">{{ participant.name }}</td>
							<td class="px-6 py-4 whitespace-nowrap">{{ participant.email }}</td>
							<td class="px-6 py-4 whitespace-nowrap text-right text-sm">
								<div class="flex items-center justify-end space-x-2">
									<Button
										variant="default"
										class="cursor-pointer"
										@click="() => {router.visit(route('participants.show', participant.id))}"
									>
										View
									</Button>
									<Button
										class="bg-gray-500 text-white hover:bg-gray-600 transition duration-200 cursor-pointer"
										@click="() => {router.visit(route('participants.edit', participant.id))}"
									>
										Edit
									</Button>

									<Dialog>
										<DialogTrigger asChild>
											<Button variant="destructive" class="cursor-pointer">Delete</Button>
										</DialogTrigger>
										<DialogContent className="sm:max-w-[425px]">
											<DialogHeader>
												<DialogTitle>Delete participant</DialogTitle>
												<DialogDescription>
													Are you sure you want to delete this participant? This action cannot be undone.
												</DialogDescription>
											</DialogHeader>
											<DialogFooter>
												<Button type="button" variant="destructive" @click="confirmDelete(participant)">Delete</Button>
											</DialogFooter>
										</DialogContent>
									</Dialog>
								</div>
							</td>
						</tr>
						<tr v-if="!participants.data.length">
							<td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No participants found.</td>
						</tr>
						</tbody>
					</table>
					</div>

					<!-- Pagination -->
					<div class="mt-6" v-if="participants.links && participants.links.length > 3">
					<div class="flex justify-between items-center">
						<div>
							Showing {{ participants.from }} to {{ participants.to }} of {{ participants.total }} entries
						</div>
						<div class="flex gap-1">
							<Link
								v-for="(link, index) in participants.links"
								:key="index"
								:href="link.url"
								v-html="link.label"
								class="px-3 py-1 rounded-md"
								:class="{
									'bg-primary text-white': link.active,
									'bg-gray-100 dark:bg-card-foreground text-gray-600 dark:text-gray-300': !link.active && link.url,
									'bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-500 cursor-not-allowed': !link.url
								}"
								:tabindex="link.url ? 0 : -1"
							/>
						</div>
					</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</AppLayout>
</template>
