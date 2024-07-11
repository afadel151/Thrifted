<script setup>
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
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
  <div>
    <header class="flex justify-between items-center backdrop-blur-sm bg-white/30 border-b p-4 px-14">
      <div class="text-2xl text-gray-800 font-bold">
        <span class="text-black">ThriftedBooks</span>
        <span class="text-purple-600">Dz</span>
      </div>
      <nav class="space-x-4">
        <a href="#" class="text-gray-600">Overview</a>
        <a href="#" class="text-gray-600">Supporters</a>
        <a href="#" class="text-gray-600">About</a>
        <Button label="Browse now" severity="help" />
      </nav>
    </header>
    <div class="w-full flex px-52 py-40">
      <div class="w-1/2 flex flex-col items-start justify-start p-10">
        <p class="text-7xl font-black">Sell & buy books in Algeria</p>
        <p class="text-gray-600 text-xl mt-20 mb-16">
          Connecting book lovers across Algeria. Discover a wide range of books,
          sell your used ones, and find your next great read with us.
        </p>
        <nav v-if="canLogin" class="flex flex-1 justify-end">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
          >
            <Button label="Dashboard" size="large" severity="help" />
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
      <div class="w-1/2">
        <img src="/booksimage.jpg" class="rounded-xl" alt="" />
      </div>
    </div>
    <div class="px-52 mt-20">
        <p class=" text-center text-6xl ">Features</p>
        <div class="   grid grid-cols-3 mt-20 px-20 gap-32">
            <div class="  bg-slate-400" >
                Real Time Chat Between Users
            </div>
            <div class="  bg-slate-400" >
                Request / Response Style
            </div>
            <div class="  bg-slate-400" >
                Ai powered Searching
            </div>
            
        </div>
    </div>
    <main
      class="flex z-10 flex-col items-center justify-center text-center mt-36"
    >
      <!-- <h1 class="text-4xl relative md:text-8xl font-bold text-gray-800 mb-4 w-[70%]">
            Sell & buy books in Algeria
            <img src="/svgs/undraw_note.svg" alt="" class="absolute fill-cyan-500  scale-150 -bottom-20 right-10">
            <img src="/svgs/undraw_fun-arrow.svg" alt="" class="absolute fill-cyan-500  scale-150 -top-20 left-10">
        </h1>

        <p class="text-gray-600 text-lg mt-20 mb-16">
            Connecting book lovers across Algeria. Discover a wide range of books, sell your used ones, and find your next great read with us.
        </p> -->

      <!-- <p class="mt-4">
            Or download the <a href="#" class="text-blue-600 underline">Mobile app version</a>
        </p> -->
    </main>

    <div
      class="blob w-[1000px] h-[1000px] rounded-[999px] absolute bottom-0 left-0 -z-10 blur-3xl bg-opacity-60 bg-gradient-to-r from-red-200 via-gray-100 to-blue-100"
    ></div>
    <div
      class="blob w-[600px] h-[600px] rounded-[999px] absolute bottom-0 left-0 -z-10 blur-3xl bg-opacity-60 bg-gradient-to-r from-slate-100 via-teal-100 to-blue-100"
    ></div>
  </div>

  <!-- <footer class="mt-20 z-10 text-center text-gray-600">
        <p class="font-bold text-xl">Buy your next read, as easy as it should be</p>
    </footer> -->
</template>
<style>
@import url("https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap");
</style>
