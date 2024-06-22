<script setup>
import NavLink from '@/Components/NavLink.vue';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

import Echo from 'laravel-echo';
const echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 9000,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 9000,
    forceTLS: false,
    enabledTransports: ["ws"],
});

import { ref, onMounted } from "vue";
import axios from "axios";
import { usePage } from '@inertiajs/vue3';
const props = defineProps({
    messages: {
        type: Array,
        required: true
    },
    chats: {
        type: Array,
        required: true
    },
    creator: {
        type: Boolean,
        required: true
    },
    chat: {
        type: Object,
        required: true
    }
});
const user = usePage().props.auth.user;
const value = ref('');
async function SendMessage() {
    if (value.value != '') {
        let response = await axios.post('/api/messages/', {
            chat_id: props.chat.id,
            message: value.value,
            creator: props.creator
        });
        console.log(response.data);
    }
}
const Messages = ref(props.messages);
import Chats from "./Chats.vue";
import InputText from 'primevue/inputtext';
import Button from "primevue/button";
const chatId = props.chat.id;
echo.channel(`chats.${chatId}`).listen('MessageSend', (e) => {
    // messages.value.push(JSON.parse(e.message));
    Messages.value.push(e.message);
    console.log(e);
    value.value = '';
});
const chatContainer = ref(null);
const scrollToBottom = () => {
    if (chatContainer.value) {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
};
onMounted(() => {
    scrollToBottom();
})
</script>
<template>
    <Chats :chats="props.chats">
        <div ref="chatContainer" class="h-full p-5 w-full flex gap-1 flex-col overflow-y-scroll  items-stretch ">
            <div class="chat place w-full  flex" v-for="message in Messages" :key="message.id"
                :class="(message.creator == 1 && props.chat.creator_id == user.id) || (message.creator == 0 && props.chat.target_id == user.id) ? 'justify-end' : 'justify-start'">
                <div class="flex cursor-pointer">
                    <div class="flex max-w-96 rounded-lg p-3 gap-3 border-2"
                        :class="(message.creator == 1 && props.chat.creator_id == user.id) || (message.creator == 0 && props.chat.target_id == user.id) ? 'bg-gray-200' : 'bg-white'">
                        <p class="text-gray-700">{{ message.message }}</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="flex  w-full justify-start gap-4 py-2 px-3 h-14 rounded-xl">
            <InputText type="text" v-model="value" class="w-11/12" />
            <Button label="Send" @click="SendMessage" outlined severity="contrast" class="w-1/12" />
        </div>
    </Chats>
    
</template>

