<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";


import { Link } from "@inertiajs/vue3";
import RequestStatusPopover from "@/Components/RequestStatusPopover.vue";
const props = defineProps({
  requests: {
    type: Array,
    required: true,
  },
});
function getDate(datetime){
  var timestamp=new Date(datetime).getTime();
    var todate=new Date(timestamp).getDate();
    var tomonth=new Date(timestamp).getMonth()+1;
    var toyear=new Date(timestamp).getFullYear();
    var original_date=tomonth+'/'+todate+'/'+toyear;
    return original_date; 
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="px-32 mt-20">
      <DataTable
        :value="props.requests"
        paginator
        :rows="10"
        dataKey="id"
        
        tableStyle="min-width: 40rem;"
         :rowsPerPageOptions="[5, 10, 20, 50]"
      >
        <template #header>
            <p class="text-3xl text-center py-5 font-bold">Book Requests</p>
        </template>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">User</p>
          </template>
          <template #body="slotProps">
            <Link :href="route('profile.show', {id: slotProps.data.user.id})"
              class="h-full  flex text-2xl font-bold justify-center  gap-2 items-center"
            >
              <div
                v-if="slotProps.data.user.picture"
                class="w-[60px] h-[60px] rounded-full bg-cover bg-center"
                :style="{
                  backgroundImage: `url('${slotProps.data.user.picture.replace(
                    'public/',
                    '/storage/'
                  )}')`,
                }"
              ></div>
              <div
                v-else
                class="w-[60px] h-[60px] rounded-full bg-cover bg-center"
                style="background-image: url('/default-avatar.jpg')"
              ></div>
              <p >{{ slotProps.data.user.name }}</p>
              </Link>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class=" text-xl w-full">Book</p>
          </template>
          <template #body="slotProps">
            <Link class="flex justify-start items-center"
                  :href="route('books.show', {id: slotProps.data.book.id})"
            >
              <img
                :src="slotProps.data.book.cover.replace('public/', '/storage/')"
                class="w-12"
                alt=""
              />
              <div class="p-3">
                <p class="text-xl font-bold">{{ slotProps.data.book.title }}</p>
                <p class="text-lg font-bold">
                  {{ slotProps.data.book.author }}
                </p>
              </div>
            </Link>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">Price</p>
          </template>
          <template #body="slotProps">
            <div
              class="h-full text-2xl font-bold w-full flex justify-center items-center"
            >
              {{ slotProps.data.book.price }}DA
            </div>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">Phone number</p>
          </template>
          <template #body="slotProps">
            <div
              class="h-full w-full text-2xl font-bold flex justify-center items-center"
            >
              {{ slotProps.data.phone_number }}
            </div>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">Date</p>
          </template>
          <template #body="slotProps">
            <div
              class="h-full w-full text-2xl font-bold flex justify-center items-center"
            >
            {{ getDate(slotProps.data.created_at) }}
            </div>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">Wilaya</p>
          </template>
          <template #body="slotProps">
            <div
              class="h-full w-full text-2xl font-bold flex justify-center items-center"
            >
              {{ slotProps.data.wilaya.name }}
            </div>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">Status</p>
          </template>
          <template #body="slotProps">
            <RequestStatusPopover
              :status="slotProps.data.status"
              :request_id="slotProps.data.id"
            />
          </template>
        </Column>
      </DataTable>
    </div>
  </AuthenticatedLayout>
</template>