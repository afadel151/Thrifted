<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from "vue";
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import Button from "primevue/button";
import Textarea from "primevue/textarea/Textarea.vue";
import NavBar from '../Components/NavBar.vue';
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext/InputText.vue";
import Avatar from "primevue/avatar";
import MultiSelect from 'primevue/multiselect';
import InputNumber from "primevue/inputnumber/InputNumber.vue";
import Tag from 'primevue/tag';
import SelectButton from 'primevue/selectbutton';
const file = ref(null);
const InputTitle = ref('');
const InputEdition = ref('');
const InputAuthor = ref('');
const InputIsbn = ref(0);
const InputPrice = ref(0);
const InputDescription = ref('');
const SelectedTags = ref([])
const visible = ref(false);
const FormatOptions = ref(['Hardcover', 'Paperback', 'Pocket']);
const InputFormat = ref('');
const ConditionOptions = ref(['New', 'Used']);
const InputCondition = ref('');
function ResetInputs() {
    file.value = '';
    InputTitle.value = '';
    InputEdition.value = '';
    InputAuthor.value = '';
    InputIsbn.value = 0;
    InputDescription.value = '';
    SelectedTags.value = [];
}

async function SaveBook() {
    if (InputTitle.value == '' || InputAuthor.value == '' || SelectedTags.value == [] || InputFormat.value == '' || InputCondition.value == '') {
        alert("you must fill al inputs ");
    }
    else {
        visible.value = false;
        let fd = new FormData();
        fd.append('file', file.value);
        fd.append('title', InputTitle.value,);
        fd.append('edition', InputEdition.value);
        fd.append('author', InputAuthor.value);
        fd.append('isbn', InputIsbn.value);
        fd.append('description', InputDescription.value);
        SelectedTags.value.forEach(tag => {
            fd.append('tags[]', tag);
        });
        fd.append('user_id', props.user.id);
        fd.append('format', InputFormat.value);
        fd.append('condition', InputCondition.value);
        fd.append('price', InputPrice.value);
        // console.log(fd.get('tags'));
        try {
            let response = await axios.post('/api/books/create', fd);
            console.log(response.data);
            MyBooks.value.push(response.data);
        } catch (error) {
            console.log(error);
        }
        ResetInputs();
    }

}
function onChange(e) {
    file.value = e.target.files[0];
}


const props = defineProps(
    {
        user: {
            type: Object,
            required: true,
        },
        books: {
            type: Array,
            required: true,
        },
        tags: {
            type: Array,
            required: true,
        },
    },
);
const MyBooks = ref(props.books);
const getSeverity = (status) => {
    switch (status) {
        case '1':
            return 'success';


        case '0':
            return 'danger';

        default:
            return null;
    }
};
</script>

<template>

    <Head title="My Books" />
    <NavBar />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-gray-800 text-xl leading-tight">My books </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="bg-white shadow-sm p- p-5 sm:rounded-lg overflow-hidden">
                    <div class="p-6 text-gray-900">Here are your books!</div>
                    <div class="grid grid-cols-4">
                        <div v-for="book in MyBooks">
                            <div class="m-2 p-2 border rounded">
                                <Tag :severity="getSeverity(book.available)"
                                    :value="book.available == 1 ? 'available' : 'sold'" rounded></Tag>
                                <!-- <Tag value="New"></Tag> -->
                                <Link :href="route('books.show', { id: book.id })">
                                <div class="mb-4">
                                    <div class="relative flex justify-center mx-auto">
                                        
                                        <img  :src="book.cover == null ? '/cover-not-available.jpg' : book.cover.replace('public/', '/storage/')" :alt="book.title"
                                            class="rounded h-40" />


                                    </div>
                                </div>
                                </Link>
                                <div class="mb-1 font-medium text-xl">{{ book.title }}</div>
                                <div class="mb-4 font-medium text-sm">{{ book.author }}</div>
                                <div class="flex justify-between items-center">
                                    <p class="mt-0 font-semibold text-xl">{{ book.price == 0 ? 'No price yet' : book.price }}</p>
                                    <span>

                                        <Button icon="pi pi-trash" style="font-size: 0.5rem" severity="secondary"
                                            outlined />
                                        <Button icon="pi pi-pencil" style="font-size: 0.5rem" class="ml-2" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Button label="Show" @click="visible = true" />
                    <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '40rem' }">
                        <template #header>
                            <div class="inline-flex justify-center items-center gap-2">
                                <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
                                    shape="circle" />
                                <span class="font-bold whitespace-nowrap">{{ props.user.name }}</span>
                            </div>
                        </template>
                        <span class="block mb-8 text-surface-500 dark:text-surface-400">Add a Book</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="title" class="w-24 font-semibold">Title</label>
                            <InputText v-model="InputTitle" id="title" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="edition" class="w-24 font-semibold">Edition</label>
                            <InputText v-model="InputEdition" id="edition" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="author" class="w-24 font-semibold">Author</label>
                            <InputText v-model="InputAuthor" id="author" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="ssn" class="w-24 font-semibold">ISBN</label>
                            <InputNumber v-model="InputIsbn" inputId="integeronly" class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label class="w-24 font-semibold">Description</label>
                            <Textarea v-model="InputDescription" rows="5" cols="30" class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label class="w-24 font-semibold">Tags</label>
                            <MultiSelect v-model="SelectedTags" display="chip" :options="props.tags" optionLabel="name"
                                optionValue="id" filter placeholder="Select Cities" :maxSelectedLabels="3"
                                class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="ssn" class="w-24 font-semibold">Format</label>
                            <SelectButton v-model="InputFormat" :options="FormatOptions" aria-labelledby="basic"
                                class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="ssn" class="w-24 font-semibold">Condition</label>
                            <SelectButton v-model="InputCondition" :options="ConditionOptions" aria-labelledby="basic"
                                class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="ssn" class="w-24 font-semibold">Price</label>
                            <InputNumber v-model="InputPrice" inputId="integeronly" class="flex-auto" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">

                            <label for="Cover" class="w-24 font-semibold">Cover</label>
                            <input name="file" type="file" class="flex-auto" @change="onChange" />
                        </div>

                        <template #footer>
                            <Button label="Cancel" text severity="secondary" @click="visible = false" autofocus />
                            <Button label="Save" outlined severity="secondary" @click="SaveBook" autofocus />
                        </template>
                    </Dialog>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
