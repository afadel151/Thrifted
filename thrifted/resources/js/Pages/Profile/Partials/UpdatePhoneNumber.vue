<script setup>
import TextInput from '@/Components/TextInput.vue';
import {  useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
const user = usePage().props.auth.user;

const form = useForm({
    user_id: user.id,
    phone: ''
});
</script>
<template>

<section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Phone number</h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your account's Phone number.
            </p>
        </header>
        <form @submit.prevent="form.post(route('profile.send_verification_code'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Phone" />

                <TextInput  type="text" class="mt-1 block w-full" v-model="form.phone" required autofocus />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
</section>
</template>