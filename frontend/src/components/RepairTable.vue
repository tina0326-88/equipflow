<template>
  <v-data-table
    :headers="headers"
    :items="repairs"
    :loading="loading"
    loading-text="載入中..."
    no-data-text="目前沒有報修案件"
    items-per-page-text="每頁顯示"
    hover
  >
    <!-- 標題欄 -->
    <template #item.title="{ item }">
      <span class="font-weight-medium">{{ item.title }}</span>
    </template>

    <!-- 狀態欄 -->
    <template #item.status="{ item }">
      <StatusChip type="repair" :status="item.status" :show-icon="true" />
    </template>

    <!-- 優先等級欄 -->
    <template #item.priority="{ item }">
      <StatusChip type="priority" :status="item.priority" variant="outlined" />
    </template>

    <!-- 報修時間欄 -->
    <template #item.reported_at="{ item }">
      <span class="text-body-2">{{ formatDateTime(item.reported_at) }}</span>
    </template>

    <!-- 完成時間欄 -->
    <template #item.completed_at="{ item }">
      <span class="text-body-2">{{ formatDateTime(item.completed_at) }}</span>
    </template>

    <!-- 操作欄 -->
    <template #item.actions="{ item }">
      <v-tooltip text="查看詳情" location="top">
        <template #activator="{ props }">
          <v-btn
            v-bind="props"
            icon="mdi-eye-outline"
            size="small"
            variant="text"
            color="primary"
            class="mr-1"
            :to="`/repairs/${item.id}`"
          />
        </template>
      </v-tooltip>

      <v-tooltip text="刪除" location="top">
        <template #activator="{ props }">
          <v-btn
            v-bind="props"
            icon="mdi-delete-outline"
            size="small"
            variant="text"
            color="error"
            @click="$emit('delete', item)"
          />
        </template>
      </v-tooltip>
    </template>
  </v-data-table>
</template>

<script setup>
import StatusChip from "@/components/StatusChip.vue";
import { formatDateTime } from "@/utils/formatData";

defineProps({
  repairs: {
    type: Array,
    default: () => [],
  },
  loading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["delete"]);

const headers = [
  { title: "標題", key: "title", sortable: true },
  { title: "狀態", key: "status", sortable: true },
  { title: "優先等級", key: "priority", sortable: true },
  { title: "報修時間", key: "reported_at", sortable: true },
  { title: "完成時間", key: "completed_at", sortable: false },
  { title: "操作", key: "actions", sortable: false, align: "center" },
];
</script>
