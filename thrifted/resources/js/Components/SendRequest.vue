<script setup>
import axios from "axios";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import { ref, onMounted } from "vue";
import InputText from "primevue/inputtext";
import { usePage } from "@inertiajs/vue3";
const Wilayas = ref([]);
const PhoneNumber = ref(usePage().props.auth.user.phone);
const props = defineProps({
  book_id: {
    type: Number,
    required: true,
  },
});
onMounted(() => {
  axios
    .get("/api/wilayas")
    .then((response) => (Wilayas.value = response.data))
    .catch((error) => console.log(error));
});
const visible = ref(false);
const SelectedCity = ref();
async function SendRequest()
{
    try {
        let response = await axios.post('/api/books/send_request',{
            book_id : props.book_id,
            user_id : usePage().props.auth.user.id,
            phone_number : PhoneNumber.value,
            wilaya_id : SelectedCity.value
        });
        visible.value = false;
    } catch (error) {
        console.log(error);
    }
}
</script>
<template>
  <Button label="Send Request" icon="pi pi-credit-cart" @click="visible = true" />

  <Dialog
    v-model:visible="visible"
    modal
    header="Request this book"
    :style="{ width: '25rem' }"
  >
    <span class="text-surface-500 dark:text-surface-400 block mb-8"
      >Update your information.</span
    >
    <div class="flex items-center gap-4 mb-4">
      <label for="username" class="font-semibold w-24">Phone </label>
      <InputText id="username" v-model="PhoneNumber" class="flex-auto" autocomplete="off" />
    </div>
    <div class="flex items-center gap-4 mb-8">
      <label for="email" class="font-semibold w-24">Wilaya</label>
      <Select id="email" v-model="SelectedCity" class="flex-auto" placeholder="Select a City" autocomplete="off" :options="Wilayas" optionLabel="name" optionValue="id" />
    </div>
    <div class="flex justify-end gap-2">
      <Button
        type="button"
        label="Cancel"
        severity="secondary"
        @click="visible = false"
      ></Button>
      <Button type="button" label="Send" @click="SendRequest"></Button>
    </div>
  </Dialog>
</template>