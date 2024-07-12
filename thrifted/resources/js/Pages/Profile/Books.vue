<script setup>
import AuthenticatedLayout from '@/Layouts/NewLayout/AuthenticatedLayout.vue';
import Tag from 'primevue/tag';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Slider from 'primevue/slider';
import axios from 'axios';
import InfiniteLoading from "v3-infinite-loading";
import { computed, ref } from 'vue';
const getSeverity = (status) => {
    if (status == true) {
        return 'success';
    } else {
        return 'danger';
    }
};
const props = defineProps({
    user_id:{
        type: Number,
        required: true
    }
})
const conditions = ref([
    { name: 'All', code: 'All' },
    { name: 'Good', code: 'Good' },
    { name: 'High', code: 'High' },
    { name: 'Normal', code: 'Normal' },
    { name: 'Acceptable', code: 'Acceptable' },
    { name: 'Low', code: 'Low' },
]);
const formats = ref([
    { name: 'All', code: 'All' },
    { name: 'Paperback', code: 'Paperback' },
    { name: 'Hardback', code: 'Hardback' },
    { name: 'Pocket', code: 'Pocket' }
]);
const states = ref([
    { name: 'All', code: -1 },
    { name: 'New', code: 1 },
    { name: 'Used', code: 0 }
]);
const original = ref([
    { name: 'All', code: -1 },
    { name: 'Original', code: 1 },
    { name: 'Copied', code: 0 }
]);
const available = ref([
    { name: 'All', code: -1 },
    { name: 'Available', code: 1 },
    { name: 'Sold', code: 0 },
]);
const value = ref([0, 50]);
const SearchInput = ref('');
const ConditionInput = ref('All');
const FormatInput = ref('All');
const StateInput = ref(-1);
const OriginalInput = ref(-1);
const AvailableInput = ref(-1);
const Books = ref([]);
const Page = ref(1);
function GetPriceRange(percentage) {
    return percentage * 50000 / 100;
}

const ComputedBooks = computed(() => {
    let books = Books.value;
    if (SearchInput.value != '') {
        books = books.filter(book => book.title.toUpperCase().includes(SearchInput.value.toUpperCase()) ||
            book.edition.toUpperCase().includes(SearchInput.value.toUpperCase()) ||
            book.author.toUpperCase().includes(SearchInput.value.toUpperCase()));
    }
    if (ConditionInput.value !== 'All') {
        books = books.filter(book => book.condition == ConditionInput.value);
    }
    if (FormatInput.value !== 'All') {
        books = books.filter(book => book.format == FormatInput.value);
    }
    if (StateInput.value != -1) {
        books = books.filter(book => book.new == StateInput.value);
    }
    if (OriginalInput.value != -1) {
        books = books.filter(book => book.original == OriginalInput.value);
    }
    if (AvailableInput.value != -1) {
        books = books.filter(book => book.available == AvailableInput.value);
    }
    books = books.filter(book => {
        if (book.price != 0) {
            return book.price >= GetPriceRange(value.value[0]) && book.price <= GetPriceRange(value.value[1])
        } else {
            return true;
        }
    });
    return books;
});

async function HandleLoadMore()
{
    try {
        let response = await axios.get(`/profile/${props.user_id}/books_pagination?page=${Page.value}`);
        response.data.data.forEach((book) =>{
            Books.value.push(book);
        });
        console.log('handle ' +Page.value);
        Page.value = Page.value + 1;
    } catch (error) {
        console.log(error);
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full flex justify-start h-24 items-center space-x-4 px-32 mt-16 ">
            <div class="mx-auto justify-self-start flex flex-col justify-start h-full items-start">
                <p>Filter by search</p>
                <IconField>
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="SearchInput" placeholder="Search" />

                </IconField>
            </div>
            <div class="w-72 flex flex-col justify-start h-full items-start">
                <p>Filter by price</p>
                <Slider v-model="value" range class="w-full my-4" />
                <p class="flex justify-around w-full items-center"><span>Min : {{ GetPriceRange(value[0]) }}Da </span>
                    <span>Max
                        : {{ GetPriceRange(value[1]) }}Da</span></p>
            </div>

            <div class="w-52 flex flex-col h-full justify-start items-start">
                <p>Filter Condition</p>
                <Select v-model="ConditionInput" :options="conditions" optionLabel="name" optionValue="code"
                    placeholder="Filter Condition" class="w-52" />
            </div>
            <div class="w-52 flex flex-col h-full justify-start items-start">
                <p>Filter Format</p>
                <Select v-model="FormatInput" :options="formats" optionLabel="name" optionValue="code"
                    placeholder="Filter Format" class="w-52" />
            </div>
            <div class="w-52 flex flex-col h-full justify-start items-start">
                <p>Filter State</p>
                <Select v-model="StateInput" :options="states" optionLabel="name" optionValue="code"
                    placeholder="Filter State" class="w-52" />

            </div>
            <div class="w-52 flex flex-col h-full justify-start items-start">
                <p>Filter Originlity</p>
                <Select v-model="OriginalInput" :options="original" optionLabel="name" optionValue="code"
                    placeholder="Filter Original" class="w-52" />
            </div>
            <div class="w-52 flex flex-col h-full justify-start items-start">
                <p>Filter Availability</p>
                <Select v-model="AvailableInput" :options="available" optionLabel="name" optionValue="code"
                    placeholder="Filter By Availability" class="w-52" />
            </div>

        </div>
        <div class="w-full  px-32 py-16 grid grid-cols-6">
            <div v-for="book in ComputedBooks" :key="book.id">
                <div class="m-2 p-2 border bg-white rounded-xl">
                    <Tag :severity="getSeverity(book.available)" :value="book.available == 1 ? 'available' : 'sold'"
                        rounded></Tag>
                    <Link :href="route('books.show', { id: book.id })">
                    <div class="mb-4">
                        <div class="relative flex justify-center mx-auto">

                            <img :src="book.cover == null ? '/cover-not-available.jpg' : book.cover.replace('public/', '/storage/')"
                                :alt="book.title" class="rounded h-40" />
                        </div>
                    </div>
                    </Link>
                    <div class="mb-1 font-medium text-xl">{{ book.title }}</div>
                    <div class="mb-4 font-medium text-sm">{{ book.author }}</div>
                    <div class="flex justify-between items-center">
                        <p class="mt-0 font-semibold text-xl">{{ book.price == 0 ? 'No price yet' :
                            book.price }}
                        </p>
                        <span v-if="book.user.id === props.user_id">

                            <Button icon="pi pi-trash" style="font-size: 0.5rem" severity="secondary" outlined />
                            <Button icon="pi pi-pencil" style="font-size: 0.5rem" class="ml-2" />
                        </span>
                        <span v-else>
                            <Button icon="pi pi-heart" style="font-size: 0.5rem" severity="secondary" @click="this.setSeverity('danger')"  />
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <InfiniteLoading @infinite="HandleLoadMore" />
    </AuthenticatedLayout>

</template>

