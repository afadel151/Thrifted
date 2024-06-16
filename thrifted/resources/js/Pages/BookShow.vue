<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Divider from 'primevue/divider';
import Button from 'primevue/button';
import TabPanel from 'primevue/tabpanel';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const props = defineProps({
    book: {
        type: Object,
        required: true,
    }
})
const user = computed(() => { return page.props.auth.user; })
</script>

<template>

    <Head title="Book view" />
    <NavBar />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center" >
                <h2 class="font-semibold text-gray-800 text-xl leading-tight">Book view </h2>
                <Link v-if="props.book.user_id == user.id" :href="route('books.edit',{ id : props.book.id})">
                    <Button icon="pi pi-pencil" label="Update" severity="contrast" raised outlined  size="small"  />
                </Link>
            </div>
        </template>
        <div class="flex justify-center items-stretch gap-4 mt-2 px-96 w-full h-[430px]">
            <img :src="props.book.cover.replace('public/', '/storage/')" alt="" class="w-fit">

            <div class="flex flex-col justify-start gap-2 w-full">
                <p class="font-semibold text-base text-gray-500">NON-FICTION</p>
                <p class="text-3xl">{{ props.book.title }}</p>
                <p class="font-bold text-3xl">2,500.00 DA</p>
                <p class="font-bold text-xl">256 pages</p>
                <p class="font-bold text-base">Paperback</p>
                <p class="text-xl" :class="props.book.available ? 'text-green-500' : 'text-red-400'">{{
                    props.book.available ?
                    'Available' : 'Sold' }}</p>
                <div class="flex justify-self-end justify-start items-center gap-2 mt-auto w-full">
                    <Button label="Buy" icon="pi pi-credit-card" size="small" raised />
                    <Button label="Add to card" icon="pi pi-shopping-cart" size="small" severity="contrast" raised />
                </div>
                <div class="flex flex-col justify-self-end justify-start items-stretch mt-auto">
                    <p>ISBN : {{ props.book.isbn }}</p>
                    <hr>
                    <p>Category : Non-fiction</p>
                    <hr>
                    <p>Author : {{ props.book.author }}</p>
                </div>
            </div>
        </div>
        <Divider />

    </AuthenticatedLayout>
</template>