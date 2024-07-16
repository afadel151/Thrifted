<script setup>
import AuthenticatedLayout from '@/Layouts/NewLayout/AuthenticatedLayout.vue';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Slider from 'primevue/slider';
import axios from 'axios';
import InfiniteLoading from "v3-infinite-loading";
import { computed, ref } from 'vue';
import CarouselUser from '@/Components/CarouselUser.vue';
const props = defineProps({
    query:{
        type: String,
        required: true
    }
})

const Users = ref([]);
const Page = ref(1);
async function HandleLoadMore()
{
    try {
        let response = await axios.get(`/api/users/${props.query}/search_pagination?page=${Page.value}`);
        response.data.data.forEach((book) =>{
            Users.value.push(book);
        });
        Page.value = Page.value + 1;
    } catch (error) {
        console.log(error);
    }
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full flex justify-start h-24 items-center space-x-4 px-20 mt-16 ">
            <div class="mx-auto justify-self-start flex flex-col justify-start h-full items-start">
                <p>Filter by search</p>
                <IconField>
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="SearchInput" placeholder="Search" />

                </IconField>
            </div>
        </div>
        <div class="w-full  sm:px-32 px-10 py-16 grid sm:grid-cols-6">
            <div v-for="user in Users" :key="user.id">
                <CarouselUser :user="user"/>
            </div>
        </div>
        <InfiniteLoading @infinite="HandleLoadMore" />
    </AuthenticatedLayout>

</template>

