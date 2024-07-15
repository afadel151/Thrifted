<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import ShopByGenre from "@/Components/ShopByGenre.vue";
import Carousel from "primevue/carousel";
import { ref } from "vue";
import CarouselBook from "@/Components/CarouselBook.vue";
import CarouselUser from "@/Components/CarouselUser.vue";
const props = defineProps({
  newbooks: {
    type: Array,
    required: true,
  },
  classics:{
    type: Array,
    required:true
  },
  literature:{
    type: Array,
    required: true
  },
  best_sellers:{
    type:Array,
    required: true
  }
});

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
import Footer from "@/Components/Footer.vue"
</script>

<template>
  <AuthenticatedLayout>
    <div class=" flex pt-10">
      <div class="h-20 w-1/5">
        <ShopByGenre />
      </div>
      <div class="w-3/5  flex-col gap-6  flex" >
        <div  class="bg-white shadow-md pt-10 rounded-2xl sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">🕜 NEW BOOKS</p>
          <Carousel
            v-if="props.newbooks.length > 3"
            :value="props.newbooks"
            :numVisible="props.newbooks.length < 4 ? props.newbooks.length : 4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
          <div v-else class="flex justify-start">
            <CarouselBook v-for="book in props.newbooks" :key="book.id" :book="book" />
          </div>
        </div>
        <div  class="bg-white shadow-md sm:px-6 pt-10 rounded-2xl lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl"> NEW CLASSICS</p>
          <Carousel
             v-if="props.classics.length > 3"
            :value="props.classics"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
          <div v-else class="flex justify-start">
            <CarouselBook v-for="book in props.classics" :key="book.id" :book="book" />
          </div>
        </div>
        <div  class="bg-white shadow-md rounded-2xl pt-10 sm:px-6 lg:px-8 overflow-hidden">
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
        <div  class="bg-white shadow-md rounded-2xl pt-10 sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">BEST OF LITERATURE</p>
          <Carousel
           v-if="props.literature.length > 3"
            :value="props.literature"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselBook :book="slotProps.data" />
            </template>
          </Carousel>
          <div v-else class="flex justify-start">
            <CarouselBook v-for="book in props.literature" :key="book.id" :book="book" />
          </div>
        </div>
        <div  class="bg-white shadow-md rounded-2xl pt-10 sm:px-6 lg:px-8 overflow-hidden">
          <p class="mb-2 ml-10 font-bold text-4xl">BEST SELLERS</p>
          <Carousel
           v-if="props.best_sellers.length > 3"
            :value="props.best_sellers"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <CarouselUser :user="slotProps.data" />
            </template>
          </Carousel>
          <div v-else class="flex justify-start">
            <CarouselUser v-for="user in props.best_sellers" :key="user.id" :user="user" />
          </div>
        </div>
      </div>
      <div class="w-1/5 ">
      </div>
    </div>
    <Footer />
  </AuthenticatedLayout>
</template>
