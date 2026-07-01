<template>
  <div>
    <div class="d-flex justify-space-between align-center mb-8">
      <h2 class="text-h4 font-weight-bold">設備管理</h2>
      <v-btn color="red" prepend-icon="mdi-plus" elevation="2">
        新增設備
      </v-btn>
    </div>

    <!-- 統計卡片 -->
    <v-row class="mb-8">
      <v-col v-for="kpi in kpis" :key="kpi.title" cols="12" sm="6" md="3">
        <v-card
          class="equipment-card pa-5 text-center"
          :class="kpi.class"
          elevation="6"
        >
          <v-icon :color="kpi.iconColor" size="40" class="mb-3">{{
            kpi.icon
          }}</v-icon>
          <div class="text-body-2 mb-1 text-medium-emphasis">
            {{ kpi.title }}
          </div>
          <div class="text-h4 font-weight-bold" :class="kpi.textClass">
            {{ kpi.value }}
          </div>
        </v-card>
      </v-col>
    </v-row>

    <!-- 搜尋與篩選 -->
    <v-card class="equipment-card pa-4 mb-6" elevation="2">
      <v-row align="center">
        <v-col cols="12" md="6">
          <v-text-field
            v-model="search"
            placeholder="搜尋設備名稱、序號、位置..."
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
            label="設備狀態"
            variant="outlined"
            density="comfortable"
            hide-details
            clearable
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-select
            v-model="typeFilter"
            :items="typeOptions"
            label="設備類型"
            variant="outlined"
            density="comfortable"
            hide-details
            clearable
          />
        </v-col>
      </v-row>
    </v-card>

    <!-- 設備列表 -->
    <v-card class="equipment-card" elevation="2">
      <v-data-table
        :headers="headers"
        :items="filteredEquipments"
        :loading="equipmentStore.loading"
        loading-text="載入中..."
        no-data-text="目前沒有設備資料"
        items-per-page-text="每頁顯示"
        hover
      >
        <!-- 狀態欄 -->
        <template #item.status="{ item }">
          <v-chip :color="getStatusColor(item.status)" size="small" label>
            {{ getStatusText(item.status) }}
          </v-chip>
        </template>

        <!-- 類型欄 -->
        <template #item.type="{ item }">
          <v-chip color="default" size="small" variant="outlined">
            {{ item.type }}
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
          />
          <v-btn
            icon="mdi-pencil-outline"
            size="small"
            variant="text"
            color="warning"
            class="mr-1"
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
          確定要刪除設備「<strong>{{ selectedEquipment?.name }}</strong
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
import { useEquipmentStore } from "@/stores/equipment";

const equipmentStore = useEquipmentStore();

onMounted(() => {
  equipmentStore.fetchEquipments();
});

// 搜尋與篩選
const search = ref("");
const statusFilter = ref(null);
const typeFilter = ref(null);

const statusOptions = [
  { label: "正常", value: "active" },
  { label: "維修中", value: "maintenance" },
  { label: "故障", value: "broken" },
];

const typeOptions = ["空調", "監控", "消防", "電梯", "網路"];

// 表格欄位
const headers = [
  { title: "設備名稱", key: "name", sortable: true },
  { title: "類型", key: "type", sortable: true },
  { title: "序號", key: "serial_number" },
  { title: "位置", key: "location" },
  { title: "狀態", key: "status", sortable: true },
  { title: "購買日期", key: "purchase_date" },
  { title: "操作", key: "actions", sortable: false, align: "center" },
];

// 篩選邏輯
const filteredEquipments = computed(() => {
  return equipmentStore.equipments.filter((e) => {
    const matchSearch =
      !search.value ||
      e.name.includes(search.value) ||
      e.serial_number.includes(search.value) ||
      e.location.includes(search.value);
    const matchStatus = !statusFilter.value || e.status === statusFilter.value;
    const matchType = !typeFilter.value || e.type === typeFilter.value;
    return matchSearch && matchStatus && matchType;
  });
});

// KPI
const kpis = computed(() => [
  {
    title: "設備總數",
    value: equipmentStore.totalEquipments,
    icon: "mdi-tools",
    iconColor: "primary",
    class: "total",
    textClass: "text-white",
  },
  {
    title: "正常運作",
    value: equipmentStore.activeCount,
    icon: "mdi-check-circle-outline",
    iconColor: "success",
    class: "active",
    textClass: "text-white",
  },
  {
    title: "維修中",
    value: equipmentStore.maintenanceCount,
    icon: "mdi-wrench-clock",
    iconColor: "orange",
    class: "maintenance",
    textClass: "text-white",
  },
  {
    title: "故障",
    value: equipmentStore.brokenCount,
    icon: "mdi-alert-circle-outline",
    iconColor: "error",
    class: "broken",
    textClass: "text-white",
  },
]);

// 刪除
const deleteDialog = ref(false);
const selectedEquipment = ref(null);

const confirmDelete = (item) => {
  selectedEquipment.value = item;
  deleteDialog.value = true;
};

const handleDelete = async () => {
  await equipmentStore.deleteEquipment(selectedEquipment.value.id);
  deleteDialog.value = false;
  selectedEquipment.value = null;
};

const getStatusText = (status) =>
  ({ active: "正常", maintenance: "維修中", broken: "故障" })[status] || status;

const getStatusColor = (status) =>
  ({ active: "success", maintenance: "warning", broken: "error" })[status] ||
  "default";
</script>

<style scoped>
.equipment-card {
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.equipment-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2) !important;
}

.total {
  background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important;
  color: white;
}
.active {
  background: linear-gradient(135deg, #166534, #4ade80) !important;
  color: white;
}
.maintenance {
  background: linear-gradient(135deg, #b45309, #f59e0b) !important;
  color: white;
}
.broken {
  background: linear-gradient(135deg, #991b1b, #f87171) !important;
  color: white;
}
</style>