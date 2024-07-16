<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import Divider from "primevue/divider";
import Button from "primevue/button";
import { computed, ref } from "vue";
import ShareBook from "@/Components/ShareBook.vue"
import { Link, usePage } from "@inertiajs/vue3";
import NavLink from "@/Components/NavLink.vue";
import Carousel from "primevue/carousel";
import Badge from "primevue/badge";
import Tag from "primevue/tag";
import Dialog from "primevue/dialog";
import axios from "axios";
const page = usePage();
const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
  related_books: {
    type: Array,
    required: true,
  },
  cards: {
    type: Array,
    required: true,
  },
  belongs_to_card:{
    type: Boolean,
    required: true
  }
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
    breakpoint: "1200px",
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
const user = computed(() => {
  return page.props.auth.user;
});
const BookImages = computed(() => {
  let images = [];
  if (props.book.cover) {
    images.push(props.book.cover.replace("public/", "/storage/"));
  }

  props.book.pictures.forEach((picture) => {
    images.push(picture.picture.replace("public/", "/storage/"));
  });
  return images;
});
function GetTotalPrice(card) {
  let total = 0;
  card.books.forEach((book) => {
    total = total + book.price;
  });
  return total;
}

import SendRequest from "@/Components/SendRequest.vue"
const BelongsToCard = ref(props.belongs_to_card);
async function AddBookToCard()
{
    try {
        let response = await axios.post('/api/cards/add_book',{
            book_id : props.book.id
        });
        BelongsToCard.value = true;
    } catch (error) {
        console.log(error);
    }
};

</script>

<template>
  <AuthenticatedLayout >
   
    <div
      class="flex pt-10 md:flex-row min-[200px]:flex-col sm:flex-col justify-center items-stretch gap-4 mt-2 2xl:px-96 w-full 2xl:h-[500px]"
    >
    
      <Carousel
        :value="BookImages"
        :numVisible="1"
        :numScroll="1"
        class="sm:w-[500px]"
      >
        <template #item="slotProps">
          <div
            class="border border-surface-200 dark:border-surface-700 rounded"
          >
            <div class="w-full">
              <img
                :src="slotProps.data"
                :alt="slotProps.data"
                class="w-full rounded"
              />
            </div>
          </div>
        </template>
      </Carousel>

      <div class="flex flex-col justify-start gap-2 md:px-0 px-10 w-full">
        <p class="font-semibold text-2xl text-gray-500">
          Category : {{ props.book.category.name }}
        </p>
        <p class="text-4xl">Title : {{ props.book.title }}</p>
        <p class="font-bold text-3xl">
          Price : {{ props.book.price }}DA
          <span
            v-if="props.book.old_price != null"
            class="text-red-300 text-xl line-through"
            >{{ props.book.old_price }} DA</span
          >
        </p>
        <!-- <p class="font-bold text-2xl">256 pages</p> -->

        <p class="font-bold text-base">Format :{{ props.book.format }}</p>
        <p class="font-bold text-base">{{ props.book.condition }} quality</p>
        <p
          class="text-xl"
          :class="props.book.available ? 'text-green-500' : 'text-red-400'"
        >
          {{ props.book.available ? "Available" : "Sold" }}
        </p>
        <div class="text-xl flex space-x-2">
          <p>Seller :</p>
          <Link :href="route('profile.show', { id: props.book.user_id })" class="flex items-center">
            <div
            v-if="props.book.user.picture"
            class="w-[30px] h-[30px] rounded-full  bg-cover bg-center"
            :style="{
              backgroundImage: `url('${props.book.user.picture.replace(
                'public/',
                '/storage/'
              )}')`,
            }"
          ></div>
          <div
            v-else
            class="w-[30px] h-[30px] rounded-full   bg-cover bg-center"
            style="background-image: url('/default-avatar.jpg')"
          ></div>
            <p class="hover:text-gray-700">{{ props.book.user.name }}</p>
          </Link>
        </div>

        <div
          class="flex sm:flex-row flex-col justify-self-end justify-start items-center gap-2 mt-auto w-full"
          v-if="props.book.user_id != user.id"
        >
          <Link :href="route('books.chat_with_seller', { id: props.book.id })">
            <Button
              label="Chat with seller"
              icon="pi pi-comments"
              severity="secondary"
              raised
            />
          </Link>
          <ShareBook />
          <SendRequest
            v-if="props.book.available == 1"
             :book_id="props.book.id"
            />
          <Button v-show="!BelongsToCard"
            label="Add to card"
            icon="pi pi-shopping-cart"
            @click="AddBookToCard"
            severity="contrast"
            raised
          />
        </div>
        <div
          v-else
          class="flex justify-self-end justify-start items-center gap-2 mt-auto w-full"
        >
          <NavLink :href="route('books.edit', { id: props.book.id })">
            <Button label="Update" icon="pi pi-pencil" size="small" raised />
          </NavLink>
        </div>
        <div
          class="flex flex-col justify-self-end justify-start items-stretch mt-auto"
        >
          <p>
            ISBN :
            {{ props.book.isbn == null ? "No isbn provided" : props.book.isbn }}
          </p>
          <hr />
          <div>
            Tags:
              <Badge
                v-for="tag in props.book.tags"
                :key="tag.id"
                :value="tag.name"
                severity="secondary"
                class="mr-2"
              ></Badge>
          </div>
          <hr />
          <p>Author : {{ props.book.author }}</p>
          <hr />
          <p>Edition : {{ props.book.edition }}</p>
        </div>
      </div>
    </div>
    <Divider />
 <div class="2xl:px-80">
  <p class="ml-10 font-black text-4xl">See Also</p>
    <Carousel
      :value="props.related_books"
      :numVisible="
        props.related_books.length > 4 ? 4 : props.related_books.length
      "
      :numScroll="1"
      :responsiveOptions="responsiveOptions"
    >
      <template #item="slotProps">
        <div class="border-2 m-2 p-4 rounded-xl bg-white">
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
                  :value="slotProps.data.available == 1 ? 'available' : 'sold'"
                  :severity="getSeverity(slotProps.data.available)"
                  class="absolute"
                  style="left: 5px; top: 5px"
                />
              </div>
            </div>
          </Link>
          <div class="mb-4 font-medium">{{ slotProps.data.title }}</div>
          <div class="flex justify-between items-center">
            <div class="mt-0 font-semibold text-xl">
              ${{ slotProps.data.price }}
            </div>
            <span>
              <Button icon="pi pi-heart" severity="secondary" outlined />
              <Button icon="pi pi-shopping-cart" class="ml-2" />
            </span>
          </div>
        </div>
      </template>
    </Carousel>
 </div>
  </AuthenticatedLayout>
</template>