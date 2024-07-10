<script setup>
import { Link } from "@inertiajs/vue3";
import Tag from "primevue/tag";
import { onBeforeMount, onMounted, ref } from "vue";
import Button from "primevue/button";
import axios from "axios";
const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
});
const CardButton = ref();
const Likes = ref(0);
const Cards = ref(0);
const LikeButton = ref();
const getSeverity = (status) => {
  if (status == true) {
    return "success";
  } else {
    return "danger";
  }
};
const Liked = ref(false);
const AddToWishList = () => {
  
    if (!Liked.value) {
      Likes.value = Likes.value + 1;
      
    }else{
      Likes.value = Likes.value - 1;
    }
    Liked.value = !Liked.value;
 
};
onBeforeMount(() => {
  axios
    .get("/api/books/" + props.book.id + "/likes")
    .then((response) => {
      Likes.value = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
    axios
    .get("/api/books/" + props.book.id + "/liked")
    .then((response) => {
      Liked.value = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
  axios
    .get("/api/books/" + props.book.id + "/cards")
    .then((response) => {
      Cards.value = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
});
onMounted(() => {
  console.log(CardButton.value);
});
</script>

<template>
  <div class="border-2 m-2 p-4 rounded">
    <Link :href="route('books.show', { id: props.book.id })">
      <div class="mb-4">
        <div class="relative flex justify-center mx-auto">
          <img
            :src="
              props.book.cover == null
                ? '/cover-not-available.jpg'
                : props.book.cover.replace('public/', '/storage/')
            "
            :alt="props.book.name"
            class="rounded h-52"
          />
          <Tag
            :value="props.book.available == 1 ? 'available' : 'sold'"
            :severity="getSeverity(props.book.available)"
            class="absolute"
            style="left: 5px; top: 5px"
          />
        </div>
      </div>
    </Link>
    <div class="mb-4 font-medium">{{ props.book.title }}</div>
    <div class="mb-4 font-medium">
      <i class="pi pi-map-marker" style="color: green"></i>
      {{ props.book.user.adress }}
    </div>
    <div class="flex justify-between items-center">
      <div class="mt-0 font-semibold text-xl">{{ props.book.price }} DA</div>
      <div class="flex items-end  justify-center gap-4 ">
        <button @click="AddToWishList" ref="LikeButton" class="relative">
          <div v-if="Likes > 0 || Liked == true"
            class="absolute -right-2 text-black font-bold -top-2 text-xs bg-[#fe6e6e] px-1 rounded-full"
          >
            {{ Likes }}
          </div>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            :fill="Liked == true ? '#ef4444' : 'none'"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="#ef4444"
            class="size-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
            />
          </svg>
        </button>

        <button   class="relative">
          <div v-show="Cards > 0"
            class="absolute -right-2 text-black font-bold  -top-2 text-xs bg-[#22c55e] px-1 rounded-full"
          >
            {{ Cards }}
          </div>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            class="size-6"
             stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              d="M1 1.75A.75.75 0 0 1 1.75 1h1.628a1.75 1.75 0 0 1 1.734 1.51L5.18 3a65.25 65.25 0 0 1 13.36 1.412.75.75 0 0 1 .58.875 48.645 48.645 0 0 1-1.618 6.2.75.75 0 0 1-.712.513H6a2.503 2.503 0 0 0-2.292 1.5H17.25a.75.75 0 0 1 0 1.5H2.76a.75.75 0 0 1-.748-.807 4.002 4.002 0 0 1 2.716-3.486L3.626 2.716a.25.25 0 0 0-.248-.216H1.75A.75.75 0 0 1 1 1.75ZM6 17.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM15.5 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"
            />
          </svg>
        </button>
        <!-- ref="CardButton" -->
      </div>
    </div>
  </div>
</template>