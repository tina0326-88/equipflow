import api from "./api";

const repairService = {
  // 取得報修列表
  getAll(params = {}) {
    return api.get("/repairs", { params });
  },

  // 取得單一報修單
  getById(id) {
    return api.get(`/repairs/${id}`);
  },

  // 新增報修單
  create(data) {
    return api.post("/repairs", data);
  },

  // 更新報修單
  update(id, data) {
    return api.put(`/repairs/${id}`, data);
  },

  // 更新報修狀態
  updateStatus(id, status) {
    return api.patch(`/repairs/${id}/status`, { status });
  },

  // 刪除報修單
  delete(id) {
    return api.delete(`/repairs/${id}`);
  },

  // 取得報修紀錄
  getLogs(id) {
    return api.get(`/repairs/${id}/logs`);
  },
};

export default repairService;