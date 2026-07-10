<template>
  <v-chip :color="chipColor" :size="size" :variant="variant" :label="label">
    <v-icon v-if="showIcon" start :icon="chipIcon" />
    {{ chipText }}
  </v-chip>
</template>

<script setup>
import { computed } from "vue";
import {
  getRepairStatusColor,
  getPriorityColor,
  getEquipmentStatusColor,
} from "@/utils/statusColor";
import {
  formatRepairStatus,
  formatPriority,
  formatEquipmentStatus,
} from "@/utils/formatData";

const props = defineProps({
  // 狀態類型：repair / priority / equipment
  type: {
    type: String,
    default: "repair",
  },
  // 狀態值
  status: {
    type: String,
    required: true,
  },
  // 尺寸
  size: {
    type: String,
    default: "small",
  },
  // 樣式
  variant: {
    type: String,
    default: "flat",
  },
  // 是否顯示圖示
  showIcon: {
    type: Boolean,
    default: false,
  },
  label: {
    type: Boolean,
    default: true,
  },
});

const chipColor = computed(() => {
  if (props.type === "repair") return getRepairStatusColor(props.status);
  if (props.type === "priority") return getPriorityColor(props.status);
  if (props.type === "equipment") return getEquipmentStatusColor(props.status);
  return "default";
});

const chipText = computed(() => {
  if (props.type === "repair") return formatRepairStatus(props.status);
  if (props.type === "priority") return formatPriority(props.status);
  if (props.type === "equipment") return formatEquipmentStatus(props.status);
  return props.status;
});

const chipIcon = computed(() => {
  if (props.type === "repair") {
    return (
      {
        pending: "mdi-clock-outline",
        processing: "mdi-wrench-outline",
        done: "mdi-check-circle-outline",
        completed: "mdi-check-circle-outline",
        cancelled: "mdi-close-circle-outline",
      }[props.status] || "mdi-help-circle-outline"
    );
  }
  if (props.type === "priority") {
    return (
      {
        high: "mdi-arrow-up-circle-outline",
        medium: "mdi-minus-circle-outline",
        low: "mdi-arrow-down-circle-outline",
      }[props.status] || "mdi-help-circle-outline"
    );
  }
  if (props.type === "equipment") {
    return (
      {
        active: "mdi-check-circle-outline",
        maintenance: "mdi-wrench-clock",
        broken: "mdi-alert-circle-outline",
      }[props.status] || "mdi-help-circle-outline"
    );
  }
  return "mdi-help-circle-outline";
});
</script>
