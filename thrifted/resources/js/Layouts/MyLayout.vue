<script setup>
import { ref } from 'vue';
const emit = defineEmits(['search']);
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import InputGroup from 'primevue/inputgroup';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
const showingNavigationDropdown = ref(false);
const SearchInput = ref('');
</script>

<template>

    <div>
        <div class="h-screen flex w-screen">
            <nav class="border-gray-100 bg-white  flex flex-col  border-b">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto w-[100px]  max-w-7xl">
                    <div class="flex flex-col gap-8 items-stretch justify-between h-16">
                        <div class="flex items-center gap-4 pt-10 flex-col">
                            <!-- Logo -->
                            <!-- <div class="flex items-center shrink-0">
                                <Link :href="route('dashboard')">
                                <ApplicationLogo class="block w-auto h-9 text-gray-800 fill-current" />
                                </Link>
                            </div> -->

                            <div class="sm:flex flex-col space-y-4 hidden ">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    <Button  icon="pi pi-home " text plain/>
                                    <!-- pi-home -->
                                </NavLink>
                                <NavLink :href="route('books.my_books')" :active="route().current('books.my_books')">
                                    <Button  icon="pi pi-book" text plain/>
                                </NavLink>
                                <NavLink :href="route('chats.index')" :active="route().current('chats.index') || route().current('chats.show')">
                                    <Button  icon="pi pi-comments" plain text />
                                </NavLink>
                            </div>
                        </div>

                        <!-- <div class="px-20 self-center">
                            <InputGroup>
                                <Button icon="pi pi-search"  size="small" severity="contrast" raised  />
                                <InputText v-model="SearchInput" placeholder="Search" />
                            </InputGroup>
                        </div> -->
                        <div class="sm:flex flex-col sm:items-center hidden ">
                            <!-- Settings Dropdown -->
                            <div class="relative ">

                                <Dropdown align="left" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">

                                            <button type="button"
                                                class="inline-flex items-center bg-white px-3 py-2 border border-transparent rounded-md font-medium text-gray-500 text-sm hover:text-gray-700 leading-4 focus:outline-none transition duration-150 ease-in-out">
                                                <img :src="$page.props.auth.user.socials[0] ? $page.props.auth.user.socials[0].provider_avatar : ''"
                                                    width="30" class="mr-1 rounded-full pi pi-user" alt="" >
                                                <!-- {{ $page.props.auth.user.name }} -->

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
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
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
            <main class="h-full w-full">
                <slot />
            </main>
        </div>
    </div>
</template>
