<script setup>

const props = defineProps({
    messages:{
        type: Array,
        required:true
    }
});
import Chats from "./Chats.vue";
</script>
<template>
    <Chats>{{ props.messages }}</Chats>
</template>