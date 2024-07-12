<script setup>
import { Link } from "@inertiajs/vue3";
import { onBeforeMount, ref } from "vue";
import Rating from "primevue/rating";
const Ratings = ref(0);
const booksNumber = ref(0);
const soldNumer = ref(0);
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});
onBeforeMount(() => {
  axios
    .get("/api/users/" + props.user.id + "/carousel_details")
    .then((response) => {
      Ratings.value = response.data.ratings;
      booksNumber.value = response.data.books;
      soldNumer.value = response.data.sld;
    })
    .catch((error) => {
      console.log(error);
    });
});
const UserRating = ref(props.user.rating);
</script>


<template>
  <div class="border-2 m-2 p-4 rounded-xl bg-white">
    <Link :href="route('profile.show',{id: props.user.id})">
      <div class="mb-4 flex items-center justify-center">
      <div
        v-if="props.user.picture"
        class="w-[200px] h-[200px] rounded-full bg-cover bg-center"
        :style="{
          backgroundImage: `url('${user.picture.replace(
            'public/',
            '/storage/'
          )}')`,
        }"
      ></div>
      <div
        v-else
        class="w-[200px] h-[200px] rounded-full bg-cover bg-center"
        style="background-image: url('/default-avatar.jpg')"
      ></div>
    </div>
    <div class="mb-4 font-bold text-xl">{{ props.user.name }}</div>
    <div class="mb-4 font-medium">
      <i class="pi pi-map-marker" style="color: green"></i>
      {{ props.user.adress != null ? props.user.adress : "No adress" }}
    </div>
    <div class="flex justify-between items-center">
      <div class="mt-0 font-semibold flex items-center gap-2">
        <Rating v-model="UserRating" /> ({{ Ratings }})
      </div>
    </div>
    </Link>
  </div>
</template>