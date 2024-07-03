<script setup>
import Cards from "./Cards.vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
  books: {
    type: Array,
    required: true,
  },
});
</script>
<template>
  <Cards :cards="props.cards">
    <div class="px-10 ">
      <div
        v-for="book in props.books"
        :key="book.id"
        class="w-full font-bold flex items-center justify-between rounded-xl border p-5"
      >
        <img
          :src="book.cover.replace('public/', '/storage/')"
          class="w-24"
          alt=""
        />
        <div class="flex flex-col font-sans items-center justify-center">
          <p class="text-4xl ">
            title: {{ book.title }}
          </p>
          <p class="text-2xl ">
            author: {{ book.author }}
          </p>
          <p class="text-xl ">
            edition: {{ book.edition }}
          </p>
          <p class=" text-green-600" v-if="book.available == 1">
            Available {{ book.price }}DA
          </p>
          
          <p v-else class="ml-2 text-red-500">Sold</p>
         <Link :href="route('profile.show',{id: book.user.id})" >
            
            From : @{{ book.user.name }}
        
        </Link>
        </div>
        <div class="flex flex-col gap-2">
           <Button label="Request" severity="contrast" />
           <Button label="Delete" severity="danger" />
        </div>
    </div>
    </div>
  </Cards>
</template>


<style>
@import url("https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap");
</style>
