<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Edit Theme</h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">Name</label>
        <input v-model="form.name" type="text" class="input" required />
      </div>
      <div class="mb-4">
        <label class="block mb-1">From</label>
        <input v-model="form.from" type="date" class="input" required />
      </div>
      <div class="mb-4">
        <label class="block mb-1">To</label>
        <input v-model="form.to" type="date" class="input" required />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Poster</label>
        <input v-model="form.poster" type="text" class="input" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Descriptions</label>
        <textarea v-model="form.descriptions" class="input"></textarea>
      </div>
      <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Update</button>
    </form>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
const props = defineProps({ theme: Object });
const form = useForm({
  name: props.theme.name,
  from: props.theme.from,
  to: props.theme.to,
  poster: props.theme.poster,
  descriptions: props.theme.descriptions,
});
function submit() {
  form.put(route('themes.update', props.theme.id));
}
</script>
