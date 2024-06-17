<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Divider from 'primevue/divider';
import Button from 'primevue/button';
import Select from 'primevue/select';
import SelectButton from 'primevue/selectbutton';

import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';
import FileUpload from 'primevue/fileupload';
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3'
import Galleria from 'primevue/galleria';
const page = usePage()
const props = defineProps({
    book: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    }
})
const selectedCategory = ref(props.book.category_id);
const InputTitle = ref(props.book.title);
const InputEdition = ref(props.book.edition);
const InputAuthor = ref(props.book.author);
const InputIsbn = ref(props.book.isbn);
const InputPrice = ref(props.book.price);
const InputDescription = ref(props.book.description);
const FormatOptions = ref(['Hardcover', 'Paperback', 'Pocket']);
const OriginalOptions = ref(['Original', 'Copied']);
const InputOriginal = ref(props.book.original == true ? 'Original' : 'Copied');
const InputFormat = ref(props.book.format);
const ConditionOptions = ref(['Low', 'Acceptable', 'Normal', 'Good ', 'High']);
const StateOptions = ref(['New', 'Used']);
const InputState = ref(props.book.new == true ? 'New' : 'Used');
const InputCondition = ref(props.book.condition);
const user = computed(() => { return page.props.auth.user; })
// const images = computed(() => { 
    
//     return props.book.cover; 
// });
// const responsiveOptions = ref([
//     {
//         breakpoint: '1300px',
//         numVisible: 4
//     },
//     {
//         breakpoint: '575px',
//         numVisible: 1
//     }
// ]);
</script>
<template>

    <Head title="Book view" />
    <NavBar />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 text-xl leading-tight">Book view </h2>
                <Link v-if="props.book.user_id == user.id" :href="route('books.show', { id: props.book.id })">
                <Button icon="pi pi-pencil" label="View" severity="contrast" raised outlined size="small" />
                </Link>
            </div>
        </template>
        <div class="flex justify-center items-stretch gap-4 mt-2 px-96 w-[70%] h-[430px]">
            <!-- <img :src="props.book.cover.replace('public/', '/storage/')" alt="" class="w-fit"> -->
            <!-- <Select v-model="selectedCategory" :options="props.categories"  optionLabel="name" optionValue="id" placeholder="Change Category" class="w-full md:w-56" /> -->
            <div class="flex flex-col justify-start gap-2 w-full">
                <div class="flex items-center gap-4 mb-2">
                    <label for="author" class="w-24 font-semibold">Category</label>
                    <Select v-model="InputCategory" :options="props.categories" optionLabel="name" optionValue="id"
                        placeholder="Select a Category" class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="author" class="w-24 font-semibold">Title</label>
                    <InputText v-model="InputTitle" id="title" autocomplete="off" class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="author" class="w-24 font-semibold">Author</label>
                    <InputText v-model="InputAuthor" id="author" class="flex-auto" autocomplete="off" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="edition" class="w-24 font-semibold">Edition</label>
                    <InputText v-model="InputEdition" id="edition" class="flex-auto" autocomplete="off" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="ssn" class="w-24 font-semibold">Price</label>
                    <InputNumber v-model="InputPrice" inputId="integeronly" class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="ssn" class="w-24 font-semibold">Format</label>
                    <SelectButton v-model="InputFormat" :options="FormatOptions" aria-labelledby="basic"
                        class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="ssn" class="w-24 font-semibold">Original</label>
                    <SelectButton v-model="InputOriginal" :options="OriginalOptions" aria-labelledby="basic"
                        class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="ssn" class="w-24 font-semibold">Condition</label>
                    <SelectButton v-model="InputCondition" :options="ConditionOptions" aria-labelledby="basic"
                        class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label for="ssn" class="w-24 font-semibold">ISBN</label>
                    <InputNumber v-model="InputIsbn" inputId="integeronly" class="flex-auto" />
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <label class="w-24 font-semibold">Description</label>
                    <Textarea v-model="InputDescription" rows="5" cols="30" class="flex-auto" />
                </div>
                <p>Book pictures</p>
                <!-- <div class="card">
                    <Galleria :value="images" :responsiveOptions="responsiveOptions" :numVisible="5"
                        containerStyle="max-width: 640px">
                        <template #item="slotProps">
                            <img :src="slotProps.item.cover" :alt="slotProps.item.alt" style="width: 100%" />
                        </template>
                        <template #thumbnail="slotProps">
                            <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" />
                        </template>
                    </Galleria>
                </div> -->
            </div>
        </div>
        <Divider />

    </AuthenticatedLayout>
</template>