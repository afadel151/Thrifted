<script setup>
import { usePage } from "@inertiajs/vue3";
import Button from "primevue/button";
import axios from "axios";
import { ref } from "vue";
const file = ref(null);
const user = usePage().props.auth.user;
const Picture = ref(user.picture);
async function submitFile() {
  let fd = new FormData();
  fd.append("file", file.value);
  fd.append("user_id", user.id);
  try {
    let response = await axios.post("/api/users/update_picture", fd);
    console.log(response.data);
    Picture.value = response.data;
  } catch (error) {
    console.log(error);
  }
}
function onChange(e) {
  file.value = e.target.files[0];
}

</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Profile picture</h2>
      <p class="mt-1 text-sm text-gray-600">
        Update your account's Profile picture.
      </p>
    </header>
    <div class="mt-6 space-y-6">
      <div v-if="user.picture" :class="`h-[182px] rounded-full bg-cover bg-center w-[182px]`" :style="{ backgroundImage: `url('${Picture.replace('public/', '/storage/')}')` }">
</div>

      <div
        v-else
        class="flex flex-col justify-center space-y-2 items-center w-72"
      >
        <img src="/default-avatar.jpg" alt="" />
      </div>
      <input type="file" @change="onChange" />
      <Button
        label="Change"
        @click="submitFile"
        severity="contrast"
        v-show="file != null"
      />
    </div>
  </section>
</template>



