/**
 * 日期格式化：2024-01-15T08:30:00 → 2024/01/15
 */
export function formatDate(dateStr) {
  if (!dateStr) return "-";
  const date = new Date(dateStr);
  if (isNaN(date)) return "-";
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, "0");
  const d = String(date.getDate()).padStart(2, "0");
  return `${y}/${m}/${d}`;
}

/**
 * 日期時間格式化：2024-01-15T08:30:00 → 2024/01/15 08:30
 */
export function formatDateTime(dateStr) {
  if (!dateStr) return "-";
  const date = new Date(dateStr);
  if (isNaN(date)) return "-";
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, "0");
  const d = String(date.getDate()).padStart(2, "0");
  const hh = String(date.getHours()).padStart(2, "0");
  const mm = String(date.getMinutes()).padStart(2, "0");
  return `${y}/${m}/${d} ${hh}:${mm}`;
}

/**
 * 計算距今時間：幾分鐘前、幾小時前、幾天前
 */
export function formatRelativeTime(dateStr) {
  if (!dateStr) return "-";
  const date = new Date(dateStr);
  if (isNaN(date)) return "-";
  const diff = (Date.now() - date) / 1000; // 秒
  if (diff < 60) return "剛剛";
  if (diff < 3600) return `${Math.floor(diff / 60)} 分鐘前`;
  if (diff < 86400) return `${Math.floor(diff / 3600)} 小時前`;
  return `${Math.floor(diff / 86400)} 天前`;
}

/**
 * 計算處理時間（小時）：reported_at → completed_at
 */
export function calcProcessingHours(reportedAt, completedAt) {
  if (!reportedAt || !completedAt) return "-";
  const diff =
    (new Date(completedAt) - new Date(reportedAt)) / (1000 * 60 * 60);
  return `${Math.round(diff)} 小時`;
}

/**
 * 優先等級文字
 */
export function formatPriority(priority) {
  return { high: "高", medium: "中", low: "低" }[priority] || priority;
}

/**
 * 報修狀態文字
 */
export function formatRepairStatus(status) {
  return (
    {
      pending: "待處理",
      processing: "處理中",
      done: "已完成",
      completed: "已完成",
      cancelled: "取消",
    }[status] || status
  );
}

/**
 * 設備狀態文字
 */
export function formatEquipmentStatus(status) {
  return (
    { active: "正常", maintenance: "維修中", broken: "故障" }[status] || status
  );
}