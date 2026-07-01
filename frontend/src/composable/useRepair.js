import { ref } from "vue";
import { useRepairStore } from "@/stores/repair";
import { useRouter } from "vue-router";

export function useRepair() {
  const repairStore = useRepairStore();
  const router = useRouter();

  const deleteDialog = ref(false);
  const selectedRepair = ref(null);
  const snackbar = ref(false);
  const snackbarMessage = ref("");
  const snackbarColor = ref("success");

  // 開啟刪除確認
  const confirmDelete = (item) => {
    selectedRepair.value = item;
    deleteDialog.value = true;
  };

  // 執行刪除
  const handleDelete = async () => {
    await repairStore.deleteRepair(selectedRepair.value.id);
    deleteDialog.value = false;
    selectedRepair.value = null;
    showSnackbar("報修單已成功刪除", "success");
  };

  // 更新狀態
  const handleUpdateStatus = async (id, status) => {
    await repairStore.updateStatus(id, status);
    showSnackbar("狀態已更新", "success");
  };

  // 前往新增頁
  const goToCreate = () => {
    router.push("/repairs/create");
  };

  // 前往詳細頁
  const goToDetail = (id) => {
    router.push(`/repairs/${id}`);
  };

  // 顯示 Snackbar
  const showSnackbar = (message, color = "success") => {
    snackbarMessage.value = message;
    snackbarColor.value = color;
    snackbar.value = true;
  };

  return {
    repairStore,
    deleteDialog,
    selectedRepair,
    snackbar,
    snackbarMessage,
    snackbarColor,
    confirmDelete,
    handleDelete,
    handleUpdateStatus,
    goToCreate,
    goToDetail,
    showSnackbar,
  };
}