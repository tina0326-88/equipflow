<template>
  <div>
    <div class="d-flex justify-space-between align-center mb-8">
      <h2 class="text-h4 font-weight-bold">報修管理</h2>
      <v-btn
        color="red"
        prepend-icon="mdi-plus"
        elevation="2"
        to="/repairs/create"
      >
        新增報修單
      </v-btn>
    </div>

    <!-- 搜尋與篩選 -->
    <v-card class="repair-card pa-4 mb-6" elevation="2">
      <v-row align="center">
        <v-col cols="12" md="6">
          <v-text-field
            v-model="search"
            placeholder="搜尋報修單標題..."
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="comfortable"
            hide-details
            clearable
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-select
            v-model="statusFilter"
            :items="statusOptions"
            item-title="label"
            item-value="value"
            label="報修狀態"
            variant="outlined"
            density="comfortable"
            hide-details
            clearable
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-select
            v-model="priorityFilter"
            :items="priorityOptions"
            item-title="label"
            item-value="value"
            label="優先等級"
            variant="outlined"
            density="comfortable"
            hide-details
            clearable
          />
        </v-col>
      </v-row>
    </v-card>

    <!-- 報修列表 -->
    <v-card class="repair-card" elevation="2">
      <v-data-table
        :headers="headers"
        :items="filteredRepairs"
        :loading="repairStore.loading"
        loading-text="載入中..."
        no-data-text="目前沒有報修案件"
        items-per-page-text="每頁顯示"
        hover
      >
        <!-- 狀態欄 -->
        <template #item.status="{ item }">
          <v-chip :color="getStatusColor(item.status)" size="small" label>
            {{ getStatusText(item.status) }}
          </v-chip>
        </template>

        <!-- 優先等級欄 -->
        <template #item.priority="{ item }">
          <v-chip
            :color="getPriorityColor(item.priority)"
            size="small"
            variant="outlined"
          >
            {{ getPriorityText(item.priority) }}
          </v-chip>
        </template>

        <!-- 操作欄 -->
        <template #item.actions="{ item }">
          <v-btn
            icon="mdi-eye-outline"
            size="small"
            variant="text"
            color="primary"
            class="mr-1"
            :to="`/repairs/${item.id}`"
          />
          <v-btn
            icon="mdi-delete-outline"
            size="small"
            variant="text"
            color="error"
            @click="confirmDelete(item)"
          />
        </template>
      </v-data-table>
    </v-card>

    <!-- 刪除確認 Dialog -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card rounded="lg">
        <v-card-title class="text-h6 pa-6">確認刪除</v-card-title>
        <v-card-text class="px-6 pb-4">
          確定要刪除「<strong>{{ selectedRepair?.title }}</strong
          >」嗎？此操作無法復原。
        </v-card-text>
        <v-card-actions class="pa-6 pt-0">
          <v-spacer />
          <v-btn variant="text" @click="deleteDialog = false">取消</v-btn>
          <v-btn color="red" variant="flat" @click="handleDelete"
            >確認刪除</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRepairStore } from "@/stores/repair";

const repairStore = useRepairStore();

onMounted(() => {
  repairStore.fetchRepairs();
});

const search = ref("");
const statusFilter = ref(null);
const priorityFilter = ref(null);

const statusOptions = [
  { label: "待處理", value: "pending" },
  { label: "處理中", value: "processing" },
  { label: "已完成", value: "done" },
  { label: "取消", value: "cancelled" },
];

const priorityOptions = [
  { label: "高", value: "high" },
  { label: "中", value: "medium" },
  { label: "低", value: "low" },
];

const headers = [
  { title: "標題", key: "title", sortable: true },
  { title: "狀態", key: "status", sortable: true },
  { title: "優先等級", key: "priority", sortable: true },
  { title: "報修時間", key: "reported_at", sortable: true },
  { title: "完成時間", key: "completed_at" },
  { title: "操作", key: "actions", sortable: false, align: "center" },
];

const filteredRepairs = computed(() => {
  return repairStore.repairs.filter((r) => {
    const matchSearch = !search.value || r.title.includes(search.value);
    const matchStatus = !statusFilter.value || r.status === statusFilter.value;
    const matchPriority =
      !priorityFilter.value || r.priority === priorityFilter.value;
    return matchSearch && matchStatus && matchPriority;
  });
});

const deleteDialog = ref(false);
const selectedRepair = ref(null);

const confirmDelete = (item) => {
  selectedRepair.value = item;
  deleteDialog.value = true;
};

const handleDelete = async () => {
  await repairStore.deleteRepair(selectedRepair.value.id);
  deleteDialog.value = false;
  selectedRepair.value = null;
};

const getStatusText = (status) =>
  ({
    pending: "待處理",
    processing: "處理中",
    done: "已完成",
    cancelled: "取消",
  })[status] || status;

const getStatusColor = (status) =>
  ({
    pending: "warning",
    processing: "info",
    done: "success",
    cancelled: "default",
  })[status] || "default";

const getPriorityText = (priority) =>
  ({ high: "高", medium: "中", low: "低" })[priority] || priority;

const getPriorityColor = (priority) =>
  ({ high: "error", medium: "warning", low: "success" })[priority] || "default";
</script>

<style scoped>
.repair-card {
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.repair-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2) !important;
}
</style>