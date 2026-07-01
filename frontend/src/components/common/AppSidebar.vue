<template>
  <v-navigation-drawer v-model="drawer" elevation="1">
    <!-- Logo 區 -->
    <div class="pa-4 d-flex align-center">
      <span class="text-h6 font-weight-bold text-red">EquipFlow</span>
    </div>

    <v-divider />

    <!-- 導覽選單 -->
    <v-list nav density="comfortable" class="mt-2">
      <v-list-item
        v-for="item in navItems"
        :key="item.title"
        :to="item.to"
        :prepend-icon="item.icon"
        :title="item.title"
        rounded="lg"
        class="mb-1"
        active-color="red"
      />
    </v-list>

    <!-- 底部版本資訊 -->
    <template #append>
      <v-divider />
      <div class="pa-4 text-caption text-disabled text-center">
        EquipFlow v1.0.0
      </div>
    </template>
  </v-navigation-drawer>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["update:modelValue"]);

const drawer = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const navItems = [
  {
    title: "儀表板",
    to: "/",
  },
  {
    title: "報修管理",
    to: "/repairs",
  },
  {
    title: "設備管理",
    to: "/equipment",
  },
];
</script>