<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavLink from '@/Components/NavLink.vue';
import Button from 'primevue/button';
import Badge from 'primevue/badge';
const user = usePage().props.auth.user;
const props = defineProps({
    chats: {
        type: Array,
        required: true
    }
})
const SellChats = ref(false);
const EnterChat = (target_id) => {
    if (target_id == user.id) {
        SellChats.value = true;
    } else {
        SellChats.value = false;
    }
}
const GetSize = (A) => {
    // console.log(A);
    return A.length;
}

</script>
<template>
    <AuthenticatedLayout>
        <div style="height: calc(100vh - 64px);" class=" bg-slate-100">
            <div class="sm:px-6 sm:py-6 h-full w-full">
                <div class="bg-white h-full shadow-sm p-5 sm:rounded-lg">
                    <div class="flex h-full w-full rounded-lg border-2">
                        <div class="w-1/6 bg-white flex flex-col justify-start border-r h-full border-gray-300">
                            <div v-for="chat in props.chats" :key="chat.id">
                                <div class="flex    items-center cursor-pointer hover:bg-gray-100 rounded-md">
                                    <NavLink class="w-full flex flex-col items-stretch  justify-between px-2"
                                        :href="route('chats.show', { id: chat.id })"
                                        :active="route().current('chats.show', { id: chat.id })">
                                        <div class="w-full flex justify-between px-2 items-center">
                                            <Button
                                                :label="chat.target_id == user.id ? chat.creator.name : chat.target.name"
                                                icon="pi pi-shop" text plain />
                                            <Badge :value="GetSize(chat.unseen_messages)"
                                                v-if="GetSize(chat.unseen_messages) > 0" severity="contrast"></Badge>
                                        </div>
                                        <p>{{ chat.latest_message }}</p>
                                    </NavLink>

                                </div>

                            </div>
                        </div>
                        <div class="h-full flex flex-col  w-full ">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>