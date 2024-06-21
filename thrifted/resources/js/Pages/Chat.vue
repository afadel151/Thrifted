<script setup>
import { ref } from "vue";  
const props = defineProps({
    messages:{
        type: Array,
        required:true
    },
    chats:{
        type: Array,
        required:true
    },
    creator:{
        type: Boolean,
        required:true
    }
});
const value = ref('')

import Chats from "./Chats.vue";
import InputText from 'primevue/inputtext';
import Button from "primevue/button";
</script>
<template>
    <Chats :chats="props.chats">
        <div class="h-full p-5 w-full flex  flex-col justify-end items-stretch ">
            <div class="chat place w-full flex" v-for="message in props.messages" :class="message.creator == true ? 'justify-end' : 'justify-start'">
                <!-- {{ message.message }} -->
                <div class="flex  cursor-pointer">
                    <div class="flex max-w-96  rounded-lg p-3 gap-3 border-2" :class="message.creator == true ? 'bg-gray-200' : 'bg-white'">
                        <p class="text-gray-700">{{ message.message }}</p>
                    </div>
                </div>
            </div>
            <div class="flex mt-3 w-full justify-start gap-4 b  py-2 h-14 rounded-xl">
                <InputText type="text" v-model="value" class="w-11/12" />
                <Button label="Send" outlined severity="contrast" class="w-1/12" />
            </div>
        </div>

    </Chats>
</template>