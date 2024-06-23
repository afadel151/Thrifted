<script  setup>
import { onBeforeMount, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import NavLink from './NavLink.vue';
import Button from 'primevue/button';
import Badge from 'primevue/badge';

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
</script>
<template>
    <div class="flex    items-center cursor-pointer hover:bg-gray-100 rounded-md">
        <NavLink class="w-full flex flex-col items-stretch  justify-between px-2"
            :href="route('chats.show', { id: chat.id })" :active="route().current('chats.show', { id: chat.id })">
            <div class="w-full flex justify-between px-2 items-center">
                <Button :label="props.chat.target_id == user.id ? props.chat.creator.name : props.chat.target.name"
                    icon="pi pi-shop" text plain />
                   
                <Badge :value="UnseenMessagesCount"  v-if="UnseenMessagesCount > 0  " v-show="!props.opened"

                    severity="contrast"></Badge>
            </div>
                {{ LastMessage?.creator == 0 && props.chat.target_id == user.id ? 'Vous: ' + LastMessage?.message : '' }} 
                {{ LastMessage?.creator == 1 && props.chat.creator_id == user.id ? 'Vous: ' + LastMessage?.message : '' }} 
                {{ LastMessage?.creator == 0 && props.chat.creator_id == user.id ? props.chat.target.name + ' : ' + LastMessage?.message : '' }} 
                {{ LastMessage?.creator == 1 && props.chat.target_id == user.id ? props.chat.target.name + ' : ' + LastMessage?.message : '' }} 
        </NavLink>

    </div>
</template>