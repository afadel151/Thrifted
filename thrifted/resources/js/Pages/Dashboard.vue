<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import ShopByGenre from "@/Components/ShopByGenre.vue";
import Carousel from "primevue/carousel";
import { ref } from "vue";
import CarouselBook from "@/Components/CarouselBook.vue";
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
</script>

<template>
  <AuthenticatedLayout>
    <div class="font-[Mulish] flex pt-10">
      <div class="h-20 w-1/5">
        <ShopByGenre />
      </div>
      <div class="w-3/5 border-x-2 flex-col flex">
        <div class="bg-white sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">🕜 NEW BOOKS</p>
          <Carousel
            :value="props.newbooks"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
        </div>
        <div class="bg-white sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">BEST CLASSICS</p>
          <Carousel
            :value="props.newbooks"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
        </div>
        <div class="bg-white sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">RELIGION BOOKS</p>
          <Carousel
            :value="props.newbooks"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
        </div>
        <div class="bg-white sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">BEST SELLERS</p>
          <Carousel
            :value="props.newbooks"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
        </div>
      </div>
      <div class="w-1/5 bg-slate-500"></div>
    </div>
  </AuthenticatedLayout>
</template>
