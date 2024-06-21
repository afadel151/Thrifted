<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MyLayout from '@/Layouts/MyLayout.vue';
import NavLink from '@/Components/NavLink.vue';
import Button from 'primevue/button';
const user = usePage().props.auth.user;
const props = defineProps({
    chats: {
        type: Array,
        required: true
    }
})
const SellChats = ref(false);
const EnterChat = (target_id)=>{
    if (target_id == user.id) {
        SellChats.value = true;
    }else{
        SellChats.value = false;
    }
}

</script>
<template>
    <MyLayout>
        <div class="h-screen bg-slate-100">
            <div class="sm:px-6 h-full w-full">
                <div class="bg-white h-full shadow-sm p-5 sm:rounded-lg">
                    <div class="flex h-full w-full rounded-lg border-2">
                        <div class="w-1/6 bg-white flex flex-col justify-start border-r h-full border-gray-300">
                            <div class="overflow-y-auto" v-for="chat in props.chats" :key="chat.id">
                                <div class="flex items-center cursor-pointer hover:bg-gray-100 rounded-md">
                                    <NavLink :href="route('chats.show', { id: chat.id })" :active="route().current('chats.show', { id: chat.id })">
                                        <Button :label="chat.target_id == user.id ? chat.creator.name : chat.target.name" icon="pi pi-shop" text plain />
                                    </NavLink>
                                </div>
                            </div>
                        </div>
                        <div  class="h-full flex flex-col  w-full ">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MyLayout>
</template>