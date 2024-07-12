<script setup>
import AuthenticatedLayout from '@/Layouts/NewLayout/AuthenticatedLayout.vue';import Carousel from "primevue/carousel";
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Tag from "primevue/tag";
import Button from "primevue/button";
import Rating from "primevue/rating";
import Inplace from "primevue/inplace";
import Popover from "primevue/popover";
import axios from "axios";
import { comment } from "postcss";
const op = ref();
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  books: {
    type: Array,
    required: true,
  },
  soldbooks: {
    type: Array,
    required: true,
  },
  rating: {
    type: Number,
    required: true,
  },
  ratings:{
    type:Array,
    required: true
  }
});
const responsiveOptions = ref([
  {
    breakpoint: "1400px",
    numVisible: 2,
    numScroll: 1,
  },
  {
    breakpoint: "1199px",
    numVisible: 3,
    numScroll: 1,
  },
  {
    breakpoint: "767px",
    numVisible: 2,
    numScroll: 1,
  },
  {
    breakpoint: "575px",
    numVisible: 1,
    numScroll: 1,
  },
]);
const getSeverity = (status) => {
  if (status == true) {
    return "success";
  } else {
    return "danger";
  }
};
const Ratings = ref(props.ratings);
const toggle = (event) => {
  op.value.toggle(event);
};
const CommentValue = ref('');
const NewRating = ref(0);
async function AddRating(){
    try {
        let response = await axios.post('/api/users/rate',
            {
                user_id : usePage().props.auth.user.id,
                rated_user_id : props.user.id,
                rating : NewRating.value,
                comment : CommentValue.value 
            }
        );
        console.log(response.data);
        Ratings.value.push(response.data);
        toggle();
    } catch (error) {   
        console.log(error);
    }
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-[1170px]:flex-col max-[1170px]:items-center  flex  justify-around space-x-10 max-[1170px]:space-x-0 max-[1170px]:space-y-5 w-screen pt-10  max-[1170px]:px-3 px-16">
      <div class="w-3/4  max-[1170px]:w-full">
        <div
          class="backdrop-blur-sm bg-white/40 max-w-7xl flex gap-20 max-[1170px]:flex-col max-[1170px]:items-center max-[1170px]:space-y-4 shadow-lg  sm:px-6 lg:px-10 sm:rounded-xl overflow-hidden py-10"
        >
          <div
            v-if="props.user.picture"
            class="w-[250px] h-[250px] rounded-full  bg-cover bg-center"
            :style="{
              backgroundImage: `url('${user.picture.replace(
                'public/',
                '/storage/'
              )}')`,
            }"
          ></div>
          <div
            v-else
            class="w-[250px] h-[250px] rounded-full   bg-cover bg-center"
            style="background-image: url('/default-avatar.jpg')"
          ></div>
          <div
            class="flex flex-col text-xl items-start justify-around  max-[1170px]:w-full max-[1170px]:ml-0 max-[1170px]:items-center "
          >
            <p><span class="font-bold">Name :</span> {{ props.user.name }}</p>
            <p><span class="font-bold">Email :</span>{{ props.user.email }}</p>
            <p>
              <span class="font-bold">Adress :</span> {{ props.user.adress }}
            </p>
            <p v-if="props.user.phone != null">
              <span class="font-bold">Phone :</span> {{ props.user.phone }}
            </p>
            <p>
              <span class="font-bold">Delivery ? :</span>
              {{
                props.user.delivery == 1 ? "Included" : "No delivery options"
              }}
            </p>
            <p>
              <span class="font-bold">Payment :</span>
              {{
                props.user.payment_on_hand == 1 ? "Hand to hand" : "On delivery"
              }}
            </p>
            <div class="flex justify-start">
              <p class="font-bold mr-10">Rating :</p>
              <Rating v-model="props.rating" severity="contrast" readonly />
              <p>({{ props.user.ratings.length }})</p>
            </div>
            <p>
              <span class="font-bold">Available books :</span>
              {{
                props.books.filter((book) => {
                  return book.available == 1;
                }).length
              }}
            </p>
            <p>
              <span class="font-bold">Sold books :</span>
              {{ props.soldbooks.length }}
            </p>
            <div class=" flex items-center space-x-2">
              <a v-tooltip.top="'Facebook account'" v-show="props.user.facebook_url != ''" target="_blank" :href="props.user.facebook_url"><Button icon="pi pi-facebook" rounded  severity="contrast" /></a>
              <a v-tooltip.top="'Instagram account'" v-show="props.user.instagram_url != ''" target="_blank" :href="props.user.instagram_url"><Button icon="pi pi-instagram" rounded  severity="contrast" /></a>
              <a v-tooltip.top="'Twitter account'"  v-show="props.user.twitter_url != ''" target="_blank" :href="props.user.twitter_url"><Button icon="pi pi-twitter" rounded  severity="contrast" /></a>
              <a v-tooltip.top="'Goodreads account'" v-show="props.user.goodreads_url != ''" target="_blank" :href="props.user.goodreads_url"><Button icon="pi pi-book" rounded  severity="contrast" /></a>
            </div>
          </div>
        </div>
        <div
          class="mt-6 flex flex-col items-stretch backdrop-blur-sm bg-white/40 max-w-7xl shadow-lg sm:px-6 lg:px-8 sm:rounded-lg overflow-hidden py-10"
        >
          <div class="w-full max-[600px]:px-2 flex justify-between  items-center">
            <div class="p-6 text-gray-900 text-4xl">
              📚 {{ props.user.name }}'s books!
            </div>
            <Link :href="route('profile.books', { id: props.user.id })">
              <Button label="View All"   raised />
            </Link>
          </div>
          <Carousel
            :value="props.books"
            :numVisible="4"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
          >
            <template #item="slotProps">
              <div class="border-2 m-2 bg-white  p-4 rounded-xl">
                <Link :href="route('books.show', { id: slotProps.data.id })">
                  <div class="mb-4">
                    <div class="relative flex justify-center mx-auto">
                      <img
                        :src="
                          slotProps.data.cover == null
                            ? '/cover-not-available.jpg'
                            : slotProps.data.cover.replace(
                                'public/',
                                '/storage/'
                              )
                        "
                        :alt="slotProps.data.name"
                        class="rounded h-52"
                      />
                      <Tag
                        :value="
                          slotProps.data.available == 1 ? 'available' : 'sold'
                        "
                        :severity="getSeverity(slotProps.data.available)"
                        class="absolute"
                        style="left: 5px; top: 5px"
                      />
                    </div>
                  </div>
                </Link>
                <div class="mb-4 font-medium">{{ slotProps.data.title }}</div>

                <div class="flex justify-between items-center">
                  <div class="mt-0 font-semibold text-xl">
                    {{ slotProps.data.price }} DA
                  </div>
                  <span>
                    <Button icon="pi pi-heart" severity="secondary" outlined />
                    <Button icon="pi pi-shopping-cart" class="ml-2" />
                  </span>
                </div>
              </div>
            </template>
          </Carousel>
        </div>
        
      </div>
      <div class="w-1/4 max-[1170px]:w-full backdrop-blur-sm bg-white/40 rounded-lg p-10 ">
        <div class="w-full flex justify-between">
          <p class="w-full font-bold text-5xl">Ratings</p>
          <Button
            type="button"
            label="Add"
            @click="toggle"
            v-show="props.user.id != usePage().props.auth.user.id"
          />

          <Popover ref="op" >
            <div class="flex flex-col gap-4 w-[25rem]" v-show="props.user.id != usePage().props.auth.user.id">
                <p class="text-2xl">Add your rating</p>
                <Rating v-model="NewRating" />
                <p class="text-xl">Tell us something about {{props.user.name}} (optional) :</p>
                <InputText maxlength="100" placeholder="Add comment" id="username" v-model="CommentValue" />
                <Button @click="AddRating"  label="Add" />
              
            </div>
          </Popover>
        </div>
        <div
          class="flex flex-col mt-10 rounded-lg items-center gap-2 justify-start"
        >
          <div class="w-full bg-white border-2 p-3 rounded-xl" v-for="rating in Ratings" :key="rating.id">
            <div class="flex items-center">
              <div
            v-if="rating.user.picture"
            class="w-[30px] h-[30px] rounded-full flex  bg-cover bg-center"
            :style="{
              backgroundImage: `url('${rating.user.picture.replace(
                'public/',
                '/storage/'
              )}')`,
            }"
          ></div>
          <div
            v-else
            class="w-[30px] h-[30px] rounded-full flex  bg-cover bg-center"
            style="background-image: url('/default-avatar.jpg')"
          ></div>
              <Link :href="route('profile.show',{id:rating.user.id})"><p class="text-2xl hover:text-gray-600 font-semibold">{{rating.user.name}}</p></Link>
            </div>
            <Rating v-model="rating.rating" readonly />
            
                <p class="m-0" v-if="rating.comment != null">
                  {{rating.comment}}
                </p>
           
          </div>
         
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
 

</template>