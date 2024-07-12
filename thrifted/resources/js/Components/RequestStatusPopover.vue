<script setup>
import Popover from "primevue/popover";
import Tag from "primevue/tag";
import { ref } from "vue";
import Button from "primevue/button";
import axios from "axios";
const op = ref();
const toggle = (event) => {
  op.value.toggle(event);
};
const props = defineProps({
  status: {
    type: String,
    required: true,
  },
  request_id: {
    type: Number,
    required: true,
  },
});
const CurrentStatus = ref(props.status);
async function ChangeRequestStatus(status) {
  try {
    let response = await axios.post(
      `/api/requests/${props.request_id}/change_status`,
      {
        request_id: props.request_id,
        status: status,
      }
    );

    CurrentStatus.value = response.data.status;
    toggle();
  } catch (error) {
    console.log(error);
  }
}
const SwitchIcon = (status) => {
  switch (status) {
    case "Untreated":
      return "pi pi-ban";

    case "In Progress":
      return "pi pi-hourglass";

    case "Finished":
      return "pi pi-check";

    case "Canceled":
      return "pi pi-times";
    default:
      break;
  }
};
const SwitchSeverity = (status) => {
  switch (status) {
    case "Untreated":
      return "danger";

    case "In Progress":
      return "info";

    case "Finished":
      return "primary";

    case "Canceled":
      return "secondary";
    default:
      break;
  }
}
</script>
<template>
  <div class="w-full h-full flex justify-center items-center">
    <Tag
      :icon="SwitchIcon(CurrentStatus)"
      class="hover:cursor-pointer"
      @click="toggle"
      :value="CurrentStatus"
      :severity="SwitchSeverity(CurrentStatus)"
    />
    <Popover ref="op">
      <div class="flex flex-col justify-center gap-2 items-stretch">
        <Button
          icon="pi pi-ban"
          @click="ChangeRequestStatus('Untreated')"
          severity="danger"
          outlined
          label="Untreated"
        />
        <Button
          icon="pi pi-hourglass"
          severity="info"
          outlined
          @click="ChangeRequestStatus('In Progress')"
          label="In Progress"
        />
        <Button
          icon="pi pi-check"
          outlined
          @click="ChangeRequestStatus('Finished')"
          label="Finished"
        />
        <Button
          icon="pi pi-times"
          outlined
          @click="ChangeRequestStatus('Canceled')"
          severity="secondary"
          label="Canceled"
        />
      </div>
    </Popover>
  </div>
</template>