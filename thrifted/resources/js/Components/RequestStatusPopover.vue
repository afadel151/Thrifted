<script setup>
import Popover from "primevue/popover";
import Tag from "primevue/tag";
import { ref } from "vue";
import Button from "primevue/button"
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
</script>
<template>
  <div class="w-full h-full flex justify-center items-center">
    <Tag
    :icon="'pi ' + (CurrentStatus == 'Untreated'
          ? 'pi-ban'
          : CurrentStatus == 'In Progress'
          ? 'pi-hourglass'
          : 'pi-check' )"
    class="hover:cursor-pointer"
      @click="toggle"
      :value="CurrentStatus"
      :severity="
        CurrentStatus == 'Untreated'
          ? 'danger'
          : CurrentStatus == 'In Progress'
          ? 'info'
          : 'primary'
      "
    />
    <Popover ref="op"> 
        <div class="flex flex-col justify-center gap-2 items-stretch">
            <Button icon="pi pi-ban" severity="danger" outlined  label="Untreated" />
            <Button icon="pi pi-hourglass" severity="info" outlined  label="In Progress" />
            <Button icon="pi pi-check" outlined label="Finished" />
            <Button icon="pi pi-times" outlined severity="secondary" label="Canceled" />
        </div>
    </Popover>
  </div>
</template>