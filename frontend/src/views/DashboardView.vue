<template>
  <div>
    <h2 class="mb-8 text-h4 font-weight-bold">儀表板</h2>

    <!-- KPI 卡片區 -->
    <v-row class="mb-10">
      <v-col
        v-for="kpi in kpis"
        :key="kpi.title"
        cols="12"
        sm="6"
        md="4"
        lg="3"
      >
        <v-card
          class="dashboard-card pa-6 text-center"
          :class="kpi.class"
          elevation="6"
        >
          <v-icon :color="kpi.iconColor" size="52" class="mb-4">{{
            kpi.icon
          }}</v-icon>

          <div class="text-body-1 mb-2 text-medium-emphasis">
            {{ kpi.title }}
          </div>

          <div class="text-h3 font-weight-bold" :class="kpi.textClass">
            {{ kpi.value }}
            <span v-if="kpi.unit" class="text-h5">{{ kpi.unit }}</span>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <!-- 最新案件 -->
    <v-card class="dashboard-card pa-6">
      <div class="d-flex justify-space-between align-center mb-6">
        <div class="text-h6 font-weight-bold">最新案件</div>
        <v-btn
          variant="text"
          color="primary"
          to="/repairs"
          prepend-icon="mdi-arrow-right"
        >
          查看全部
        </v-btn>
      </div>

      <v-list density="comfortable" v-if="latestRepairs.length">
        <v-list-item
          v-for="item in latestRepairs"
          :key="item.id"
          :to="`/repairs/${item.id}`"
          class="mb-2"
        >
          <template #prepend>
            <v-icon color="warning" size="28" class="mr-4"
              >mdi-wrench-clock</v-icon
            >
          </template>

          <v-list-item-title class="font-weight-medium">{{
            item.title
          }}</v-list-item-title>

          <v-list-item-subtitle class="mt-1">
            <v-chip
              :color="getStatusColor(item.status)"
              size="small"
              class="mr-3"
            >
              {{ getStatusText(item.status) }}
            </v-chip>
            <span class="text-caption text-disabled">{{ item.createdAt }}</span>
          </v-list-item-subtitle>
        </v-list-item>
      </v-list>

      <div v-else class="text-center py-10 text-disabled">目前尚無報修案件</div>
    </v-card>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRepairStore } from "@/stores/repair";

const repairStore = useRepairStore();

onMounted(() => {
  repairStore.fetchRepairs();
});

// KPI 資料
const kpis = computed(() => [
  {
    title: "總案件",
    value: repairStore.totalRepairs,
    icon: "mdi-format-list-bulleted",
    iconColor: "primary",
    class: "total",
    textClass: "text-white",
  },
  {
    title: "待處理",
    value: repairStore.pendingCount,
    icon: "mdi-clock-outline",
    iconColor: "orange",
    class: "pending",
    textClass: "text-white",
  },
  {
    title: "已完成",
    value: repairStore.completedCount,
    icon: "mdi-check-circle-outline",
    iconColor: "success",
    class: "done",
    textClass: "text-white",
  },
  {
    title: "今日新增",
    value: repairStore.todayNewCount || 0,
    icon: "mdi-calendar-plus",
    iconColor: "info",
    class: "",
  },
  {
    title: "逾期案件",
    value: repairStore.overdueCount || 0,
    icon: "mdi-alert-circle",
    iconColor: "error",
    class: "",
    textClass: "text-error",
  },
  {
    title: "平均處理時間",
    value: repairStore.avgProcessingTime,
    unit: "小時",
    icon: "mdi-timer-outline",
    iconColor: "deep-orange",
    class: "",
  },
  {
    title: "設備總數",
    value: 86,
    icon: "mdi-tools",
    iconColor: "teal",
    class: "",
  },
  {
    title: "本週成長",
    value: `+${repairStore.growthRate}`,
    unit: "%",
    icon: "mdi-trending-up",
    iconColor: "success",
    class: "",
    textClass: "text-success",
  },
]);

const latestRepairs = computed(() => repairStore.latestRepairs);

const getStatusText = (status) =>
  ({
    pending: "待處理",
    processing: "處理中",
    done: "已完成",
    completed: "已完成",
  })[status] || status;

const getStatusColor = (status) =>
  ({
    pending: "warning",
    processing: "info",
    done: "success",
    completed: "success",
  })[status] || "default";
</script>

<style scoped>
.dashboard-card {
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
}

.dashboard-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3) !important;
}

/* KPI 卡片顏色 */
.total {
  background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important;
  color: white;
}
.pending {
  background: linear-gradient(135deg, #b45309, #f59e0b) !important;
  color: white;
}
.done {
  background: linear-gradient(135deg, #166534, #4ade80) !important;
  color: white;
}
</style>