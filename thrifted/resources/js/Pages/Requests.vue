<script setup>
import AuthenticatedLayout from "@/Layouts/NewLayout/AuthenticatedLayout.vue";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup";
import Row from "primevue/row";
import Tag from "primevue/tag";
import RequestStatusPopover from "@/Components/RequestStatusPopover.vue";
const props = defineProps({
  requests: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="px-20">
      <DataTable
        :value="props.requests"
        paginator
        :rows="10"
        dataKey="id"
        tableStyle="min-width: 40rem"
      >
        <template #header>
          <div class="flex flex-wrap items-center justify-between gap-2">
            <span class="text-xl font-bold">Book Requests</span>
            <Button icon="pi pi-refresh" rounded raised />
          </div>
        </template>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">User</p>
          </template>
          <template #body="slotProps">
            <div
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
            </div>
          </template>
        </Column>
        <Column>
          <template #header>
            <p class="text-center text-xl w-full">Book</p>
          </template>
          <template #body="slotProps">
            <div class="flex justify-center items-center">
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
            </div>
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
              {{ slotProps.data.book.price }}
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