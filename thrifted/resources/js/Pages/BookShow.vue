<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Divider from 'primevue/divider';
import Button from 'primevue/button';
import { computed, popScopeId, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import Carousel from 'primevue/carousel';
import Badge from 'primevue/badge';
import Tag from 'primevue/tag';
const page = usePage()
const props = defineProps({
    book: {
        type: Object,
        required: true,
    },
    related_books: {
        type: Array,
        required: true,
    },
    cards:{
        type: Array,
        required: true,
    }
});
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
        breakpoint: '1200px',
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
const user = computed(() => { return page.props.auth.user; });
const BookImages = computed(() => {
    let images = [];
    if (props.book.cover) {
        images.push(props.book.cover.replace('public/', '/storage/'));
    };

    props.book.pictures.forEach(picture => {
        images.push(picture.picture.replace('public/', '/storage/'));
    });
    return images;
})

</script>

<template>

    <!-- <Head title="Book view" />
    <NavBar /> -->
    
    <AuthenticatedLayout class="px-20">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-gray-800 text-xl leading-tight">Book view </h2>
                <Link v-if="props.book.user_id == user.id" :href="route('books.edit', { id: props.book.id })">
                <Button icon="pi pi-pencil" label="Update" severity="contrast" raised outlined size="small" />
                </Link>
            </div>
        </template>
        <div
            class="flex md:flex-row min-[200px]:flex-col sm:flex-col justify-center items-stretch gap-4 mt-2 2xl:px-96 w-full  2xl:h-[500px]">
            <!-- <img :src="props.book.cover == null ? '/cover-not-available.jpg' : props.book.cover.replace('public/', '/storage/')"

                alt="" class="w-fit"> -->
            <Carousel :value="BookImages" :numVisible="1" :numScroll="1" class="sm:w-[500px]  ">
                <template #item="slotProps">
                    <div class="border   border-surface-200 dark:border-surface-700 rounded ">
                        <div class="w-full">
                            <img :src="slotProps.data" :alt="slotProps.data" class="w-full  rounded" />

                        </div>
                    </div>
                </template>
            </Carousel>

            <div class="flex flex-col justify-start gap-2 w-full">
                <p class="font-semibold text-2xl text-gray-500">Category : {{ props.book.category.name }}</p>
                <p class="text-4xl">Title : {{ props.book.title }}</p>
                <p class="font-bold text-3xl">Price : {{ props.book.price }}DA <span v-if="props.book.old_price != null"
                        class="text-red-300 text-xl line-through">{{ props.book.old_price }} DA</span></p>
                <!-- <p class="font-bold text-2xl">256 pages</p> -->

                <p class="font-bold text-base">Format :{{ props.book.format }}</p>
                <p class="font-bold text-base">{{ props.book.condition }} quality</p>
                <p class="text-xl" :class="props.book.available ? 'text-green-500' : 'text-red-400'">{{
                    props.book.available ?
                        'Available' : 'Sold' }}</p>
                <p class="text-xl">
                    Seller :
                    <Link :href="route('profile.show', { id: props.book.user_id })">
                    {{
                        props.book.user.name

                    }}
                    </Link>
                </p>

                <div class="flex justify-self-end justify-start items-center gap-2 mt-auto w-full"
                    v-if="props.book.user_id != user.id">
                    <Link :href="route('books.chat_with_seller', { id: props.book.id })">
                    <Button label="Request" icon="pi pi-credit-card" size="small" raised />
                    </Link>
                    <Button label="Add to card" icon="pi pi-shopping-cart" size="small" severity="contrast" raised />
                </div>
                <div v-else class="flex justify-self-end justify-start items-center gap-2 mt-auto w-full">
                    <NavLink :href="route('books.edit', { id: props.book.id })">
                        <Button label="Update" icon="pi pi-pencil" size="small" raised />
                    </NavLink>
                </div>
                <div class="flex flex-col justify-self-end justify-start items-stretch mt-auto">
                    <p>ISBN : {{ props.book.isbn == null ? 'No isbn provided' : props.book.isbn }}</p>
                    <hr>
                    <div>Tags:
                        <template v-for="tag in props.book.tags">
                            <Badge :value="tag.name" severity="secondary" class="mr-2"></Badge>
                        </template>
                    </div>
                    <hr>
                    <p>Author : {{ props.book.author }}</p>
                </div>
            </div>
        </div>
        <Divider />
        <p class="ml-10 font-black text-4xl">See Also</p>
        <Carousel :value="props.related_books"
            :numVisible="props.related_books.length < 4 ? 4 : props.related_books.length" :numScroll="1"
            :responsiveOptions="responsiveOptions">
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
                        <div class="mt-0 font-semibold text-xl">${{ slotProps.data.price }}</div>
                        <span>
                            <Button icon="pi pi-heart" severity="secondary" outlined />
                            <Button icon="pi pi-shopping-cart" class="ml-2" />
                        </span>
                    </div>
                </div>
            </template>
        </Carousel>
    </AuthenticatedLayout>
</template>