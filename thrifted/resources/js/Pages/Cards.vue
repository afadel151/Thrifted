<script setup>
import { computed, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
// import Link from "@inertiajs/vue3";
import Divider from 'primevue/divider';

const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
});
const MyCards = ref(props.cards);
const visible = ref(false);

let TotalPrice = computed(()=>{
  let Price = 0;
  MyCards.value.forEach(card=> {
    Price = Price + card.book.price;
  });
  return Price;
})
</script>
<template>
  <AuthenticatedLayout>
    <div class="flex w-screen px-20   gap-4 mt-2">
      <div class="w-1/4 flex flex-col gap-2">
        <div
          v-for="card in MyCards"
          :key="card.id"
          class="border"
        >
          <a
          :href="route('books.show',{id: card.book.id})"
          class="flex p-6 justify-between items-start h-full w-full">
            <p class="text-3xl p-4 font-bold text-gray-950">{{ card.book.title }}</p>
      </a>
        </div>
      </div>
      <Divider layout="vertical" />
    </div>
    
  </AuthenticatedLayout>
</template>

