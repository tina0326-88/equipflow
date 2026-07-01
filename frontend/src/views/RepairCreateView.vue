<template>
  <div>
    <div class="d-flex align-center mb-8">
      <v-btn icon="mdi-arrow-left" variant="text" class="mr-3" to="/repairs" />
      <h2 class="text-h4 font-weight-bold">新增報修單</h2>
    </div>

    <v-card class="repair-card pa-8" elevation="2" max-width="800">
      <v-form ref="formRef" @submit.prevent="handleSubmit">
        <v-row>
          <!-- 標題 -->
          <v-col cols="12">
            <v-text-field
              v-model="form.title"
              label="報修單標題"
              placeholder="請簡述問題"
              variant="outlined"
              :rules="[rules.required]"
              prepend-inner-icon="mdi-format-title"
            />
          </v-col>

          <!-- 設備選擇 -->
          <v-col cols="12" md="6">
            <v-select
              v-model="form.equipment_id"
              :items="equipmentStore.equipments"
              item-title="name"
              item-value="id"
              label="選擇設備"
              variant="outlined"
              :rules="[rules.required]"
              prepend-inner-icon="mdi-tools"
            />
          </v-col>

          <!-- 優先等級 -->
          <v-col cols="12" md="6">
            <v-select
              v-model="form.priority"
              :items="priorityOptions"
              item-title="label"
              item-value="value"
              label="優先等級"
              variant="outlined"
              :rules="[rules.required]"
              prepend-inner-icon="mdi-flag-outline"
            />
          </v-col>

          <!-- 問題描述 -->
          <v-col cols="12">
            <v-textarea
              v-model="form.description"
              label="問題描述"
              placeholder="請詳細描述問題情況、發生時間、影響範圍..."
              variant="outlined"
              rows="5"
              :rules="[rules.required]"
              prepend-inner-icon="mdi-text"
            />
          </v-col>

          <!-- 送出按鈕 -->
          <v-col cols="12" class="d-flex justify-end gap-3">
            <v-btn variant="outlined" to="/repairs" class="mr-3">取消</v-btn>
            <v-btn
              type="submit"
              color="red"
              elevation="2"
              :loading="repairStore.loading"
              prepend-icon="mdi-send"
            >
              送出報修單
            </v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-card>

    <!-- 送出成功 Snackbar -->
    <v-snackbar
      v-model="snackbar"
      color="success"
      timeout="3000"
      location="top"
    >
      <v-icon class="mr-2">mdi-check-circle</v-icon>
      報修單已成功送出！
    </v-snackbar>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useRepairStore } from "@/stores/repair";
import { useEquipmentStore } from "@/stores/equipment";

const router = useRouter();
const repairStore = useRepairStore();
const equipmentStore = useEquipmentStore();

onMounted(() => {
  equipmentStore.fetchEquipments();
});

const formRef = ref(null);
const snackbar = ref(false);

const form = ref({
  title: "",
  equipment_id: null,
  priority: null,
  description: "",
  reported_by: 1, // 之後接登入系統再動態帶入
});

const priorityOptions = [
  { label: "高", value: "high" },
  { label: "中", value: "medium" },
  { label: "低", value: "low" },
];

const rules = {
  required: (v) => !!v || "此欄位為必填",
};

const handleSubmit = async () => {
  const { valid } = await formRef.value.validate();
  if (!valid) return;

  await repairStore.createRepair(form.value);

  snackbar.value = true;
  setTimeout(() => router.push("/repairs"), 1500);
};
</script>

<style scoped>
.repair-card {
  border-radius: 16px;
}
</style>