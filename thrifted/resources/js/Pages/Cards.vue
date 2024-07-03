<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import DataView from "primevue/dataview";
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
const confirm = useConfirm();
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
const confirm1 = () => {
    confirm.require({
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Save'
        },
        accept: () => {
            alert('deleted');
        },
        reject: () => {
            alert('rejected');
        }
    });
};
</script>
<template>
  <AuthenticatedLayout>
    <ConfirmDialog></ConfirmDialog>
    <div class="h-24 flex px-96 items-center justify-end">
        <Button label="Add" @click="visible = true" />
    </div>
    <Dialog
      v-model:visible="visible"
      modal
      header="Edit Profile"
      :style="{ width: '25rem' }"
    >
      <span class="text-surface-500 dark:text-surface-400 block mb-8"
        >Update your information.</span
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
        <Button type="button" label="Save" @click="Addcard"></Button>
      </div>
    </Dialog>
    <div class="flex flex-col justify-center items-stretch gap-4 mt-2 px-96">
      <DataView :value="MyCards">
        <template #list="slotProps">
          <div class="flex border flex-col">
            <div v-for="(item, index) in slotProps.items" :key="index">
              <div
                class="flex flex-col sm:flex-row sm:items-center p-6 gap-4"
                :class="{
                  'border-t border-surface-200 dark:border-surface-700':
                    index !== 0,
                }"
              >
                <div
                  class="flex flex-col md:flex-row justify-between md:items-center flex-1 gap-6"
                >
                  <div
                    class="flex flex-row md:flex-col justify-between items-start gap-2"
                  >
                    <div>
                      <div class="text-lg font-medium mt-2">
                        {{ item.name }}
                      </div>
                    </div>
                    <div class="bg-surface-100 p-1" style="border-radius: 30px">
                      <div
                        class="bg-surface-0 flex items-center gap-2 justify-center py-1 px-2"
                        style="
                          border-radius: 30px;
                          box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04),
                            0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                        "
                      >
                        <i class="pi pi-star-fill text-yellow-500"></i>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-col md:items-end gap-8">
                    <div class="flex flex-row-reverse md:flex-row gap-2">
                      <Button icon="pi pi-trash" @click="confirm1" outlined severity="danger"></Button>
                      <Button
                        icon="pi pi-eye"
                        label="View"
                        :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                        class="flex-auto md:flex-initial whitespace-nowrap"
                      ></Button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </DataView>
    </div>
  </AuthenticatedLayout>
</template>