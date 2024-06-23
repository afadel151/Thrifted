<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
    }else{
        return 'danger';
    }
};

const AddNewBook = (book) => {
    MyBooks.value.push(book);
};
const SoldBooks = computed(()=>{
    let books = props.books;
    books =  books.filter(book =>  book.available == 0 );
    return books;
});
const BooksWithNoPrice = computed(() =>{
    let books = props.books;
    books =  books.filter(book =>  book.price == 0 );
    return books;
})
</script>

<template>
    <Head title="My Books" />
    <NavBar />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-gray-800 text-xl leading-tight">My books </h2>
        </template>

        <div class="py-12 w-full bg-slate-100 overflow-y-scroll " style="height: calc(100vh - 64px);">
            <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl ">
                <div class="bg-white shadow-sm  p-5 sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-gray-900 text-4xl">📚 Here are your books!</div>
                    <BookAdd :user="props.user" :categories="props.categories" :tags="props.tags" @add-book="AddNewBook" />
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div v-for="book in MyBooks">
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
                <div class="bg-white mt-2 shadow-sm p- p-5 sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-gray-900 text-5xl">📦 Your sold books!</div>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div v-for="book in SoldBooks">
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
                <div class="bg-white mt-2 shadow-sm p- p-5 sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="p-6 text-gray-900 text-5xl">💸 Books with no price</div>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div v-for="book in BooksWithNoPrice">
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
