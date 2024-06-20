<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import NavLink from '@/Components/NavLink.vue';
import Button from 'primevue/button';
const user = usePage().props.auth.user;
const props = defineProps({
    chats: {
        type: Array,
        required: true
    }
})
</script>
<template>
    <AuthenticatedLayout>


        <div class="h-full pb-20 bg-slate-100">

            <div class=" sm:px-6 h-full w-full">
                <div class="bg-white h-full shadow-sm  p-5 sm:rounded-lg overflow-hidden">
                    <div class="sm:flex   space-x-0 ">
                        <NavLink :href="route('chats.selling')" :active="route().current('chats.selling')">
                            <Button label="Sells" icon="pi pi-shop " text plain />
                            <!-- pi-home -->
                        </NavLink>
                        <NavLink :href="route('chats.buying')" :active="route().current('chats.buying')">
                            <Button label="Purchases" icon="pi pi-shopping-bag" text plain />
                        </NavLink>

                    </div>
                    <div class="flex h-full  w-screen border-2 overflow-hidden">
                        <div class="w-1/6 bg-white border-r h-full border-gray-300">
                            <div class="overflow-y-auto h-full p-3 mb-9 pb-20" v-for="chat in props.chats">
                                <div class="flex items-center mb-4 cursor-pointer hover:bg-gray-100 p-2 rounded-md">
                                    <NavLink :href="route('chats.show',{id:chat.id})" :active="route().current('chats.show',{id:chat.id})">
                                        <Button :label="chat.target.name" icon="pi pi-shop " text plain />
                                    </NavLink>
                                </div>
                            </div>
                        </div>
                        <div class="h-full w-full bg-slate-500 ">
                            <slot />
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>