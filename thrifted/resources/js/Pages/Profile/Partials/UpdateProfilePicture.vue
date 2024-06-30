<script setup>
import { Link, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileUpload from "primevue/fileupload";
import axios from "axios";
import { ref } from "vue";

const file = ref(null);
const uploadPercentage = ref(0);
const user_id = usePage().props.auth.user.id;
function handleFileUpload(){
  file = this.$refs.file.files[0];
}
async function submitFile() {
  let fd = new FormData();
  fd.append("file", file.value);
  fd.append("user_id", user_id);
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
console.log($refs);
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Phone number</h2>
      <p class="mt-1 text-sm text-gray-600">
        Update your account's Phone number.
      </p>
    </header>
    <form @submit.prevent="submitFile" class="mt-6 space-y-6">
      <div>
        <label
          >File
          <input
            type="file"
            id="file"
            ref="file"
            v-on:change="handleFileUpload()"
          />
        </label>
        <br />
        <progress max="100" :value.prop="uploadPercentage"></progress>
        <br />
      </div>
      <div class="flex items-center gap-4">
        <PrimaryButton>Save</PrimaryButton>
      </div>
    </form>
  </section>
</template>

