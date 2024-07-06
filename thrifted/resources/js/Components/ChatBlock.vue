<script  setup>
import { onBeforeMount, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import NavLink from './NavLink.vue';
import Button from 'primevue/button';
import Badge from 'primevue/badge';
import{ Link} from '@inertiajs/vue3';
const props = defineProps({
    chat: {
        type: Object,
        required: true
    },
    opened:{
        type: Boolean,
        required:false,
        default: false
    }
});
const user = usePage().props.auth.user;
const LastMessage = ref(null);
const UnseenMessagesCount = ref(0);
const GetSize = (A) => {
    return A.length;
}
// const { state, ready } = useAsyncState(async () => {
//   const { data } = await axios.get('https://api.github.com/')
//   return { data }
// })
onBeforeMount(()=>{
        axios.post('/api/chats/get_last_message', {
            chat_id: props.chat.id
        }).then((response) => {
            LastMessage.value = response.data;
        }).catch((error)=>{
            console.log(error);
        });
        axios.post('/api/chats/get_unseen_messages', {
            chat_id: props.chat.id
        }).then((response) => {
            UnseenMessagesCount.value = response.data;
        }).catch((error)=>{
            console.log(error);
        });

});
const profileImage = props.chat.target_id == user.id ? props.chat.creator.picture == null ? '/default-avatar.jpg' : props.chat.creator.picture.replace('public/','/storage/') : props.chat.target.picture == null ? 'default-avatar.jpg' : props.chat.target.picture.replace('public/' , '/storage/') ;
</script>
<template>
    <Link :href="route('chats.show', { id: chat.id })" class="flex  p-2  items-center cursor-pointer bg-gray-100 hover:bg-gray-200 rounded-xl ">
        <div
            class="w-[80px] h-[80px] rounded-full bg-cover bg-center"
            :style="{
              backgroundImage: `url(${profileImage})`,
            }"
          ></div> 
        <div class=" flex flex-col items-stretch  justify-between "
            >
            <div class="w-full flex justify-between px-2 items-center">
                <p class="text-xl m-2"> {{ props.chat.target_id == user.id ? props.chat.creator.name : props.chat.target.name }}</p>
                   
                <Badge :value="UnseenMessagesCount"  v-if="UnseenMessagesCount > 0  " v-show="!props.opened"

                    severity="contrast"></Badge>
            </div>
                {{ LastMessage?.creator == 0 && props.chat.target_id == user.id ? 'Vous: ' + (LastMessage?.book_id == 0 ? LastMessage?.message : LastMessage?.book.title) : '' }} 
                {{ LastMessage?.creator == 1 && props.chat.creator_id == user.id ? 'Vous: ' + (LastMessage?.book_id == 0 ? LastMessage?.message : LastMessage?.book.title) : '' }} 
                {{ LastMessage?.creator == 0 && props.chat.creator_id == user.id ? props.chat.target.name + ' : ' + (LastMessage?.book_id == 0 ? LastMessage?.message : LastMessage?.book.title) : '' }} 
                {{ LastMessage?.creator == 1 && props.chat.target_id == user.id ? props.chat.target.name + ' : ' + (LastMessage?.book_id == 0 ? LastMessage?.message : LastMessage?.book.title) : '' }} 
        </div>

    </Link>
</template>