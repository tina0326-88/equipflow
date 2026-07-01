import api from "./api";

const equipmentService = {
  // 取得設備列表
  getAll(params = {}) {
    return api.get("/equipment", { params });
  },

  // 取得單一設備
  getById(id) {
    return api.get(`/equipment/${id}`);
  },

  // 新增設備
  create(data) {
    return api.post("/equipment", data);
  },

  // 更新設備
  update(id, data) {
    return api.put(`/equipment/${id}`, data);
  },

  // 刪除設備
  delete(id) {
    return api.delete(`/equipment/${id}`);
  },
};

export default equipmentService;