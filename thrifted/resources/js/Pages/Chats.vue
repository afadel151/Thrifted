<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatBlock from '@/Components/ChatBlock.vue';
const props = defineProps({
    chats: {
        type: Array,
        required: true
    }
})



</script>
<template>
    <AuthenticatedLayout>
        <div style="height: calc(100vh - 64px);" class=" bg-slate-100">
            <div class="sm:px-6 sm:py-6 h-full w-full">
                <div class="bg-white h-full shadow-sm p-5 sm:rounded-lg">
                    <div class="flex h-full w-full rounded-lg border-2">
                        <div class="w-1/6 bg-white flex flex-col justify-start border-r h-full border-gray-300">
                            <div v-for="chat in props.chats" :key="chat.id">
                                    <ChatBlock :chat="chat" />
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