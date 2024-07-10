<script setup>
import axios from "axios";
import { onBeforeMount, ref } from "vue";
import { Link } from "@inertiajs/vue3";
const Categories = ref([]);
onBeforeMount(()=>{
    axios.get('/api/categories').then((response)=>{
        Categories.value = response.data;
        console.log(Categories.value);
    }).catch((error)=>{
        console.log(error);
    })
})
</script>

<template>
    <div class="w-full flex-col gap-1 flex justify-start items-start px-10">
        <p class="text-2xl mb-5 font-bold">Looking for a category ?</p>
        <div v-for="category in Categories" :key="category.id">
            <Link :href="'/categories/'+category.id" class="text-lg hover:bg-slate-200 p-1 rounded-xl">{{ category.name }}</Link>
        </div>
    </div>
</template>