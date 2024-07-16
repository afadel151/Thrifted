<script setup>
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import Footer from "@/Components/Footer.vue";
defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
  <div class=" h-screen  ">
    <header
      class="flex  justify-between sm:px-64 items-center backdrop-blur-sm bg-white/40 border-b p-4"
    >
      <div class="text-2xl text-gray-800 font-bold">
        <span class="text-black">ThriftedBooks</span>
        <span class="text-purple-600">Dz</span>
      </div>
      <nav class="space-x-4">
        <a href="#" class=" sm:block hidden text-gray-600">Overview</a>
        <a href="#" class="sm:block hidden text-gray-600">Supporters</a>
        <a href="#" class="sm:block hidden text-gray-600">About</a>
        <Button label="Browse now" severity="help" />
      </nav>
    </header>
    <div class="w-full bg-  flex-col-reverse sm:flex-row  flex sm:px-[20%] gap-4 py-40">
      <div
        v-motion
        :initial="{ opacity: 0, x: -100 }"
        :enter="{ opacity: 1, x: 0, scale: 1 }"
        :variants="{ custom: { scale: 2 } }"
        :hovered="{ scale: 1 }"
        :delay="100"
        :duration="300"
        class="sm:w-1/2 w-full flex flex-col items-start justify-start "
      >
        <p class="text-7xl font-black">Sell & buy books in Algeria</p>
        <p class="text-gray-600 text-xl mt-10 mb-16">
          Connecting book lovers across Algeria. Discover a wide range of books,
          sell your used ones, and find your next great read with us.
        </p>
        <nav v-if="canLogin" class="flex flex-1 mt-20 justify-end">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
          >
            <Button label="Dashboard" icon="pi pi-arrow-right" size="large" severity="help" />
          </Link>

          <template v-else>
            <!-- <Link
            :href="route('login')"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
          >
            <Button label="Login" size="large" severity="secondary"/>
          </Link> -->

            <Link
              v-if="canRegister"
              :href="route('register')"
              class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
              <Button
                label="Get Started "
                icon="pi pi-arrow-right"
                size="large"
                severity="help"
              />
            </Link>
          </template>
        </nav>
      </div>
      <div class="sm:w-1/2  w-full ">
        <img
          src="/booksimage.jpg"
          v-motion
          :initial="{ opacity: 0, x: 100 }"
          :enter="{ opacity: 1, x: 0, scale: 1 }"
          :variants="{ custom: { scale: 2 } }"
          :hovered="{ scale: 1 }"
          :delay="100"
          :duration="300"
          class="rounded-xl"
          alt=""
        />
      </div>
    </div>
    <div class="sm:px-52 snap-center mt-20">
      <p class="text-center text-6xl">Features</p>
     
      <div class="grid  snap-center sm:grid-cols-2 sm:grid-rows-2  mt-24 px-10 sm:px-20 gap-16">
        <div  v-motion
        :initial="{ opacity: 0, x: -100 }"
        :visible="{ opacity: 1, x: 0, scale: 1 }"
        :variants="{ custom: { scale: 2 } }"
        :hovered="{ scale: 1 }"
        :delay="100"
        :duration="300"
         class="border-2 bg-white hover:duration-200 hover:border-gray-300 rounded-2xl pt-5 pl-5">
          <p class="text-xl font-bold">Real Time Chat</p>
          <div class="pt-5">
            <p class="text-base">Lorem ipsum dolor sit amet consectetur adipisicing elit. 
              Consequatur necessitatibus consequuntur aliquam minus perferendis voluptatibus aperiam,</p>
            <div class="pt-5 pl-10">
              <img class="rounded-b-none  border-t border-l  rounded-l-xl" src="/imgs/realtimechats.png" alt="">
            </div>
          </div>
        </div>
        <div v-motion
          :initial="{ opacity: 0, x: 100 }"
          :visible="{ opacity: 1, x: 0, scale: 1 }"
          :variants="{ custom: { scale: 2 } }"
          :hovered="{ scale: 1 }"
          :delay="100"
          :duration="300"
           class="border-2 bg-white hover:duration-200 hover:border-gray-300 rounded-2xl pt-5 pl-5">
          <p class="text-xl font-bold">Home</p>
          <div class="pt-5">
            <p class="text-base">Lorem ipsum dolor sit amet consectetur adipisicing elit. 
              Consequatur necessitatibus consequuntur aliquam minus perferendis voluptatibus aperiam,</p>
            <div class="pt-5 pl-10">
              <img class="rounded-b-none  border-t border-l  rounded-l-xl" src="/imgs/home.png" alt="">
            </div>
          </div>
        </div>
        <div v-motion
        :initial="{ opacity: 0, x: -100 }"
        :visible="{ opacity: 1, x: 0, scale: 1 }"
        :variants="{ custom: { scale: 2 } }"
        :hovered="{ scale: 1 }"
        :delay="100"
        :duration="300"
         class="border-2 bg-white hover:duration-200 hover:border-gray-300 rounded-2xl pt-5 pl-5">
          <p class="text-xl font-bold">AI search</p>
          <div class="pt-5">
            <p class="text-base">Lorem ipsum dolor sit amet consectetur adipisicing elit. 
              Consequatur necessitatibus consequuntur aliquam minus perferendis voluptatibus aperiam,</p>
            <div class="pt-5 pl-10">
              <img class="rounded-b-none  border-t border-l  rounded-l-xl" src="/imgs/autocomplete-suggestion.png" alt="">
            </div>
          </div>
        </div>
        <div v-motion
          :initial="{ opacity: 0, x: 100 }"
          :visible="{ opacity: 1, x: 0, scale: 1 }"
          :variants="{ custom: { scale: 2 } }"
          :hovered="{ scale: 1 }"
          :delay="100"
          :duration="300"
           class="border-2 bg-white hover:duration-200 hover:border-gray-300 rounded-2xl pt-5 pl-5">
          <p class="text-xl font-bold">Request your book</p>
          <div class="pt-5">
            <p class="text-base">Lorem ipsum dolor sit amet consectetur adipisicing elit. 
              Consequatur necessitatibus consequuntur aliquam minus perferendis voluptatibus aperiam,</p>
            <div class="pt-5 pl-10">
              <img class="rounded-b-none  border-t border-l  rounded-l-xl" src="/imgs/autocomplete-suggestion.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
    <Footer class="mt-20" />
    <!-- <main
      class="flex z-10 flex-col items-center justify-center text-center mt-36"
    >

    </main> -->

    <div
      class="blob w-[1000px] h-[1000px] rounded-[999px] fixed bottom-0 left-0 -z-10 blur-3xl bg-opacity-60 bg-gradient-to-r from-red-200 via-gray-100 to-blue-100"
    ></div>
    <div
      class="blob w-[600px] h-[600px] rounded-[999px] fixed bottom-0 left-0 -z-10 blur-3xl bg-opacity-60 bg-gradient-to-r from-slate-100 via-teal-100 to-blue-100"
    ></div>
  </div>

 
</template>
<style>
@import url("https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap");
</style>
