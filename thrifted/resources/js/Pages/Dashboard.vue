<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import Tag from "primevue/tag";
import Carousel from "primevue/carousel";
import { ref } from "vue";
const props = defineProps({
  newbooks: {
    type: Array,
    required: true,
  },
});
const getSeverity = (status) => {
  if (status == true) {
    return "success";
  } else {
    return "danger";
  }
};
const responsiveOptions = ref([
  {
    breakpoint: "1400px",
    numVisible: 2,
    numScroll: 1,
  },
  {
    breakpoint: "1199px",
    numVisible: 3,
    numScroll: 1,
  },
  {
    breakpoint: "767px",
    numVisible: 2,
    numScroll: 1,
  },
  {
    breakpoint: "575px",
    numVisible: 1,
    numScroll: 1,
  },
]);
function AddToWishList(button, book_id) {
  try {
        const svg = button.querySelector('svg');
  } catch (error) {
    console.log(error);
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="font-[Mulish] pt-10">
      <div
        class="mx-auto bg-white max-w-7xl border-x-2 sm:px-6 lg:px-8 overflow-hidden"
      >
        <p class="mb-2 ml-10 font-bold text-4xl">🕜 New books</p>
        <Carousel
          :value="props.newbooks"
          :numVisible="4"
          :numScroll="1"
          :responsiveOptions="responsiveOptions"
        >
          <template #item="slotProps">
            <div class="border-2 m-2 p-4 rounded">
              <Link :href="route('books.show', { id: slotProps.data.id })">
                <div class="mb-4">
                  <div class="relative flex justify-center mx-auto">
                    <img
                      :src="
                        slotProps.data.cover == null
                          ? '/cover-not-available.jpg'
                          : slotProps.data.cover.replace('public/', '/storage/')
                      "
                      :alt="slotProps.data.name"
                      class="rounded h-52"
                    />
                    <Tag
                      :value="
                        slotProps.data.available == 1 ? 'available' : 'sold'
                      "
                      :severity="getSeverity(slotProps.data.available)"
                      class="absolute"
                      style="left: 5px; top: 5px"
                    />
                  </div>
                </div>
              </Link>
              <div class="mb-4 font-medium">{{ slotProps.data.title }}</div>
              <div class="mb-4 font-medium">
                <i class="pi pi-map-marker" style="color: green"></i>
                {{ slotProps.data.user.adress }}
              </div>
              <div class="flex justify-between items-center">
                <div class="mt-0 font-semibold text-xl">
                  {{ slotProps.data.price }} DA
                </div>
                <span>
                  <button onclick="AddToWishList(this, slotProps.data.id)">
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
                  <Button icon="pi pi-shopping-cart" class="ml-2" />
                </span>
              </div>
            </div>
          </template>
        </Carousel>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
