<template>
  <div>
    <!-- 頂部導覽 -->
    <div class="d-flex align-center mb-8">
      <v-btn icon="mdi-arrow-left" variant="text" class="mr-3" to="/repairs" />
      <h2 class="text-h4 font-weight-bold">報修單詳細資訊</h2>
      <v-spacer />
      <v-btn
        v-if="!isEditing"
        color="primary"
        prepend-icon="mdi-pencil"
        variant="outlined"
        class="mr-3"
        @click="startEdit"
      >
        編輯
      </v-btn>
      <v-btn
        v-if="isEditing"
        color="success"
        prepend-icon="mdi-content-save"
        variant="flat"
        class="mr-3"
        :loading="repairStore.loading"
        @click="handleSave"
      >
        儲存
      </v-btn>
      <v-btn
        v-if="isEditing"
        variant="outlined"
        class="mr-3"
        @click="cancelEdit"
      >
        取消
      </v-btn>
      <v-btn
        color="error"
        prepend-icon="mdi-delete"
        variant="outlined"
        @click="deleteDialog = true"
      >
        刪除
      </v-btn>
    </div>

    <!-- 載入中 -->
    <div v-if="repairStore.loading && !repair" class="text-center py-16">
      <v-progress-circular indeterminate color="primary" size="56" />
      <div class="mt-4 text-disabled">載入中...</div>
    </div>

    <!-- 找不到資料 -->
    <div v-else-if="!repair" class="text-center py-16">
      <v-icon size="64" color="disabled">mdi-file-alert-outline</v-icon>
      <div class="mt-4 text-h6 text-disabled">找不到此報修單</div>
      <v-btn class="mt-6" to="/repairs" variant="outlined">返回列表</v-btn>
    </div>

    <template v-else>
      <v-row>
        <!-- 左欄：基本資訊 -->
        <v-col cols="12" md="8">
          <!-- 基本資訊卡片 -->
          <v-card class="detail-card pa-6 mb-6" elevation="2">
            <div class="d-flex align-center mb-6">
              <v-icon color="primary" class="mr-2"
                >mdi-information-outline</v-icon
              >
              <span class="text-h6 font-weight-bold">基本資訊</span>
            </div>

            <v-row>
              <!-- 標題 -->
              <v-col cols="12">
                <div class="text-caption text-disabled mb-1">報修單標題</div>
                <div v-if="!isEditing" class="text-body-1 font-weight-medium">
                  {{ repair.title }}
                </div>
                <v-text-field
                  v-else
                  v-model="editForm.title"
                  variant="outlined"
                  density="comfortable"
                  :rules="[rules.required]"
                  hide-details="auto"
                />
              </v-col>

              <!-- 狀態 -->
              <v-col cols="12" sm="6">
                <div class="text-caption text-disabled mb-1">報修狀態</div>
                <v-chip
                  v-if="!isEditing"
                  :color="getRepairStatusColor(repair.status)"
                  label
                >
                  {{ formatRepairStatus(repair.status) }}
                </v-chip>
                <v-select
                  v-else
                  v-model="editForm.status"
                  :items="statusOptions"
                  item-title="label"
                  item-value="value"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                />
              </v-col>

              <!-- 優先等級 -->
              <v-col cols="12" sm="6">
                <div class="text-caption text-disabled mb-1">優先等級</div>
                <v-chip
                  v-if="!isEditing"
                  :color="getPriorityColor(repair.priority)"
                  variant="outlined"
                  label
                >
                  {{ formatPriority(repair.priority) }}
                </v-chip>
                <v-select
                  v-else
                  v-model="editForm.priority"
                  :items="priorityOptions"
                  item-title="label"
                  item-value="value"
                  variant="outlined"
                  density="comfortable"
                  hide-details
                />
              </v-col>

              <!-- 問題描述 -->
              <v-col cols="12">
                <div class="text-caption text-disabled mb-1">問題描述</div>
                <div
                  v-if="!isEditing"
                  class="text-body-2 pa-3 rounded"
                  style="background: rgba(0, 0, 0, 0.04)"
                >
                  {{ repair.description }}
                </div>
                <v-textarea
                  v-else
                  v-model="editForm.description"
                  variant="outlined"
                  rows="4"
                  :rules="[rules.required]"
                  hide-details="auto"
                />
              </v-col>
            </v-row>
          </v-card>

          <!-- 報修紀錄卡片 -->
          <v-card class="detail-card pa-6" elevation="2">
            <div class="d-flex align-center mb-6">
              <v-icon color="primary" class="mr-2">mdi-history</v-icon>
              <span class="text-h6 font-weight-bold">報修紀錄</span>
            </div>

            <div
              v-if="logs.length === 0"
              class="text-center py-8 text-disabled"
            >
              <v-icon size="40" class="mb-2"
                >mdi-clipboard-text-off-outline</v-icon
              >
              <div>尚無操作紀錄</div>
            </div>

            <v-timeline v-else density="compact" side="end">
              <v-timeline-item
                v-for="log in logs"
                :key="log.id"
                dot-color="primary"
                size="small"
              >
                <template #opposite>
                  <div class="text-caption text-disabled">
                    {{ formatDateTime(log.created_at) }}
                  </div>
                </template>
                <v-card class="pa-3" variant="outlined" rounded="lg">
                  <div class="text-body-2 font-weight-bold mb-1">
                    {{ log.action }}
                  </div>
                  <div class="text-caption text-medium-emphasis">
                    {{ log.note }}
                  </div>
                </v-card>
              </v-timeline-item>
            </v-timeline>
          </v-card>
        </v-col>

        <!-- 右欄：關聯資訊 -->
        <v-col cols="12" md="4">
          <!-- 設備資訊卡片 -->
          <v-card class="detail-card pa-6 mb-6" elevation="2">
            <div class="d-flex align-center mb-4">
              <v-icon color="primary" class="mr-2">mdi-tools</v-icon>
              <span class="text-h6 font-weight-bold">關聯設備</span>
            </div>

            <div v-if="!isEditing">
              <div v-if="relatedEquipment">
                <v-list-item class="px-0">
                  <template #prepend>
                    <v-icon color="teal" class="mr-2">mdi-wrench-cog</v-icon>
                  </template>
                  <v-list-item-title class="font-weight-medium">
                    {{ relatedEquipment.name }}
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    {{ relatedEquipment.type }}
                  </v-list-item-subtitle>
                </v-list-item>

                <v-divider class="my-3" />

                <div class="info-row">
                  <span class="text-caption text-disabled">位置</span>
                  <span class="text-body-2">{{
                    relatedEquipment.location
                  }}</span>
                </div>
                <div class="info-row">
                  <span class="text-caption text-disabled">序號</span>
                  <span class="text-body-2">{{
                    relatedEquipment.serial_number
                  }}</span>
                </div>
                <div class="info-row">
                  <span class="text-caption text-disabled">設備狀態</span>
                  <v-chip
                    :color="getEquipmentStatusColor(relatedEquipment.status)"
                    size="x-small"
                    label
                  >
                    {{ formatEquipmentStatus(relatedEquipment.status) }}
                  </v-chip>
                </div>
              </div>
              <div v-else class="text-disabled text-caption">無關聯設備</div>
            </div>

            <v-select
              v-else
              v-model="editForm.equipment_id"
              :items="equipmentStore.equipments"
              item-title="name"
              item-value="id"
              label="選擇設備"
              variant="outlined"
              density="comfortable"
              hide-details
            />
          </v-card>

          <!-- 時間資訊卡片 -->
          <v-card class="detail-card pa-6 mb-6" elevation="2">
            <div class="d-flex align-center mb-4">
              <v-icon color="primary" class="mr-2">mdi-clock-outline</v-icon>
              <span class="text-h6 font-weight-bold">時間資訊</span>
            </div>

            <div class="info-row">
              <span class="text-caption text-disabled">報修時間</span>
              <span class="text-body-2">{{
                formatDateTime(repair.reported_at)
              }}</span>
            </div>
            <div class="info-row">
              <span class="text-caption text-disabled">建立時間</span>
              <span class="text-body-2">{{
                formatDateTime(repair.created_at)
              }}</span>
            </div>
            <div class="info-row">
              <span class="text-caption text-disabled">完成時間</span>
              <span class="text-body-2">{{
                formatDateTime(repair.completed_at)
              }}</span>
            </div>
            <div class="info-row">
              <span class="text-caption text-disabled">處理時間</span>
              <span class="text-body-2">
                {{
                  calcProcessingHours(repair.reported_at, repair.completed_at)
                }}
              </span>
            </div>
          </v-card>

          <!-- 快速更新狀態卡片 -->
          <v-card class="detail-card pa-6" elevation="2">
            <div class="d-flex align-center mb-4">
              <v-icon color="primary" class="mr-2">mdi-update</v-icon>
              <span class="text-h6 font-weight-bold">快速更新狀態</span>
            </div>

            <v-btn
              v-for="option in statusOptions"
              :key="option.value"
              :color="option.color"
              :variant="repair.status === option.value ? 'flat' : 'outlined'"
              block
              class="mb-2"
              :disabled="repair.status === option.value"
              @click="handleUpdateStatus(repair.id, option.value)"
            >
              {{ option.label }}
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </template>

    <!-- 刪除確認 Dialog -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card rounded="lg">
        <v-card-title class="text-h6 pa-6">確認刪除</v-card-title>
        <v-card-text class="px-6 pb-4">
          確定要刪除「<strong>{{ repair?.title }}</strong
          >」嗎？此操作無法復原。
        </v-card-text>
        <v-card-actions class="pa-6 pt-0">
          <v-spacer />
          <v-btn variant="text" @click="deleteDialog = false">取消</v-btn>
          <v-btn color="error" variant="flat" @click="handleDelete"
            >確認刪除</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Snackbar -->
    <v-snackbar
      v-model="snackbar"
      :color="snackbarColor"
      timeout="3000"
      location="top"
    >
      <v-icon class="mr-2">mdi-check-circle</v-icon>
      {{ snackbarMessage }}
    </v-snackbar>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useRepairStore } from "@/stores/repair";
import { useEquipmentStore } from "@/stores/equipment";
import { useRepair } from "@/composable/useRepair";
import {
  formatDateTime,
  formatPriority,
  formatRepairStatus,
  formatEquipmentStatus,
  calcProcessingHours,
} from "@/utils/formatData";
import {
  getRepairStatusColor,
  getPriorityColor,
  getEquipmentStatusColor,
} from "@/utils/statusColor";

const route = useRoute();
const router = useRouter();
const repairStore = useRepairStore();
const equipmentStore = useEquipmentStore();

const {
  deleteDialog,
  snackbar,
  snackbarMessage,
  snackbarColor,
  handleUpdateStatus,
  showSnackbar,
} = useRepair();

// 資料
const repair = ref(null);
const logs = ref([]);
const isEditing = ref(false);
const editForm = ref({});
const formRef = ref(null);

const rules = {
  required: (v) => !!v || "此欄位為必填",
};

// 關聯設備
const relatedEquipment = computed(() =>
  equipmentStore.equipments.find((e) => e.id === repair.value?.equipment_id),
);

// 選項
const statusOptions = [
  { label: "待處理", value: "pending", color: "warning" },
  { label: "處理中", value: "processing", color: "info" },
  { label: "已完成", value: "done", color: "success" },
  { label: "取消", value: "cancelled", color: "default" },
];

const priorityOptions = [
  { label: "高", value: "high" },
  { label: "中", value: "medium" },
  { label: "低", value: "low" },
];

// 載入資料
onMounted(async () => {
  const id = route.params.id;
  await equipmentStore.fetchEquipments();
  repair.value = await repairStore.fetchRepairById(id);
  logs.value = await repairStore.fetchRepairLogs(id);
});

// 編輯
const startEdit = () => {
  editForm.value = { ...repair.value };
  isEditing.value = true;
};

const cancelEdit = () => {
  editForm.value = {};
  isEditing.value = false;
};

const handleSave = async () => {
  const updated = await repairStore.updateRepair(
    repair.value.id,
    editForm.value,
  );
  if (updated) {
    repair.value = { ...updated };
    isEditing.value = false;
    showSnackbar("報修單已成功更新", "success");
  }
};

// 刪除
const handleDelete = async () => {
  await repairStore.deleteRepair(repair.value.id);
  deleteDialog.value = false;
  showSnackbar("報修單已成功刪除", "success");
  setTimeout(() => router.push("/repairs"), 1000);
};
</script>

<style scoped>
.detail-card {
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.info-row:last-child {
  border-bottom: none;
}
</style>