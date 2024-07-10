<script setup>
import axios from "axios";
import { onBeforeMount, ref } from "vue";
import { Link } from "@inertiajs/vue3";
const Tags = ref([]);
onBeforeMount(()=>{
    axios.get('/api/tags').then((response)=>{
        Tags.value = response.data;
        console.log(Tags.value);
    }).catch((error)=>{
        console.log(error);
    })
})
</script>

<template>
    <div class="w-full flex-col gap-1 flex justify-start items-start px-10">
        <p class="text-2xl mb-5 font-bold">Or a relation ?</p>
        <div v-for="category in Tags" :key="category.id">
            <Link :href="'/Tags/'+category.id" class="text-lg hover:bg-slate-200 p-1 rounded-xl">{{ category.name }}</Link>
        </div>
    </div>
</template>