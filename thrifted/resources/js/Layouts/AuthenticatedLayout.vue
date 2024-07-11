<script setup>
import { onBeforeMount, ref } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import InputGroup from "primevue/inputgroup";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import ProgressSpinner from "primevue/progressspinner";
import axios from "axios";
import Dialog from "primevue/dialog";
const showingNavigationDropdown = ref(false);
const SearchInput = ref("");
const Searching = ref(false);
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
const SearchResults = ref([]);
import { watch } from "vue";
function Search(search) {
  Searching.value = true;
  console.log(SearchInput.value);
  axios
    .post("/api/books/search", {
      search: search,
    })
    .then((response) => {
      SearchResults.value = response.data;
      Searching.value = false;
      console.log(response.data);
    })
    .catch((error) => console.log(error));
}
watch(SearchInput, async (NewSearch, OldSearch) => {
  if (NewSearch.length != 0) {
    Search(NewSearch);
  } else {
    SearchResults.value = [];
  }
});
const visible = ref(false);

const position = ref("center");
const openPosition = (pos) => {
  position.value = pos;
  visible.value = true;
};
const clearResults = () => {
  SearchResults.value = [];
};
</script>

<template>
  <div>
    <div class="h-screen ">
      <nav class="border-gray-100 bg-white border-b h-[64px]">
        <!-- Primary Navigation Menu -->
        <div class="px-10">
          <div class="flex justify-between items-stretch h-16">
            <div class="flex">
              <div class="sm:flex space-x-8 hidden sm:-my-px sm:ms-10">
                <NavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  <Button label="Home" icon="pi pi-home " text plain />
                  <!-- pi-home -->
                </NavLink>
                <NavLink
                  :href="route('books.my_books')"
                  :active="route().current('books.my_books')"
                >
                  <Button label="My Books" icon="pi pi-book" text plain />
                </NavLink>
                <NavLink
                  :href="route('chats.index')"
                  :active="
                    route().current('chats.index') ||
                    route().current('chats.show')
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

                <NavLink
                  :href="route('cards.index')"
                  :active="route().current('cards.index')"
                >
                  <Button label="Card" icon="pi pi-shopping-cart" plain text />
                </NavLink>

                <NavLink
                  :href="route('cards.index')"
                  :active="route().current('cards.index')"
                >
                  <Button label="Likes" icon="pi pi-heart" plain text />
                </NavLink>
                <!--  -->
              </div>
            </div>
            <div class="flex justify-start items-center">
              <div class="">
                <IconField @click="openPosition('top')">
                  <InputIcon class="pi pi-search" />
                  <InputText v-model="SearchInput" placeholder="Search" />
                </IconField>

                <Dialog
                  v-model:visible="visible"
                  header="Search"
                  :style="{ width: '50rem' }"
                  :position="position"
                  :modal="true"
                  :draggable="false"
                >
                  <div class="flex items-center gap-1 mb-4 px-6">
                    <IconField class="flex-auto w-full">
                      <InputIcon class="pi pi-search flex-auto" />
                      <InputText
                        v-model="SearchInput"
                        placeholder="Search"
                        class="flex-auto w-full"
                      />
                    </IconField>
                    <Button
                      icon="pi pi-search"
                      label="Search"
                      class="mx-1"
                      v-show="SearchInput.length > 0"
                      severity="contrast"
                    />
                    <Button
                      icon="pi pi-times "
                      severity="contrast"
                      @click="clearResults"
                    />
                  </div>
                  <div
                    class="flex items-center flex-col justify-center gap-4 mb-8 px-6"
                  >
                    <ProgressSpinner
                      v-show="Searching"
                      style="width: 50px; height: 50px"
                    />
                    <div
                      v-for="book in SearchResults"
                      :key="book.id"
                      class="w-full font-bold flex items-center justify-start rounded-lg border"
                    >
                      <img
                        :src="book.cover.replace('public/', '/storage/')"
                        class="w-24"
                        alt=""
                      />
                      <div>
                        <p class="text-4xl ml-2">
                          {{ book.title }}
                        </p>
                        <p class="text-2xl ml-2">
                          {{ book.author }}
                        </p>
                        <p class="text-xl ml-2">
                          {{ book.edition }}
                        </p>
                        <p
                          class="ml-2 text-green-400"
                          v-if="book.available == 1"
                        >
                          Available
                        </p>
                        <p v-else class="ml-2 text-red-500">Sold</p>
                      </div>
                    </div>
                  </div>
                  <div class="flex justify-end gap-2">
                    Search by
                    <svg
                      width="77"
                      height="19"
                      aria-label="Algolia"
                      role="img"
                      id="Layer_1"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 2196.2 500"
                    >
                      <path
                        class="cls-2"
                        d="M1070.38,275.3V5.91c0-3.63-3.24-6.39-6.82-5.83l-50.46,7.94c-2.87,.45-4.99,2.93-4.99,5.84l.17,273.22c0,12.92,0,92.7,95.97,95.49,3.33,.1,6.09-2.58,6.09-5.91v-40.78c0-2.96-2.19-5.51-5.12-5.84-34.85-4.01-34.85-47.57-34.85-54.72Z"
                      ></path>
                      <rect
                        class="cls-1"
                        x="1845.88"
                        y="104.73"
                        width="62.58"
                        height="277.9"
                        rx="5.9"
                        ry="5.9"
                      ></rect>
                      <path
                        class="cls-2"
                        d="M1851.78,71.38h50.77c3.26,0,5.9-2.64,5.9-5.9V5.9c0-3.62-3.24-6.39-6.82-5.83l-50.77,7.95c-2.87,.45-4.99,2.92-4.99,5.83v51.62c0,3.26,2.64,5.9,5.9,5.9Z"
                      ></path>
                      <path
                        class="cls-2"
                        d="M1764.03,275.3V5.91c0-3.63-3.24-6.39-6.82-5.83l-50.46,7.94c-2.87,.45-4.99,2.93-4.99,5.84l.17,273.22c0,12.92,0,92.7,95.97,95.49,3.33,.1,6.09-2.58,6.09-5.91v-40.78c0-2.96-2.19-5.51-5.12-5.84-34.85-4.01-34.85-47.57-34.85-54.72Z"
                      ></path>
                      <path
                        class="cls-2"
                        d="M1631.95,142.72c-11.14-12.25-24.83-21.65-40.78-28.31-15.92-6.53-33.26-9.85-52.07-9.85-18.78,0-36.15,3.17-51.92,9.85-15.59,6.66-29.29,16.05-40.76,28.31-11.47,12.23-20.38,26.87-26.76,44.03-6.38,17.17-9.24,37.37-9.24,58.36,0,20.99,3.19,36.87,9.55,54.21,6.38,17.32,15.14,32.11,26.45,44.36,11.29,12.23,24.83,21.62,40.6,28.46,15.77,6.83,40.12,10.33,52.4,10.48,12.25,0,36.78-3.82,52.7-10.48,15.92-6.68,29.46-16.23,40.78-28.46,11.29-12.25,20.05-27.04,26.25-44.36,6.22-17.34,9.24-33.22,9.24-54.21,0-20.99-3.34-41.19-10.03-58.36-6.38-17.17-15.14-31.8-26.43-44.03Zm-44.43,163.75c-11.47,15.75-27.56,23.7-48.09,23.7-20.55,0-36.63-7.8-48.1-23.7-11.47-15.75-17.21-34.01-17.21-61.2,0-26.89,5.59-49.14,17.06-64.87,11.45-15.75,27.54-23.52,48.07-23.52,20.55,0,36.63,7.78,48.09,23.52,11.47,15.57,17.36,37.98,17.36,64.87,0,27.19-5.72,45.3-17.19,61.2Z"
                      ></path>
                      <path
                        class="cls-2"
                        d="M894.42,104.73h-49.33c-48.36,0-90.91,25.48-115.75,64.1-14.52,22.58-22.99,49.63-22.99,78.73,0,44.89,20.13,84.92,51.59,111.1,2.93,2.6,6.05,4.98,9.31,7.14,12.86,8.49,28.11,13.47,44.52,13.47,1.23,0,2.46-.03,3.68-.09,.36-.02,.71-.05,1.07-.07,.87-.05,1.75-.11,2.62-.2,.34-.03,.68-.08,1.02-.12,.91-.1,1.82-.21,2.73-.34,.21-.03,.42-.07,.63-.1,32.89-5.07,61.56-30.82,70.9-62.81v57.83c0,3.26,2.64,5.9,5.9,5.9h50.42c3.26,0,5.9-2.64,5.9-5.9V110.63c0-3.26-2.64-5.9-5.9-5.9h-56.32Zm0,206.92c-12.2,10.16-27.97,13.98-44.84,15.12-.16,.01-.33,.03-.49,.04-1.12,.07-2.24,.1-3.36,.1-42.24,0-77.12-35.89-77.12-79.37,0-10.25,1.96-20.01,5.42-28.98,11.22-29.12,38.77-49.74,71.06-49.74h49.33v142.83Z"
                      ></path>
                      <path
                        class="cls-2"
                        d="M2133.97,104.73h-49.33c-48.36,0-90.91,25.48-115.75,64.1-14.52,22.58-22.99,49.63-22.99,78.73,0,44.89,20.13,84.92,51.59,111.1,2.93,2.6,6.05,4.98,9.31,7.14,12.86,8.49,28.11,13.47,44.52,13.47,1.23,0,2.46-.03,3.68-.09,.36-.02,.71-.05,1.07-.07,.87-.05,1.75-.11,2.62-.2,.34-.03,.68-.08,1.02-.12,.91-.1,1.82-.21,2.73-.34,.21-.03,.42-.07,.63-.1,32.89-5.07,61.56-30.82,70.9-62.81v57.83c0,3.26,2.64,5.9,5.9,5.9h50.42c3.26,0,5.9-2.64,5.9-5.9V110.63c0-3.26-2.64-5.9-5.9-5.9h-56.32Zm0,206.92c-12.2,10.16-27.97,13.98-44.84,15.12-.16,.01-.33,.03-.49,.04-1.12,.07-2.24,.1-3.36,.1-42.24,0-77.12-35.89-77.12-79.37,0-10.25,1.96-20.01,5.42-28.98,11.22-29.12,38.77-49.74,71.06-49.74h49.33v142.83Z"
                      ></path>
                      <path
                        class="cls-2"
                        d="M1314.05,104.73h-49.33c-48.36,0-90.91,25.48-115.75,64.1-11.79,18.34-19.6,39.64-22.11,62.59-.58,5.3-.88,10.68-.88,16.14s.31,11.15,.93,16.59c4.28,38.09,23.14,71.61,50.66,94.52,2.93,2.6,6.05,4.98,9.31,7.14,12.86,8.49,28.11,13.47,44.52,13.47h0c17.99,0,34.61-5.93,48.16-15.97,16.29-11.58,28.88-28.54,34.48-47.75v50.26h-.11v11.08c0,21.84-5.71,38.27-17.34,49.36-11.61,11.08-31.04,16.63-58.25,16.63-11.12,0-28.79-.59-46.6-2.41-2.83-.29-5.46,1.5-6.27,4.22l-12.78,43.11c-1.02,3.46,1.27,7.02,4.83,7.53,21.52,3.08,42.52,4.68,54.65,4.68,48.91,0,85.16-10.75,108.89-32.21,21.48-19.41,33.15-48.89,35.2-88.52V110.63c0-3.26-2.64-5.9-5.9-5.9h-56.32Zm0,64.1s.65,139.13,0,143.36c-12.08,9.77-27.11,13.59-43.49,14.7-.16,.01-.33,.03-.49,.04-1.12,.07-2.24,.1-3.36,.1-1.32,0-2.63-.03-3.94-.1-40.41-2.11-74.52-37.26-74.52-79.38,0-10.25,1.96-20.01,5.42-28.98,11.22-29.12,38.77-49.74,71.06-49.74h49.33Z"
                      ></path>
                      <path
                        class="cls-1"
                        d="M249.83,0C113.3,0,2,110.09,.03,246.16c-2,138.19,110.12,252.7,248.33,253.5,42.68,.25,83.79-10.19,120.3-30.03,3.56-1.93,4.11-6.83,1.08-9.51l-23.38-20.72c-4.75-4.21-11.51-5.4-17.36-2.92-25.48,10.84-53.17,16.38-81.71,16.03-111.68-1.37-201.91-94.29-200.13-205.96,1.76-110.26,92-199.41,202.67-199.41h202.69V407.41l-115-102.18c-3.72-3.31-9.42-2.66-12.42,1.31-18.46,24.44-48.53,39.64-81.93,37.34-46.33-3.2-83.87-40.5-87.34-86.81-4.15-55.24,39.63-101.52,94-101.52,49.18,0,89.68,37.85,93.91,85.95,.38,4.28,2.31,8.27,5.52,11.12l29.95,26.55c3.4,3.01,8.79,1.17,9.63-3.3,2.16-11.55,2.92-23.58,2.07-35.92-4.82-70.34-61.8-126.93-132.17-131.26-80.68-4.97-148.13,58.14-150.27,137.25-2.09,77.1,61.08,143.56,138.19,145.26,32.19,.71,62.03-9.41,86.14-26.95l150.26,133.2c6.44,5.71,16.61,1.14,16.61-7.47V9.48C499.66,4.25,495.42,0,490.18,0H249.83Z"
                      ></path>
                    </svg>
                  </div>
                </Dialog>
              </div>
              <div class="sm:flex sm:items-center hidden">
                <!-- Settings Dropdown -->
                <div class="relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex  items-center bg-white px-3 py-2 border border-transparent rounded-md font-medium text-gray-500 text-sm hover:text-gray-700 leading-4 focus:outline-none transition duration-150 ease-in-out"
                        >
                          <div
                            v-if="$page.props.auth.user.picture"
                            class="w-[30px] mr-2 h-[30px] rounded-full flex bg-cover bg-center"
                            :style="{
                              backgroundImage: `url('${$page.props.auth.user.picture.replace(
                                'public/',
                                '/storage/'
                              )}')`,
                            }"
                          ></div>
                          <img
                            v-else
                            :src="
                              $page.props.auth.user.socials[0]
                                ? $page.props.auth.user.socials[0]
                                    .provider_avatar
                                : ''
                            "
                            width="30"
                            class="mr-2 rounded-full"
                            alt=""
                          />
                          {{ $page.props.auth.user.name }}

                          <svg
                            class="w-4 h-4 -me-0.5 ms-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
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

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden -me-2">
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
