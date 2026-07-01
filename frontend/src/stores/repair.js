import { defineStore } from "pinia";
import { ref, computed } from "vue";
import repairService from "@/services/repairService";

// Mock 資料（後端未完成時使用）
const mockLogs = [
  {
    id: 1,
    repair_id: 1,
    user_id: 1,
    action: "建立報修單",
    note: "空調噪音問題，需盡快處理",
    created_at: "2024-01-15T08:30:00",
  },
  {
    id: 2,
    repair_id: 1,
    user_id: 3,
    action: "狀態變更",
    note: "已指派維修人員前往查看",
    created_at: "2024-01-15T10:00:00",
  },
  {
    id: 3,
    repair_id: 2,
    user_id: 2,
    action: "建立報修單",
    note: "監控畫面出現雜訊，影響安全監控",
    created_at: "2024-01-14T10:00:00",
  },
  {
    id: 4,
    repair_id: 2,
    user_id: 3,
    action: "指派處理",
    note: "已安排技術人員於明日處理",
    created_at: "2024-01-14T14:00:00",
  },
];

const mockRepairs = [
  {
    id: 1,
    title: "空調異常噪音",
    description: "3樓會議室空調運作時有異常噪音",
    status: "pending",
    priority: "high",
    equipment_id: 1,
    reported_by: 1,
    assigned_to: null,
    reported_at: "2024-01-15T08:30:00",
    completed_at: null,
    created_at: "2024-01-15T08:30:00",
  },
  {
    id: 2,
    title: "監控攝影機畫面異常",
    description: "B1停車場監控畫面出現雜訊",
    status: "processing",
    priority: "medium",
    equipment_id: 2,
    reported_by: 2,
    assigned_to: 3,
    reported_at: "2024-01-14T10:00:00",
    completed_at: null,
    created_at: "2024-01-14T10:00:00",
  },
  {
    id: 3,
    title: "消防灑水頭漏水",
    description: "5樓走廊消防灑水頭有漏水情況",
    status: "done",
    priority: "high",
    equipment_id: 3,
    reported_by: 1,
    assigned_to: 3,
    reported_at: "2024-01-13T09:00:00",
    completed_at: "2024-01-14T16:00:00",
    created_at: "2024-01-13T09:00:00",
  },
  {
    id: 4,
    title: "電梯按鈕故障",
    description: "2號電梯3樓按鈕無反應",
    status: "pending",
    priority: "high",
    equipment_id: 4,
    reported_by: 2,
    assigned_to: null,
    reported_at: "2024-01-15T11:00:00",
    completed_at: null,
    created_at: "2024-01-15T11:00:00",
  },
  {
    id: 5,
    title: "網路設備斷線",
    description: "4樓網路交換器頻繁斷線",
    status: "processing",
    priority: "medium",
    equipment_id: 5,
    reported_by: 3,
    assigned_to: 2,
    reported_at: "2024-01-15T13:00:00",
    completed_at: null,
    created_at: "2024-01-15T13:00:00",
  },
];

export const useRepairStore = defineStore("repair", () => {
  // State
  const repairs = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const useMock = ref(true); // 切換 Mock 模式

  // Getters
  const totalRepairs = computed(() => repairs.value.length);

  const pendingCount = computed(
    () => repairs.value.filter((r) => r.status === "pending").length,
  );

  const completedCount = computed(
    () =>
      repairs.value.filter(
        (r) => r.status === "done" || r.status === "completed",
      ).length,
  );

  const processingCount = computed(
    () => repairs.value.filter((r) => r.status === "processing").length,
  );

  const todayNewCount = computed(() => {
    const today = new Date().toDateString();
    return repairs.value.filter(
      (r) => new Date(r.created_at).toDateString() === today,
    ).length;
  });

  const overdueCount = computed(() => {
    const now = new Date();
    return repairs.value.filter((r) => {
      if (r.status === "done" || r.status === "completed") return false;
      const reported = new Date(r.reported_at);
      const diffDays = (now - reported) / (1000 * 60 * 60 * 24);
      return diffDays > 3;
    }).length;
  });

  const avgProcessingTime = computed(() => {
    const done = repairs.value.filter(
      (r) =>
        (r.status === "done" || r.status === "completed") && r.completed_at,
    );
    if (!done.length) return 0;
    const total = done.reduce((sum, r) => {
      const diff =
        (new Date(r.completed_at) - new Date(r.reported_at)) / (1000 * 60 * 60);
      return sum + diff;
    }, 0);
    return Math.round(total / done.length);
  });

  const growthRate = computed(() => {
    return 12; // Mock 固定值，之後接 API 再計算
  });

  const latestRepairs = computed(() =>
    [...repairs.value]
      .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      .slice(0, 5)
      .map((r) => ({ ...r, createdAt: r.created_at?.slice(0, 10) })),
  );

  // Actions
  async function fetchRepairs(params = {}) {
    loading.value = true;
    error.value = null;
    try {
      if (useMock.value) {
        await new Promise((r) => setTimeout(r, 300));
        repairs.value = mockRepairs;
      } else {
        const res = await repairService.getAll(params);
        repairs.value = res.data || res;
      }
    } catch (err) {
      error.value = "載入報修資料失敗";
      console.error(err);
    } finally {
      loading.value = false;
    }
  }

  async function createRepair(data) {
    loading.value = true;
    try {
      if (useMock.value) {
        await new Promise((r) => setTimeout(r, 300));
        const newRepair = {
          ...data,
          id: repairs.value.length + 1,
          status: "pending",
          created_at: new Date().toISOString(),
          reported_at: new Date().toISOString(),
          completed_at: null,
        };
        repairs.value.unshift(newRepair);
        return newRepair;
      } else {
        const res = await repairService.create(data);
        repairs.value.unshift(res.data || res);
        return res;
      }
    } catch (err) {
      error.value = "新增報修單失敗";
      console.error(err);
    } finally {
      loading.value = false;
    }
  }

  async function updateStatus(id, status) {
    try {
      if (useMock.value) {
        const repair = repairs.value.find((r) => r.id === id);
        if (repair) {
          repair.status = status;
          if (status === "done") repair.completed_at = new Date().toISOString();
        }
      } else {
        await repairService.updateStatus(id, status);
        await fetchRepairs();
      }
    } catch (err) {
      error.value = "更新狀態失敗";
      console.error(err);
    }
  }

  async function deleteRepair(id) {
    try {
      if (useMock.value) {
        repairs.value = repairs.value.filter((r) => r.id !== id);
      } else {
        await repairService.delete(id);
        repairs.value = repairs.value.filter((r) => r.id !== id);
      }
    } catch (err) {
      error.value = "刪除報修單失敗";
      console.error(err);
    }
  }

  // 取得單筆報修單
  async function fetchRepairById(id) {
    loading.value = true;
    error.value = null;
    try {
      if (useMock.value) {
        await new Promise((r) => setTimeout(r, 300));
        return mockRepairs.find((r) => r.id === Number(id)) || null;
      } else {
        const res = await repairService.getById(id);
        return res.data || res;
      }
    } catch (err) {
      error.value = "載入報修單失敗";
      console.error(err);
      return null;
    } finally {
      loading.value = false;
    }
  }

  // 更新報修單
  async function updateRepair(id, data) {
    loading.value = true;
    try {
      if (useMock.value) {
        await new Promise((r) => setTimeout(r, 300));
        const index = repairs.value.findIndex((r) => r.id === Number(id));
        if (index !== -1) {
          repairs.value[index] = { ...repairs.value[index], ...data };
          return repairs.value[index];
        }
      } else {
        const res = await repairService.update(id, data);
        await fetchRepairs();
        return res.data || res;
      }
    } catch (err) {
      error.value = "更新報修單失敗";
      console.error(err);
    } finally {
      loading.value = false;
    }
  }

  // 取得報修紀錄
  async function fetchRepairLogs(id) {
    try {
      if (useMock.value) {
        await new Promise((r) => setTimeout(r, 300));
        return mockLogs.filter((l) => l.repair_id === Number(id));
      } else {
        const res = await repairService.getLogs(id);
        return res.data || res;
      }
    } catch (err) {
      error.value = "載入報修紀錄失敗";
      console.error(err);
      return [];
    }
  }

  return {
    repairs,
    loading,
    error,
    useMock,
    totalRepairs,
    pendingCount,
    completedCount,
    processingCount,
    todayNewCount,
    overdueCount,
    avgProcessingTime,
    growthRate,
    latestRepairs,
    fetchRepairs,
    createRepair,
    updateStatus,
    deleteRepair,
    fetchRepairById,
    updateRepair,
    fetchRepairLogs,
  };
});