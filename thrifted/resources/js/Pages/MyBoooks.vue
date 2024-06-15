<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";
const visible = ref(false);
import Button from "primevue/button";
import Toast from "primevue/toast";
import NavBar from '../Components/NavBar.vue';
import Dialog from "primevue/dialog";
import InputText from "primevue/InputText";
import Avatar from "primevue/avatar";
import FileUpload from "primevue/fileupload";
import InputNumber from "primevue/inputnumber";
const customBase64Uploader = async (event) => {
    const file = event.files[0];
    const reader = new FileReader();
    let blob = await fetch(file.objectURL).then((r) => r.blob()); //blob:url

    reader.readAsDataURL(blob);

    reader.onloadend = function () {
        const base64data = reader.result;
    };
};
const value1 = ref(42723);

const props = defineProps(
    {
        books: {
            type: Array,
            required: true,
        },
    },
);
</script>

<template>

    <Head title="My Books" />
    <NavBar />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-gray-800 text-xl leading-tight">My books</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="bg-white shadow-sm p- p-5 sm:rounded-lg overflow-hidden">
                    <div class="p-6 text-gray-900">Here are your books!</div>
                    <div class="grid grid-cols-4">
                        <div v-for="book in props.books">
                            {{ book.title }}
                            <img :src="book.cover" alt="">
                        </div>
                    </div>
                    <Button label="Show" @click="visible = true" />
                    <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
                        <template #header>
                            <div class="inline-flex justify-center items-center gap-2">
                                <Avatar image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
                                    shape="circle" />
                                <span class="font-bold whitespace-nowrap">{{ $page.props.auth.user.name }}</span>
                            </div>
                        </template>
                        <span class="block mb-8 text-surface-500 dark:text-surface-400">Add a Book</span>
                        <div class="flex items-center gap-4 mb-4">
                            <label for="title" class="w-24 font-semibold">Title</label>
                            <InputText id="title" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="edition" class="w-24 font-semibold">Edition</label>
                            <InputText id="edition" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="author" class="w-24 font-semibold">Author</label>
                            <InputText id="author" class="flex-auto" autocomplete="off" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <label for="ssn" class="w-24 font-semibold">ISBN</label>
                            <InputNumber v-model="value1" inputId="integeronly" />
                        </div>
                        <div class="flex items-center gap-4 mb-2">
                            <Toast />
                            <label for="Cover" class="w-24 font-semibold">Cover</label>
                            <FileUpload mode="basic" name="demo[]" url="/api/upload" accept="image/*" customUpload
                                @uploader="customBase64Uploader" />
                        </div>
                        <template #footer>
                            <Button label="Cancel" text severity="secondary" @click="visible = false" autofocus />
                            <Button label="Save" outlined severity="secondary" @click="visible = false" autofocus />
                        </template>
                    </Dialog>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
