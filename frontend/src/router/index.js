import { createRouter, createWebHistory } from "vue-router";
import DashboardView from "@/views/DashboardView.vue";
import RepairView from "@/views/RepairView.vue";
import RepairCreateView from "@/views/RepairCreateView.vue";
import RepairDetailView from "@/views/RepairDetailView.vue";
import EquipmentView from "@/views/EquipmentView.vue";

const routes = [
  {
    path: "/",
    name: "Dashboard",
    component: DashboardView,
  },
  {
    path: "/repairs",
    name: "Repairs",
    component: RepairView,
  },
  {
    path: "/repairs/create",
    name: "RepairCreate",
    component: RepairCreateView,
  },
  {
    path: "/repairs/:id",
    name: "RepairDetail",
    component: RepairDetailView,
  },
  {
    path: "/equipment",
    name: "Equipment",
    component: EquipmentView,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;