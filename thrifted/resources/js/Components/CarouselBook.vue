<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import Tag from "primevue/tag";
import { onBeforeMount, onMounted, ref } from "vue";
import axios from "axios";
import Button from "primevue/button";

const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
  my_books:{
    type:Boolean,
    default : false
  }
});
import DeleteBookDialog from "@/Components/DeleteBookDialog.vue"
const Likes = ref(0);
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
      try {
          axios.post('/api/books/add_to_wishlist',{
            book_id: props.book.id,
          }).then(response => {
            if (response.status == 200) {
              Liked.value = true;
              Likes.value = Likes.value + 1;
            }else if(response.status == 201){
              Liked.value = false;
              Likes.value = Likes.value - 1;
            }
          }).catch(error =>{
            console.log(error);
          })
      } catch (error) {
        console.log(error);
      }
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
  
});
function DeleteBook(id)
{
  console.log(id);
  IsDeleted.value = 'hidden';
}
const IsDeleted = ref('');
</script>

<template>
  <div class="border-2 m-2 p-4 rounded-xl bg-white " :class="IsDeleted">
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
      <i class="pi pi-map-marker" style="color: var(--p-violet-500)"></i>
      {{ props.book.user.adress }}
    </div>
    <div class="flex justify-between items-center">
      <div class="mt-0 font-semibold text-xl">{{ props.book.price }} DA</div>
      <div class="flex items-center  justify-center gap-4 ">
        <button @click="AddToWishList" ref="LikeButton" class="relative">
          <div v-if="Likes > 0 || Liked == true"
            class="absolute -right-2 text-black font-bold -top-2 text-xs bg-[#fe6e6e] border-2 border-white px-1 rounded-full"
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
        <DeleteBookDialog @deleted="DeleteBook" v-if="props.book.user.id == usePage().props.auth.user.id" :book_id="props.book.id" />
      </div>
    </div>
  </div>
</template>