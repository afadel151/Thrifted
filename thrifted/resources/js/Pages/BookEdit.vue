<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import Button from "primevue/button";
import Select from "primevue/select";
import SelectButton from "primevue/selectbutton";
import axios from "axios";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import Popover from "primevue/popover";
import { computed, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Galleria from "primevue/galleria";
const page = usePage();
const props = defineProps({
  book: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
});
const file = ref(null);
const InputTitle = ref(props.book.title);
const InputEdition = ref(props.book.edition);
const InputAuthor = ref(props.book.author);
const InputIsbn = ref(props.book.isbn);
const InputPrice = ref(props.book.price);
const InputDescription = ref(props.book.description);
const FormatOptions = ref(["Hardcover", "Paperback", "Pocket"]);
const OriginalOptions = ref(["Original", "Copied"]);
const InputOriginal = ref(props.book.original == true ? "Original" : "Copied");
const InputFormat = ref(props.book.format);
const ConditionOptions = ref(["Low", "Acceptable", "Normal", "Good ", "High"]);
const StateOptions = ref(["New", "Used"]);
const AvailableOptions = ref(["Available", "Sold"]);
const InputState = ref(props.book.new == true ? "New" : "Used");
const InputCategory = ref(props.book.category_id);
const InputCondition = ref(props.book.condition);
const InputAvailable = ref(props.book.available == true ? "Available" : "Sold");
const op = ref();
const user = computed(() => {
  return page.props.auth.user;
});
const images = computed(() => {
  let images = [];
  images.push(props.book.cover);
  props.book.pictures.forEach((picture) => {
    images.push(picture.picture);
  });
  return images;
});
const responsiveOptions = ref([
  {
    breakpoint: "1300px",
    numVisible: 4,
  },
  {
    breakpoint: "575px",
    numVisible: 1,
  },
]);
const toggle = (event) => {
  op.value.toggle(event);
};
function onChange(e) {
  file.value = e.target.files[0];
}
async function AddPicture() {
  let formData = new FormData();
  formData.append("book_id", props.book.id);
  formData.append("file", file.value);
  try {
    let response = await axios.post("/api/books/add_picture", formData);
    if (response.status == 200) {
      location.reload();
    }
  } catch (error) {
    console.log(error);
  }
}

async function UpdateBook() {
  let formData = new FormData();
  formData.append("book_id", props.book.id); //
  formData.append("title", InputTitle.value); //
  formData.append("edition", InputEdition.value); //
  formData.append("author", InputAuthor.value); //
  formData.append("isbn", InputIsbn.value); //
  formData.append("price", InputPrice.value); //
  formData.append("description", InputDescription.value); //
  formData.append("format", InputFormat.value); //
  formData.append("original", InputOriginal.value); //
  formData.append("state", InputState.value); //
  formData.append("condition", InputCondition.value); //
  formData.append("category_id", InputCategory.value); //
  formData.append("available", InputAvailable.value); //
  try {
    let response = await axios.post("/api/books/update", formData);
    if (response.status == 200) {
      location.reload();
    }
  } catch (error) {
    console.log(error);
  }
}
</script>
<template>
  
  <AuthenticatedLayout>
    <!-- <template #header> -->
    <div class="flex max-[600px]:flex-col max-[600px]:items-center max-[600px]:gap-2 justify-between px-32 my-10 items-center">
      <h2 class="font-semibold text-gray-800 text-3xl leading-tight">
        Book Edit
      </h2>
      <div class="space-x-4 flex max-[600px]:flex-col max-[600px]:items-center max-[600px]:gap-2">
        <Link
          v-if="props.book.user_id == user.id"
          :href="route('books.show', { id: props.book.id })"
        >
          <Button
            icon="pi pi-eye"
            label="View"
            severity="contrast"
            raised
            outlined
            size="small"
          />
        </Link>
        
      </div>
    </div>
    <!-- </template> -->
    <p></p>
    <div class="px-32 ">
      <!-- <img :src="props.book.cover.replace('public/', '/storage/')" alt="" class="w-fit"> -->
      <!-- <Select v-model="selectedCategory" :options="props.categories"  optionLabel="name" optionValue="id" placeholder="Change Category" class="w-full md:w-56" /> -->
      <div class="flex flex-col justify-start gap-2  md:w-full md:px-32">
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="author" class="w-24 font-semibold">Category</label>
          <Select
            v-model="InputCategory"
            :options="props.categories"
            optionLabel="name"
            optionValue="id"
            placeholder="Select a Category"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="author" class="w-24 font-semibold">Title</label>
          <InputText
            v-model="InputTitle"
            id="title"
            autocomplete="off"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="author" class="w-24 font-semibold">Author</label>
          <InputText
            v-model="InputAuthor"
            id="author"
            class="flex-auto"
            autocomplete="off"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="edition" class="w-24 font-semibold">Edition</label>
          <InputText
            v-model="InputEdition"
            id="edition"
            class="flex-auto"
            autocomplete="off"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">Price</label>
          <InputNumber
            v-model="InputPrice"
            inputId="integeronly"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">Format</label>
          <SelectButton
            v-model="InputFormat"
            :options="FormatOptions"
            aria-labelledby="basic"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">Original</label>
          <SelectButton
            v-model="InputOriginal"
            :options="OriginalOptions"
            aria-labelledby="basic"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">Condition</label>
          <SelectButton
            v-model="InputCondition"
            :options="ConditionOptions"
            aria-labelledby="basic"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">State</label>
          <SelectButton
            v-model="InputState"
            :options="StateOptions"
            aria-labelledby="basic"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">Available ?</label>
          <SelectButton
            v-model="InputAvailable"
            :options="AvailableOptions"
            aria-labelledby="basic"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label for="ssn" class="w-24 font-semibold">ISBN</label>
          <InputNumber
            v-model="InputIsbn"
            inputId="integeronly"
            class="flex-auto"
          />
        </div>
        <div class="flex md:flex-row flex-col items-center gap-4 mb-2">
          <label class="w-24 font-semibold">Description</label>
          <Textarea
            v-model="InputDescription"
            rows="5"
            cols="30"
            class="flex-auto"
          />
         
        </div>
        <Button
          icon="pi pi-save"
          label="Save changes"
          @click="UpdateBook"
          severity="contrast"
          raised
          class="md:mx-96 my-10"
        />
        <div
          class="flex md:flex-row flex-col justify-between items-center gap-4 mb-2"
        >
          <p class="font-bold text-3xl">Book pictures</p>
          <div v-if="images.length < 3" >
            <Button
              type="button"
              icon="pi pi-plus-circle"
              label="Add"
              @click="toggle"
            />

            <Popover ref="op">
              <div class="flex items-center justify-start max-[600px]:px-20 gap-2d max-[600px]:w-[400px]">
                <label for="Cover" class="font-semibold">Add a picture</label>
                <input
                  name="file"
                  type="file"
                  class="flex-auto"
                  @change="onChange"
                />
                <Button
                  label="Add"
                  outlined
                  severity="contrast"
                  @click="AddPicture"
                />
              </div>
            </Popover>
          </div>
        </div>
        <div class="md:card sm:flex sm:justify-center max-[600px]:w-full">
          <Galleria
            :value="images"
            :responsiveOptions="responsiveOptions"
            :numVisible="5"
            containerStyle="max-width: 1000px"
          >
            <template #item="slotProps">
              <img
                :src="
                  slotProps.item == null
                    ? ''
                    : slotProps.item.replace('public/', '/storage/')
                "
                :alt="slotProps.item == null ? 'no image' : 'My image'"
                style="width: 70%"
              />
            </template>
            <template #thumbnail="slotProps">
              <img
                :src="
                  slotProps.item == null
                    ? ''
                    : slotProps.item.replace('public/', '/storage/')
                "
                :alt="slotProps.item == null ? 'no image' : 'My image'"
              />
            </template>
          </Galleria>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>