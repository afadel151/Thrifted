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
const DeleteFromCardlist = (card) => {
  try {
          axios.post('/api/cards/delete_book',{
            book_id: card.book.id,
          }).then(response => {
            if(response.status == 201){
              Card.value = Card.value.filter(c => c.book.id != card.book.id)
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
const Card = ref([]);
onMounted(()=>{
    axios.get(`/api/cards`)
        .then(respoonse => Card.value = respoonse.data)
        .catch(error=> console.log(error));
});
</script>
<template>
  <div>
    <Button type="button" icon="pi pi-shopping-cart" label="Card" @click="toggle" />
    <Popover ref="op">
      <div class="flex flex-col gap-4 w-[25rem]">
        <div  v-for="card in Card"
        :key="card.id" class="flex items-center w-full   font-bold  justify-start rounded-lg ">
        <Link
         
          :href="route('books.show',{id: card.book.id})"
          class="w-full flex"
        >
          <img
            :src="card.book.cover.replace('public/', '/storage/')"
            class="w-12"
            alt=""
          />
          <div>
            <p class="text-xl ml-2">
              {{ card.book.title }}
            </p>
            <p class="text-lg ml-2">
              {{ card.book.author }}
            </p>
            <p class="ml-2 text-green-400" v-if="card.book.available == 1">
              Available
            </p>
            <p v-else class="ml-2 text-red-500">Sold</p>
          </div>
        </Link>
        <Button icon="pi pi-trash" @click="DeleteFromCardlist(card)" /> 
      </div>
      </div>
    </Popover>
  </div>
</template>