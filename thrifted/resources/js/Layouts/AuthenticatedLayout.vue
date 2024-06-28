<script setup>
import { onBeforeMount, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import InputGroup from 'primevue/inputgroup';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import axios from 'axios';
const showingNavigationDropdown = ref(false);
const SearchInput = ref('');
const UnseenMessages = ref('');
import { Inertia } from '@inertiajs/inertia';
onBeforeMount(()=>{
    axios.post('/api/users/unseen_messages')
        .then(response => {
            UnseenMessages.value = response.data;
        })
        .catch(error => {
            console.log(error);
        });
});

import Pusher from 'pusher-js';
window.Pusher = Pusher;
import Echo from 'laravel-echo';
import { usePage } from '@inertiajs/vue3';
const echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 9000,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 9000,
    forceTLS: false,
    enabledTransports: ["ws"],
});
const userId = usePage().props.auth.user.id;
echo.channel(`user.messages.${userId}`).listen('MessageNotification', (e) => {
    if (! ('Notification' in window)) {
              alert('Web Notification is not supported');
              return;
            }

            Notification.requestPermission( permission => {
              let notification = new Notification('ThriftedBooksDz', {
                body: e.sender.name + ':' + e.message.message,
                // icon: "https://pusher.com/static_logos/320x320.png" // optional image url
              });

              // link to page on clicking the notification
              notification.onclick = () => {
                    Inertia.visit('/chats/' + e.message.chat_id);
              };
            });
});
</script>

<template>
    <div>
        <div class="h-screen font-[Mulish]">
            <nav class="border-gray-100 bg-white border-b h-[64px]">
                <!-- Primary Navigation Menu -->
                <div class="px-10">
                    <div class="flex justify-between items-stretch h-16">
                        <div class="flex">

                            <div class="sm:flex space-x-8 hidden sm:-my-px sm:ms-10">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    <Button label="Home" icon="pi pi-home " text plain />
                                    <!-- pi-home -->
                                </NavLink>
                                <NavLink :href="route('books.my_books')" :active="route().current('books.my_books')">
                                    <Button label="My Books" icon="pi pi-book" text plain />
                                </NavLink>
                                <NavLink :href="route('chats.index')"
                                    :active="route().current('chats.index') || route().current('chats.show')">
                                    <Button label="Chats"  :badge="UnseenMessages + ''" badgeSeverity="contrast" icon="pi pi-comments" plain text />
                                </NavLink>
                                <Button label="Card" icon="pi pi-shopping-cart" plain text />
                            </div>
                        </div>
                        <div class="flex justify-start items-center">
                            <div >
                                <InputGroup>
                                    <Button icon="pi pi-search" size="small" severity="contrast" raised />
                                    <InputText v-model="SearchInput" placeholder="Search" />
                                </InputGroup>
                            </div>
                            <div class="sm:flex sm:items-center hidden">
                                <!-- Settings Dropdown -->
                                <div class="relative">

                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">

                                                <button type="button"
                                                    class="inline-flex items-center bg-white px-3 py-2 border border-transparent rounded-md font-medium text-gray-500 text-sm hover:text-gray-700 leading-4 focus:outline-none transition duration-150 ease-in-out">
                                                    <img :src="$page.props.auth.user.socials[0] ? $page.props.auth.user.socials[0].provider_avatar : ''"
                                                        width="30" class="mr-1 rounded-full" alt="">
                                                    {{ $page.props.auth.user.name }}

                                                    <svg class="w-4 h-4 -me-0.5 ms-2" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink :href="route('profile.show',{id:userId})"> View Profile </DropdownLink>
                                            <DropdownLink :href="route('profile.edit')"> Edit Profile </DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button">
                                                Log Out
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>


                        <!-- Hamburger -->
                        <div class="flex items-center sm:hidden -me-2">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex justify-center items-center hover:bg-gray-100 focus:bg-gray-100 p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="space-y-1 pt-2 pb-3">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-gray-200 pt-4 pb-1 border-t">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-gray-500 text-sm">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="space-y-1 mt-3">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <!-- <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 max-w-7xl">
                    <slot name="header" />
                </div>
            </header> -->

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<style>
@import url('https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap');
</style>
