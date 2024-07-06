<script setup>
import TextInput from "@/Components/TextInput.vue";
import InputText from "primevue/inputtext";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import axios from "axios";

const user = usePage().props.auth.user;
const FacebookLink = ref(user.facebook_url);
const InstagramLink = ref(user.instagram_url);
const TwitterLink = ref(user.twitter_url);
const GoodreadsLink = ref(user.goodreads_url);

function isURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return pattern.test(str);
}



async function SaveUrls() {
  let fd = new FormData();
  fd.append("user_id",user.id);
  if (
    FacebookLink.value == '' &&
    InstagramLink.value == '' &&
    TwitterLink.value == '' &&
    GoodreadsLink.value == ''
  ) {
    alert("You should provide at least one link");
  } else {
    if (FacebookLink.value != '' && FacebookLink.value.trim() !== "") {
      if (isURL(FacebookLink.value)) {
        fd.append("facebook_url", FacebookLink.value);
      }
    }
    if (InstagramLink.value != '' && InstagramLink.value.trim() !== "") {
      if (isURL(InstagramLink.value)) {
        fd.append("instagram_url", InstagramLink.value);
      }
    }
    if (TwitterLink.value != '' && TwitterLink.value.trim() !== "") {
      if (isURL(TwitterLink.value)) {
        fd.append("twitter_url", TwitterLink.value);
      }
    }
    if (GoodreadsLink.value != '' && GoodreadsLink.value.trim() !== "") {
      if (isURL(GoodreadsLink.value)) {
        fd.append("goodreads_url", GoodreadsLink.value);
      }
    }
    try {
      let response = await axios.post("/api/users/add_social_links", fd);
      if (response.data == 'success') {
        location.reload();
      }
    } catch (error) {
      console.log(error);
    }
  }
}
</script>

<template>
    
  <section>  
    <header>
      <h2 class="text-lg font-medium text-gray-900">Social links</h2>
      <p class="mt-1 text-sm text-gray-600">Update your account's links.</p>
    </header>
    <div class="mt-6 space-y-6">
      <div>
        <div class="flex items-center gap-1">
          <i class="pi pi-facebook"></i>
          <InputLabel for="facebook" value="Facebook" />
        </div>
        <InputText
          id="facebook"
          type="text"
          v-model="FacebookLink"
          class="mt-1 block w-full"
        />
      </div>
      <div>
        <div class="flex items-center gap-1">
          <i class="pi pi-instagram"></i>
          <InputLabel for="instagram" value="Instagram" />
        </div>
        <InputText
          id="instagram"
          type="text"
          v-model="InstagramLink"
          class="mt-1 block w-full"
        />
      </div>
      <div>
        <div class="flex items-center gap-1">
          <i class="pi pi-twitter"></i>
          <InputLabel for="twitter" value="Twitter" />
        </div>
        <TextInput
          id="twitter"
          v-model="TwitterLink"
          type="text"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="twitter"
        />
      </div>
      <div>
        <div class="flex items-center gap-1">
          <img src="/svgs/goodreads.svg" height="16px" width="16px" alt="" />
          <InputLabel for="goodreads" value="Goodreads" />
        </div>
        <TextInput
          id="goodreads"
          v-model="GoodreadsLink"
          type="text"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="goodreads"
        />
      </div>
      <div class="flex items-center gap-4">
        <PrimaryButton @click="SaveUrls">Save</PrimaryButton>
      </div>
    </div>
  </section>
</template>
