<script setup>
import { onBeforeMount, ref } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import BookSearch from "@/Components/BookSearch.vue";
import Button from "primevue/button";
import axios from "axios";
import Divider from "primevue/divider";
const showingNavigationDropdown = ref(false);
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
const UnseenMessages = ref("");
import { Inertia } from "@inertiajs/inertia";
onBeforeMount(() => {
  axios
    .post("/api/users/unseen_messages")
    .then((response) => {
      UnseenMessages.value = response.data;
    })
    .catch((error) => {
      console.log(error);
    });
});
import Pusher from "pusher-js";
window.Pusher = Pusher;
import Echo from "laravel-echo";
import { usePage } from "@inertiajs/vue3";
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
echo.channel(`user.messages.${userId}`).listen("MessageNotification", (e) => {
  if (!("Notification" in window)) {
    alert("Web Notification is not supported");
    return;
  }

  Notification.requestPermission((permission) => {
    let notification = new Notification("ThriftedBooksDz", {
      body: e.sender.name + ":" + e.message.message,
      // icon: "https://pusher.com/static_logos/320x320.png" // optional image url
    });

    notification.onclick = () => {
      Inertia.visit("/chats/" + e.message.chat_id);
    };
  });
});
</script>

<template>
  <div>
    <div class="h-screen font-[Mulish]">
      <nav
        class="border-gray-100 bg-white mt-4 mb-4"
      >
        <div class="px-10 ">
          <div class="flex  justify-center items-center gap-10 h-16">
            <ApplicationLogo class="h-16" />
            <BookSearch />

            <div class="sm:flex hidden">
              <div class="sm:flex hidden sm:-my-px">
                <NavLink
                  :href="route('cards.index')"
                  :active="route().current('cards.index')"
                >
                  <Button label="Card" icon="pi pi-shopping-cart" plain text />
                </NavLink>
                <Divider layout="vertical" />
                <NavLink
                  :href="route('cards.index')"
                  :active="route().current('cards.index')"
                >
                  <Button label="Wishlist" icon="pi pi-heart" plain text />
                </NavLink>
                <!--  -->
                <Divider layout="vertical" />
              </div>
              <div class="flex justify-start items-center">
                <div class="sm:flex sm:items-center hidden">
                  <!-- Settings Dropdown -->
                  <div class="relative">
                    <Dropdown align="right" width="48">
                      <template #trigger>
                        <span class="inline-flex rounded-md">
                          <button
                            type="button"
                            class="inline-flex items-center justify-center bg-white font-medium text-gray-500 text-sm hover:text-gray-700 leading-4 focus:outline-none transition duration-150 ease-in-out"
                          >
                            <i class="pi pi-user"></i>
                            <!-- {{ $page.props.auth.user.name }} -->
                            <p class="ml-2 text-lg">Account</p>
                          </button>
                        </span>
                      </template>

                      <template #content>
                        <DropdownLink
                          :href="route('profile.show', { id: userId })"
                        >
                          View Profile
                        </DropdownLink>
                        <DropdownLink :href="route('profile.edit')">
                          Edit Profile
                        </DropdownLink>
                        <DropdownLink
                          :href="route('logout')"
                          method="post"
                          as="button"
                        >
                          Log Out
                        </DropdownLink>
                      </template>
                    </Dropdown>
                  </div>
                </div>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex justify-center items-center hover:bg-gray-100 focus:bg-gray-100 p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out"
              >
                <svg
                  class="w-6 h-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
          <Divider />
        </div>

        <div class="w-full sm:flex hidden   justify-center mt-2">
          <NavLink
            :href="route('dashboard')"
            :active="route().current('dashboard')"
          >
            <Button label="Home" icon="pi pi-home " text plain />
          </NavLink>
          <Divider layout="vertical" />
          <NavLink
            :href="route('books.my_books')"
            :active="route().current('books.my_books')"
          >
            <Button label="My Books" icon="pi pi-book" text plain />
          </NavLink>
          <Divider layout="vertical" />
          <NavLink
            :href="route('chats.index')"
            :active="
              route().current('chats.index') || route().current('chats.show')
            "
          >
            <Button
              label="Chats"
              :badge="UnseenMessages + ''"
              badgeSeverity="contrast"
              icon="pi pi-comments"
              plain
              text
            />
          </NavLink>
          <Divider layout="vertical" />
          <NavLink>
            <Button
              label="About"
              badgeSeverity="contrast"
              icon="pi pi-info"
              plain
              text
            />
          </NavLink>
          <NavLink>
            <Button
              label="Requests"
              badge="14"
              badgeSeverity="danger"
              icon="pi pi-question"
              plain
              text
            />
          </NavLink>
        </div>
        <!-- Responsive Navigation Menu -->
      </nav>
      <!-- Page Heading -->
      <!-- <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 max-w-7xl">
                    <slot name="header" />
                </div>
            </header> -->

      <!-- Page Content -->
      <main>
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden z-10"
        >
          <div class="space-y-1 pt-2 pb-3">
            <ResponsiveNavLink
              :href="route('dashboard')"
              :active="route().current('dashboard')"
            >
              Dashboard
            </ResponsiveNavLink>
            <ResponsiveNavLink
              :href="route('books.my_books')"
              :active="route().current('books.my_books')"
            >
              My Books
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="border-gray-200 pt-4 pb-1 border-t">
            <div class="px-4">
              <div class="font-medium text-base text-gray-800">
                {{ $page.props.auth.user.name }}
              </div>
              <div class="font-medium text-gray-500 text-sm">
                {{ $page.props.auth.user.email }}
              </div>
            </div>

            <div class="space-y-1 mt-3">
              <ResponsiveNavLink :href="route('profile.edit')">
                Edit Profile
              </ResponsiveNavLink>
              <ResponsiveNavLink
                :href="route('profile.show', { id: $page.props.auth.user.id })"
              >
                Show Profile
              </ResponsiveNavLink>
              <ResponsiveNavLink
                :href="route('logout')"
                method="post"
                as="button"
              >
                Log Out
              </ResponsiveNavLink>
            </div>
          </div>
        </div>

        <slot />
      </main>
    </div>
  </div>
</template>
<style>
@import url("https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap");
</style>









               