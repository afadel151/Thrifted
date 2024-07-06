<script setup>
import Pusher from 'pusher-js';
import { Link } from '@inertiajs/vue3';
import InfiniteLoading from "v3-infinite-loading";
window.Pusher = Pusher;
import axios from 'axios';
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
import { usePage } from '@inertiajs/vue3';
const props = defineProps({
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
const Page = ref(1);
async function SendMessage() {
    if (value.value != '') {
        let response = await axios.post('/api/messages/', {
            chat_id: props.chat.id,
            message: value.value,
            creator: props.creator
        });
        scrollToBottom();
    }
}
const Messages = ref([]);
const Seen = ref(false);
async function MarkAsSeen() {
    try {
        let response = await axios.post('/api/chats/mark_seen', {
            chat_id: props.chat.id
        });
        console.log(response.data);
        Seen.value = true;
    } catch (error) {
        console.log(error);
    }
}
const LastPage = ref(0);
async function HandleLoadMore() {
    try {

        let response = await axios.get(`/api/chats/${props.chat.id}/messages?page=${Page.value}`);
        response.data.data.forEach(message => {
            Messages.value.push(message);
        });
        console.log(response.data);
        LastPage.value = response.data.last_page;
        Page.value = Page.value + 1;
    } catch (error) {
        console.log(error);
    }
}
import ProgressSpinner from 'primevue/progressspinner';
import Chats from "./Chats.vue";
import InputText from 'primevue/inputtext';
import Button from "primevue/button";
const chatId = props.chat.id;
echo.channel(`chats.${chatId}`).listen('MessageSend', (e) => {
    Messages.value.unshift(e.message);
    value.value = '';
});
echo.channel(`chats.${chatId}`).listen('ChatSeen', (e) => {
    UnseenNotSeen.value = false;
});

const chatContainer = ref(null);
const scrollToBottom = () => {
    if (chatContainer.value) {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
};
onMounted(() => {
    MarkAsSeen();
    scrollToBottom();
});
const UnseenNotSeen = ref(true);
const profileImage = props.chat.target_id == user.id ? props.chat.creator.picture == null ? '/default-avatar.jpg' : props.chat.creator.picture.replace('public/','/storage/') : props.chat.target.picture == null ? 'default-avatar.jpg' : props.chat.target.picture.replace('public/' , '/storage/') ;

</script>
<template>
    <Chats :chats="props.chats" :opened="true">
        <div class=" h-20 p-3 flex items-center  justify-start border-2 rounded-2xl">
            <div
            class="w-[65px] h-[65px] rounded-full bg-cover bg-center"
            :style="{
              backgroundImage: `url(${profileImage})`,
            }"
          ></div> 
          <div class="flex flex-col items-start ml-5">
            <p class="text-3xl font-bold ">{{ props.chat.target_id == user.id ? props.chat.creator.name : props.chat.target.name }}</p>
            <!-- <p class="text-xl"> {{ props.chat.target_id == user.id ? props.chat.creator.email : props.chat.target.email }}</p> -->
          </div>
        </div>
        <div ref="chatContainer"
            class="h-full p-5 w-full flex gap-1 flex-col-reverse overflow-y-scroll   items-stretch ">
            <div class=" w-full   flex" v-for="message in Messages" :key="message.id"
                :class="(message.creator == 1 && props.chat.creator_id == user.id) || (message.creator == 0 && props.chat.target_id == user.id) ? 'justify-end' : 'justify-start'">
                <div :class="message.book_id != 0 ? ' border rounded-lg' : ''">
                    <div class="flex max-w-96 rounded-lg p-3 gap-3 border-2" v-if="message.book == null"
                        :class="(message.creator == 1 && props.chat.creator_id == user.id) || (message.creator == 0 && props.chat.target_id == user.id) ? 'bg-gray-200' : 'bg-white'">
                        <p class="text-gray-700">{{ message.message }}</p>

                    </div>

                    <div v-else class="m-2 p-4  h-full  w-full  ">
                        <Link :href="route('books.show', { id: message.book_id })"
                            class=" h-full flex flex-col items-center justify-between w-full">



                        <img :src="message.book.cover == null ? '/cover-not-available.jpg' : message.book.cover.replace('public/', '/storage/')"
                            :alt="message.book.title" class="rounded w-40 " />



                        <div class="font-medium text-xl">{{ message.book.title }}</div>
                        <div class=" font-medium text-sm">{{ message.book.author }}</div>
                        <div class="flex w-full justify-center items-center">
                            <p class="mt-0 font-semibold text-xl">{{ message.book.price == 0 ? 'No price yet' :
                                message.book.price }}
                            </p>
                        </div>

                        </Link>
                    </div>
                </div>
            </div>
            <div  class="w-full flex justify-center items-center" v-if="LastPage + 1 != Page || LastPage == 0" >
                <ProgressSpinner style="width: 40px; height: 40px" strokeWidth="8" fill="transparent"
                    animationDuration=".5s" aria-label="Custom ProgressSpinner" />
                <InfiniteLoading style="height: 0px; width: 0px;" @infinite="HandleLoadMore" />
            </div>

        </div>
        <div class="flex  w-full justify-start gap-4 py-2 px-3 h-14 rounded-xl">
            <InputText type="text" v-model="value" class="w-11/12" />
            <Button label="Send" @click="SendMessage" outlined severity="contrast" class="w-1/12" />
        </div>
    </Chats>

</template>
