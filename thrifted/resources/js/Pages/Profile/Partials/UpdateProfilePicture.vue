<script setup>
import { Link, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileUpload from "primevue/fileupload";
import Button from "primevue/button";
import axios from "axios";
import { ref } from "vue";
const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});
const file = ref(null);
const uploadPercentage = ref(0);
const user = usePage().props.auth.user;
function handleFileUpload() {
  file = this.$refs.file.files[0];
}
async function submitFile() {
  let fd = new FormData();
  fd.append("file", file.value);
  fd.append("user_id", user.id);
  axios
    .post("/api/users/update_picture", fd, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      onUploadProgress: function (progressEvent) {
        uploadPercentage.value = parseInt(
          Math.round((progressEvent.loaded / progressEvent.total) * 100)
        );
      }.bind(this),
    })
    .then(function () {
      console.log("SUCCESS!!");
    })
    .catch(function () {
      console.log("FAILURE!!");
    });
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
      <img
        v-if="user.picture != null"
        :src="user.picture.replace('public/', '/storage/')"
        alt=""
      />
      <div v-else class="flex flex-col justify-center items-center w-64 h-6w-64">
        <img src="/default-avatar.jpg" alt="" />

        <Button label="Change" severity="contrast" />
      </div>
    </div>
  </section>
</template>

