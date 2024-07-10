<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import Carousel from 'primevue/carousel';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from "vue";
import { Link } from '@inertiajs/vue3';
import Button from "primevue/button";
import BookAdd from "@/Components/BookAdd.vue";
import Tag from 'primevue/tag';
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
        categories: {
            type: Array,
            required: true,
        },
    },
);
const MyBooks = ref(props.books);
const getSeverity = (status) => {
    if (status == true) {
        return 'success';
    } else {
        return 'danger';
    }
};

const AddNewBook = (book) => {
    MyBooks.value.push(book);
};
const SoldBooks = computed(() => {
    let books = props.books;
    books = books.filter(book => book.available == 0);
    return books;
});
const BooksWithNoPrice = computed(() => {
    let books = props.books;
    books = books.filter(book => book.price == 0);
    return books;
})
const responsiveOptions = ref([
    {
        breakpoint: '1400px',
        numVisible: 2,
        numScroll: 1
    },
    {
        breakpoint: '1199px',
        numVisible: 3,
        numScroll: 1
    },
    {
        breakpoint: '767px',
        numVisible: 2,
        numScroll: 1
    },
    {
        breakpoint: '575px',
        numVisible: 1,
        numScroll: 1
    }
]);
</script>

<template>


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-gray-800 text-xl leading-tight">My books </h2>
        </template>

        <div class="bg-slate-100 py-12 w-full overflow-y-scroll" style="height: calc(100vh - 64px);">
            <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="bg-white shadow-sm p-5 sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-4xl text-gray-900">📚 Here are your books!</div>
                        <BookAdd :user="props.user" :categories="props.categories" :tags="props.tags"
                            @add-book="AddNewBook" />
                    </div>
                    <!-- <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"> -->
                        <Carousel :value="MyBooks" :numVisible="4" :numScroll="1"
                            :responsiveOptions="responsiveOptions" :class="props.books.length < 4 ? 'w-'+props.books.length+'/4' : ''">
                            <template #item="slotProps">
                                <div class="border-2 m-2 p-4 rounded">
                                    <Link :href="route('books.show', { id: slotProps.data.id })">
                                    <div class="mb-4">
                                        <div class="relative flex justify-center mx-auto">
                                            <img :src="slotProps.data.cover == null ? '/cover-not-available.jpg' : slotProps.data.cover.replace('public/', '/storage/')"
                                                :alt="slotProps.data.name" class="rounded h-52" />
                                            <Tag :value="slotProps.data.available == 1 ? 'available' : 'sold'"
                                                :severity="getSeverity(slotProps.data.available)" class="absolute"
                                                style="left:5px; top: 5px" />
                                        </div>
                                    </div>
                                    </Link>
                                    <div class="mb-4 font-medium">{{ slotProps.data.title }}</div>
                                    <div class="flex justify-between items-center">
                                        <div class="mt-0 font-semibold text-xl">{{ slotProps.data.price }} DA</div>
                                        <span>
                                            <Button icon="pi pi-heart" severity="secondary" outlined />
                                            <Button icon="pi pi-shopping-cart" class="ml-2" />
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </Carousel>
                    <!-- </div> -->
                     <Link  class="float-right" :href="route('profile.books',{id:props.user.id})">
                        <Button label="View all" />
                    </Link>
                        

                </div>
                <div class="bg-white shadow-sm mt-2 p- p-5 sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-5xl text-gray-900">📦 Your sold books!</div>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div v-for="book in SoldBooks" :key="book.id">
                            <div class="m-2 p-2 border rounded">
                                <Tag :severity="getSeverity(book.available)"
                                    :value="book.available == 1 ? 'available' : 'sold'" rounded></Tag>
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
                                    <span>

                                        <Button icon="pi pi-trash" style="font-size: 0.5rem" severity="secondary"
                                            outlined />
                                        <Button icon="pi pi-pencil" style="font-size: 0.5rem" class="ml-2" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="bg-white shadow-sm mt-2 p- p-5 sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-5xl text-gray-900">💸 Books with no price</div>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div v-for="book in BooksWithNoPrice" :key="book.id">
                            <div class="m-2 p-2 border rounded">
                                <Tag :severity="getSeverity(book.available)"
                                    :value="book.available == 1 ? 'available' : 'sold'" rounded></Tag>
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
                                    <span>

                                        <Button icon="pi pi-trash" style="font-size: 0.5rem" severity="secondary"
                                            outlined />
                                        <Button icon="pi pi-pencil" style="font-size: 0.5rem" class="ml-2" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
