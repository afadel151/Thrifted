<script setup>
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { ref, onMounted } from "vue";
import Listbox from "primevue/listbox";
const Chats = ref([]);
const visible = ref(false);
const fd = new FormData();
fd.append("user_id", usePage().props.auth.user.id);
onMounted(() => {
  axios
    .post("/api/users/chats", fd)
    .then((response) => {
      Chats.value = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
});
const CheckedUsers = ref([]);
function getName(chat) {
  return chat.target_id == usePage().props.auth.user.id
    ? chat.creator.name
    : chat.target.name;
}
function getPicture(chat) {
  return chat.target_id == usePage().props.auth.user.id
    ? chat.creator.picture
    : chat.target.picture;
}
</script>

<template>
  <Button
    label="Share"
    @click="visible = true"
    icon="pi pi-share-alt"
    raised
    severity="info"
  />
  <Dialog
    v-model:visible="visible"
    modal
    header="Share"
    :style="{ width: '40rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <div class="flex flex-col space-y-2 justify-center items-center">
      <Listbox
        v-model="CheckedUsers"
        :options="Chats"
        multiple
        optionValue="id"
        class="w-full rounded-xl mb-5"
      >
        <template #option="slotProps">
          <div class="flex items-center gap-4">
            
        <div
          v-if="getPicture(slotProps.option)"
          class="w-[50px] h-[50px] rounded-full bg-cover bg-center"
          :style="{
            backgroundImage: `url('${getPicture(slotProps.option).replace(
              'public/',
              '/storage/'
            )}')`,
          }"
        ></div>
        <div
          v-else
          class="w-[50px] h-[50px] rounded-full bg-cover bg-center"
          style="background-image: url('/default-avatar.jpg')"
        ></div>
            
        <p class="text-xl">{{ getName(slotProps.option) }}</p>
          </div>
        </template>
      </Listbox>
      </div>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" @click="visible = false" severity="secondary"></Button>
      <Button type="button" label="Send" severity="contrast"></Button>
    </div>
  </Dialog>
</template>