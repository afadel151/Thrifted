<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import NavLink from "@/Components/NavLink.vue";
import ConfirmDialog from "primevue/confirmdialog";
const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
});
const NewCardName = ref("");
const MyCards = ref(props.cards);
const visible = ref(false);
async function Addcard() {
  try {
    if (NewCardName.value !== "") {
      const fd = new FormData();
      fd.append("user_id", usePage().props.auth.user.id);
      fd.append("name", NewCardName.value);
      let response = await axios.post("/cards/create", fd);
      NewCardName.value = "";
      visible.value = false;
      console.log(response);
      MyCards.value.push(response.data);
    } else {
      alert("name input must be provided");
    }
  } catch (error) {
    console.log(error.message);
  }
}
function GetTotalPrice(card) {
  let total = 0;
  card.books.forEach((book) => {
    total = total + book.price;
  });
  return total;
}
</script>
<template>
  <AuthenticatedLayout>
    <div class="h-24  flex px-20 items-center justify-start">
      <Button label="New card" severity="contrast" @click="visible = true" />
    </div>
    <Dialog
      v-model:visible="visible"
      modal
      header="New Card"
      :style="{ width: '25rem' }"
    >
      
      <div class="flex items-center gap-4 mb-4">
        <label for="username" class="font-semibold w-24">Name</label>
        <InputText
          id="username"
          v-model="NewCardName"
          class="flex-auto"
          autocomplete="off"
        />
      </div>
      <div class="flex justify-end gap-2">
        <Button
          type="button"
          label="Cancel"
          severity="secondary"
          @click="visible = false"
        ></Button>
        <Button type="button" severity="contrast" label="Save" @click="Addcard"></Button>
      </div>
    </Dialog>
    <div class="flex w-screen px-20   gap-4 mt-2">
      <div class="w-1/4 flex flex-col gap-2">
        <div
          v-for="card in MyCards"
          :key="card.id"
          class="border"
        >
          <NavLink
          :href="route('cards.show',{id: card.id})"
          :active="route().current('cards.show',{id: card.id})"
          class="flex p-6 justify-between items-start h-full w-full">
            <p class="text-3xl p-4 font-bold text-gray-950">{{ card.name }}</p>
            <p class="text-gray-900 text-lg">
              total : {{ card.books.length }} books ({{
                GetTotalPrice(card)
              }}DA)
            </p>
          </NavLink>
        </div>
      </div>
      <div class="w-3/4 h-fit ">
        <slot />
      </div>
    </div>
  </AuthenticatedLayout>
</template>