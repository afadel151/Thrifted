<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Carousel from 'primevue/carousel';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Rating from 'primevue/rating';
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    books: {
        type: Array,
        required: true,
    },
    soldbooks: {
        type: Array,
        required: true,
    },
    rating:{
        type: Number,
        required: true,
    }
});
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
const getSeverity = (status) => {
    if (status == true) {
        return 'success';
    } else {
        return 'danger';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="font-[Mulish] pt-10 bg-slate-100">
            <div class="mx-auto bg-white max-w-7xl flex h-full  shadow-lg sm:px-6 lg:px-8 sm:rounded-lg overflow-hidden py-10">
                <div class="w-[40%] flex p-20  justify-center items-center h-full ">
                    <img src="/blank-profile-picture.webp" alt="" class="w-full rounded-full">
                </div>
                <div class=" flex flex-col text-xl   items-start justify-around w-full ml-20 ">
                    <p ><span class="font-bold">Name :</span> {{ props.user.name }}</p>
                    <p><span class="font-bold">Email :</span>{{ props.user.email }}</p>
                    <p><span class="font-bold">Adress :</span> {{ props.user.adress }}</p>
                    <p v-if="props.user.phone != null"><span class="font-bold">Phone :</span> {{ props.user.phone }}</p>
                    <p><span class="font-bold">Delivery ? :</span> {{ props.user.delivery == 1 ? 'Included' : 'No delivery options' }}</p>
                    <p><span class="font-bold">Payment :</span> {{ props.user.payment_on_hand == 1 ? 'Hand to hand' : 'On delivery' }}</p>
                    <div class="flex justify-start ">
                        <p class="font-bold mr-10"> Rating : </p>
                        <Rating v-model="props.rating" severity="contrast" readonly />
                        <p>({{ props.user.ratings.length }})</p>
                    </div>
                    <p><span class="font-bold">Available books :</span> {{ props.books.filter(book=> {return book.available == 1}).length }}</p>
                    <p><span class="font-bold">Sold books :</span> {{ props.soldbooks.length }}</p>
                </div>
            </div>
            <div class="mx-auto mt-6 flex flex-col items-stretch bg-white max-w-7xl  h-full  shadow-lg sm:px-6 lg:px-8 sm:rounded-lg overflow-hidden py-10">
                <div class="w-full flex justify-between items-center">  
                    <div class="p-6 text-gray-900 text-4xl">📚 {{ props.user.name }}'s books!</div>
                    <Button label="View All" severity="contrast" outlined raised />
                </div>
                <Carousel :value="props.books" :numVisible="4" :numScroll="1" :responsiveOptions="responsiveOptions">
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
            </div>
            <div class="mx-auto mt-6 flex flex-col items-stretch bg-white max-w-7xl  h-full  shadow-lg sm:px-6 lg:px-8 sm:rounded-lg overflow-hidden py-10">
                <div class="w-full flex justify-between items-center">  
                    <div class="p-6 text-gray-900 text-4xl">📚 {{ props.user.name }}'s sold books!</div>
                    <Button label="View All" severity="contrast" outlined raised />
                </div>
                <Carousel :value="props.soldbooks" :numVisible="4" :numScroll="1" :responsiveOptions="responsiveOptions">
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>