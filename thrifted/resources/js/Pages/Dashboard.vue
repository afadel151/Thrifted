<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Button from "primevue/button";
import NavBar from '../Components/NavBar.vue';
import Tag from 'primevue/tag';
import Carousel from 'primevue/carousel';
import { ref } from 'vue';
const props = defineProps({
    newbooks: {
        type: Array,
        required: true,
    }
})
const getSeverity = (status) => {
    if (status == true) {
        return 'success';
    } else {
        return 'danger';
    }
};
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
        <div class="font-[Mulish] pt-10 bg-slate-100">
            <div class="mx-auto bg-white max-w-7xl  shadow-lg sm:px-6 lg:px-8 sm:rounded-lg overflow-hidden py-10">
                <p class="mb-2 ml-10 font-bold text-4xl"> 🕜 New books </p>
                <Carousel :value="props.newbooks" :numVisible="4" :numScroll="1" :responsiveOptions="responsiveOptions">
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
                            <div class="mb-4 font-medium"> <i class="pi pi-map-marker" style="color: green"></i> {{
                                slotProps.data.user.adress }}</div>
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
