<script setup>
import axios from "axios";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { ref } from "vue";
const emits = defineEmits(["deleted"]);
const props = defineProps({
  book_id: {
    type: Number,
    required: true,
  },
});
async function DeletBook(){
    try {
            let response = await axios.post(`/api/books/delete`,{
                book_id : props.book_id,
            });
            if (response.status == 200) {
                emits('deleted',props.book_id);
                visible.value = false;
            }else{
                Error.value = true;
            }
    } catch (error) {
            console.log(error);
    }
}
const visible = ref(false);
const Error = ref(false);
</script>
<template>
  <div>
    <Button icon="pi pi-trash" severity="danger" raised text @click="visible = true" />
    <Dialog
      v-model:visible="visible"
      modal
      header="Delete Book"
      :style="{ width: '25rem' }"
    >
      <p v-show="Error" class="text-xl text-red-500">Error while deleting the book</p>
      <div class="flex justify-end gap-2">
        <Button
          type="button"
          label="Cancel"
          severity="secondary"
          @click="visible = false"
        ></Button>
        <Button type="button" label="Delete" severity="danger" @click="DeletBook"></Button>
      </div>
    </Dialog>
  </div>
</template>