<script setup>
import axios from "axios";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import { onMounted, ref } from "vue";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Avatar from "primevue/avatar";
import MultiSelect from "primevue/multiselect";
import InputNumber from "primevue/inputnumber";
import SelectButton from "primevue/selectbutton";
import Select from "primevue/select";
import { usePage } from "@inertiajs/vue3";
const emit = defineEmits(["addBook"]);
const props = defineProps({
  user_id: {
    type: String,
    required: true,
  }
});
const Categories = ref([]);
const Tags = ref([]);
onMounted(()=>{
  axios.get('/api/categories')
        .then(response => Categories.value = response.data)
        .catch(error => console.log(error));
  axios.get('/api/tags')
        .then(response => Tags.value = response.data)
        .catch(error => console.log(error));
})
const file = ref(null);
const InputTitle = ref("");
const InputEdition = ref("");
const InputAuthor = ref("");
const InputIsbn = ref(0);
const InputPrice = ref(0);
const InputDescription = ref("");
const SelectedTags = ref([]);
const visible = ref(false);
const FormatOptions = ref(["Hardcover", "Paperback", "Pocket"]);
const OriginalOptions = ref(["Original", "Copied"]);
const InputOriginal = ref("");
const InputFormat = ref("");
const ConditionOptions = ref(["Low", "Acceptable", "Normal", "Good ", "High"]);
const StateOptions = ref(["New", "Used"]);
const InputState = ref("");
const InputCondition = ref("");
const InputCategory = ref(0);
function ResetInputs() {
  file.value = "";
  InputTitle.value = "";
  InputEdition.value = "";
  InputAuthor.value = "";
  InputIsbn.value = 0;
  InputDescription.value = "";
  SelectedTags.value = [];
  InputOriginal.value = "";
  InputFormat.value = "";
  InputCondition.value = "";
  InputPrice.value = 0;
  InputState.value = "";
  InputCategory.value = 0;
}

async function SaveBook() {
  if (
    InputTitle.value == "" ||
    InputCategory.value == 0 ||
    InputAuthor.value == "" ||
    SelectedTags.value == [] ||
    InputFormat.value == "" ||
    InputCondition.value == "" ||
    InputState.value == ""
  ) {
    alert("you must fill al inputs ");
  } else {
    visible.value = false;
    let fd = new FormData();
    fd.append("file", file.value);
    fd.append("title", InputTitle.value);
    fd.append("edition", InputEdition.value);
    fd.append("author", InputAuthor.value);
    fd.append("isbn", InputIsbn.value);
    fd.append("description", InputDescription.value);
    SelectedTags.value.forEach((tag) => {
      fd.append("tags[]", tag);
    });
    fd.append("user_id", props.user_id);
    fd.append("format", InputFormat.value);
    fd.append("condition", InputCondition.value);
    fd.append("price", InputPrice.value);
    fd.append("state", InputState.value);
    fd.append("category_id", InputCategory.value);
    fd.append("original", InputOriginal.value);
    try {
      let response = await axios.post("/api/books/create", fd);
      if (response.status == 300) {
          alert('Error while adding your book');
      }else{
        console.log(response.data);
      emit("addBook", response.data);
      }
    } catch (error) {
      console.log(error);
    }
    ResetInputs();
  }
}
function onChange(e) {
  file.value = e.target.files[0];
  
}
</script>
<template >
  <div>
    <Button icon="pi pi-plus-circle" label="Add" v-if="props.user_id == usePage().props.auth.user.id"  @click="visible = true" />
  <Dialog
    v-model:visible="visible"
    modal
    header="Add a book"
    class="sm:w-[40rem] w-[90%] "
  >
    <template #header>
      <div class="inline-flex justify-center items-center gap-2">
        <Avatar
          image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
          shape="circle"
        />
      </div>
    </template>
    <span class="block mb-8 text-surface-500 dark:text-surface-400"
      >Add a Book</span
    >
    <div class="flex items-center gap-4 mb-4">
      <label for="title" class="w-24 font-semibold">Title</label>
      <InputText
        v-model="InputTitle"
        id="title"
        class="flex-auto"
        autocomplete="off"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="edition" class="w-24 font-semibold">Edition</label>
      <InputText
        v-model="InputEdition"
        id="edition"
        class="flex-auto"
        autocomplete="off"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="author" class="w-24 font-semibold">Author</label>
      <InputText
        v-model="InputAuthor"
        id="author"
        class="flex-auto"
        autocomplete="off"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="author" class="w-24 font-semibold">Category</label>
      <Select
        v-model="InputCategory"
        :options="Categories"
        optionLabel="name"
        optionValue="id"
        placeholder="Select a Category"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="ssn" class="w-24 font-semibold">ISBN</label>
      <InputNumber
        v-model="InputIsbn"
        inputId="integeronly"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label class="w-24 font-semibold">Description</label>
      <Textarea
        v-model="InputDescription"
        rows="5"
        cols="30"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label class="w-24 font-semibold">Tags</label>
      <MultiSelect
        v-model="SelectedTags"
        display="chip"
        :options="Tags"
        optionLabel="name"
        optionValue="id"
        filter
        placeholder="Select Tags"
        :maxSelectedLabels="3"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="ssn" class="w-24 font-semibold">Format</label>
      <SelectButton
        v-model="InputFormat"
        :options="FormatOptions"
        aria-labelledby="basic"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="ssn" class="w-24 font-semibold">Condition</label>
      <SelectButton
        v-model="InputCondition"
        :options="ConditionOptions"
        aria-labelledby="basic"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="ssn" class="w-24 font-semibold">State</label>
      <SelectButton
        v-model="InputState"
        :options="StateOptions"
        aria-labelledby="basic"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="ssn" class="w-24 font-semibold">Original</label>
      <SelectButton
        v-model="InputOriginal"
        :options="OriginalOptions"
        aria-labelledby="basic"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="ssn" class="w-24 font-semibold">Price</label>
      <InputNumber
        v-model="InputPrice"
        inputId="integeronly"
        class="flex-auto"
      />
    </div>
    <div class="flex items-center gap-4 mb-2">
      <label for="Cover" class="w-24 font-semibold">Cover</label>
      <input name="file" type="file" class="flex-auto" @change="onChange" />
    </div>

    <template #footer>
      <div>
        <Button
        label="Cancel"
        text
        severity="secondary"
        @click="visible = false"
        autofocus
      />
      <Button
        label="Save"
        outlined
        severity="secondary"
        @click="SaveBook"
        autofocus
      />
      </div>
    </template>
  </Dialog>
  </div>
</template>