/**
 * 報修狀態 → Vuetify color
 */
export function getRepairStatusColor(status) {
  return (
    {
      pending: "warning",
      processing: "info",
      done: "success",
      completed: "success",
      cancelled: "default",
    }[status] || "default"
  );
}

/**
 * 優先等級 → Vuetify color
 */
export function getPriorityColor(priority) {
  return (
    { high: "error", medium: "warning", low: "success" }[priority] || "default"
  );
}

/**
 * 設備狀態 → Vuetify color
 */
export function getEquipmentStatusColor(status) {
  return (
    { active: "success", maintenance: "warning", broken: "error" }[status] ||
    "default"
  );
}