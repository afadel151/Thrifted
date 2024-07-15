<script setup>

import { useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Select from "primevue/select";
import axios from "axios";
import { onMounted, ref, Transition } from "vue";
const Wilayas = ref([]);
const SelectedCity = ref(usePage().props.auth.user.wilaya_id);
onMounted(() => {
  axios
    .get("/api/wilayas")
    .then((response) => Wilayas.value = response.data)
    .catch((error) => console.log(error));
});

const form = useForm({
  wilaya_id: SelectedCity.value,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Wilaya</h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your account's wilaya
            </p>
        </header>
        <form @submit.prevent="form.post(route('profile.update_wilaya'))" class="mt-6 space-y-6">
            <div>

                <Select v-model="SelectedCity" class="flex-auto w-full" placeholder="Select a City" autocomplete="off" :options="Wilayas" optionLabel="name" optionValue="id" />
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
</section>

</template>