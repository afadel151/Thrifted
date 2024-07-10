<script setup>
import { Link } from "@inertiajs/vue3";
import Tag  from "primevue/tag";
import { ref } from "vue";
import Button from "primevue/button";
const props = defineProps({
    book: {
        type: Object,
        required: true
    }
});
const LikeButton = ref();
const getSeverity = (status) => {
  if (status == true) {
    return "success";
  } else {
    return "danger";
  }
};
const  AddToWishList = () => {
  try {
        LikeButton.value.firstChild.setAttribute('fill','red'); 
  } catch (error) {
    console.log(error);
  }
};
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
                      :value="
                        props.book.available == 1 ? 'available' : 'sold'
                      "
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
                <div class="mt-0 font-semibold text-xl">
                  {{ props.book.price }} DA
                </div>
                <div class="flex items-center">
                  <button @click="AddToWishList" ref="LikeButton">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke-width="1.5"
                      stroke="currentColor"
                      class="size-6"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"
                      />
                    </svg>
                  </button>
                  <Button icon="pi pi-shopping-cart" severity="secondary" class="ml-2" />
                </div>
              </div>
            </div>
</template>