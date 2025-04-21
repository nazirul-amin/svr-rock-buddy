<script setup>
import { ref } from 'vue';
defineProps({
    columns: Array,
    rows: Array,
    emptyText: {
        type: String,
        default: 'No data found.',
    },
    keyField: {
        type: String,
        default: 'id',
    },
});

const sortKey = ref(null);
const sortAsc = ref(true);

function setSort(key) {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = key;
        sortAsc.value = true;
    }
}
</script>

<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300"
                        @click="setSort(col.key)"
                    >
                        {{ col.label }}
                        <span v-if="sortKey === col.key">
                            <svg
                                v-if="sortAsc"
                                xmlns="http://www.w3.org/2000/svg"
                                class="inline-block h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                class="inline-block h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </span>
                    </th>
                    <th
                        v-if="$slots.actions"
                        class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300"
                    >
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="dark:bg-card divide-y divide-gray-200 bg-white dark:divide-gray-700">
                <tr v-for="row in rows" :key="row[keyField]" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td v-for="col in columns" :key="col.key" class="whitespace-wrap px-6 py-4">
                        <slot :name="`cell-${col.key}`" :value="row[col.key]" :row="row">
                            {{ row[col.key] }}
                        </slot>
                    </td>
                    <td v-if="$slots.actions" class="whitespace-wrap px-6 py-4 text-right text-sm">
                        <slot name="actions" :row="row" />
                    </td>
                </tr>
                <tr v-if="!rows.length">
                    <td :colspan="columns.length + ($slots.actions ? 1 : 0)" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{ emptyText }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
