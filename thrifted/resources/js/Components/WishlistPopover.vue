<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import axios from "axios";
import Button from "primevue/button";
import Popover from "primevue/popover";
import { onMounted, ref } from "vue";

const op = ref();
const toggle = (event) => {
  op.value.toggle(event);
};
const DeleteFromWishlist = (wish) => {
  try {
          axios.post('/api/books/add_to_wishlist',{
            book_id: wish.book.id,
          }).then(response => {
            if(response.status == 201){
              Books.value = Books.value.filter(w => w.book.id != wish.book.id)
            }else{
              console.log('Error');
            }
          }).catch(error =>{
            console.log(error);
          })
      } catch (error) {
        console.log(error);
      }
}
const Books = ref([]);
onMounted(()=>{
    axios.get(`/api/users/${usePage().props.auth.user.id}/wishlist`)
        .then(respoonse => Books.value = respoonse.data)
        .catch(error=> console.log(error));
});
</script>
<template>
  <div>
    <Button type="button" icon="pi pi-heart" class="sm:w-fit w-full sm:m-0 my-3" label="WishList" @click="toggle" />
    <Popover ref="op">
      <div class="flex flex-col gap-4 w-[25rem]">
        <div  v-for="wish in Books"
        :key="wish.id" class="flex items-center w-full   font-bold  justify-start rounded-lg ">
        <Link
         
          :href="route('books.show',{id: wish.book.id})"
          class="w-full flex"
        >
          <img
            :src="wish.book.cover.replace('public/', '/storage/')"
            class="w-12"
            alt=""
          />
          <div>
            <p class="text-xl ml-2">
              {{ wish.book.title }}
            </p>
            <p class="text-lg ml-2">
              {{ wish.book.author }}
            </p>
            <p class="ml-2 text-green-400" v-if="wish.book.available == 1">
              Available
            </p>
            <p v-else class="ml-2 text-red-500">Sold</p>
          </div>
        </Link>
        <Button icon="pi pi-trash" @click="DeleteFromWishlist(wish)" /> 
      </div>
      </div>
    </Popover>
  </div>
</template>