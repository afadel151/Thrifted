<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
const props = defineProps({
    phone: {
        type: String,
        required: true,
    }
})
const user = usePage().props.auth.user;
const form = useForm({
   code:null,
   phone:props.phone,
   user_id : user.id,
});
</script>

<template>
    <form @submit.prevent="form.post(route('profile.verify_code'))">
        <label for="">code</label>
        <input type="text" v-model="form.code">
    </form>
</template>