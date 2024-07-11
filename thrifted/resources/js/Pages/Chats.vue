<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import ChatBlock from '@/Components/ChatBlock.vue';
const props = defineProps({
    chats: {
        type: Array,
        required: true
    }
});
</script>
<template>
    <AuthenticatedLayout>
        <div style="height: calc(100vh - 156px);" >
            <div class=" p-10 h-full w-full">
                <div class=" h-full shadow-sm p-2 sm:rounded-lg">
                    <div class="flex gap-5 h-full  w-full rounded-lg ">
                        <div class="w-2/6 border-b-2 rounded-2xl p-2  bg-white flex flex-col  gap-4 justify-start overflow-y-scroll  h-full ">
                            <div v-for="chat in props.chats" :key="chat.id">
                                    <ChatBlock :chat="chat" />
                            </div>
                        </div>
                        <div class="h-full bg-white rounded-2xl border-2 rounded-t-[40px] flex flex-col  w-full ">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>