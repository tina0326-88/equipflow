<template>
  <v-card
    :elevation="elevation"
    :class="['base-card', { 'base-card--hover': hover }]"
    :color="color"
    v-bind="$attrs"
  >
    <!-- 標題區 -->
    <div
      v-if="title || $slots.header"
      class="d-flex justify-space-between align-center px-6 pt-6 pb-2"
    >
      <div class="d-flex align-center">
        <v-icon v-if="icon" :color="iconColor" class="mr-2">{{ icon }}</v-icon>
        <span class="text-h6 font-weight-bold">{{ title }}</span>
      </div>
      <slot name="header" />
    </div>

    <v-divider v-if="title || $slots.header" class="mx-6 mt-2" />

    <!-- 內容區 -->
    <div :class="['pa-6', contentClass]">
      <slot />
    </div>

    <!-- 底部區 -->
    <div v-if="$slots.footer" class="px-6 pb-6">
      <v-divider class="mb-4" />
      <slot name="footer" />
    </div>
  </v-card>
</template>

<script setup>
defineProps({
  // 卡片標題
  title: {
    type: String,
    default: "",
  },
  // 標題左側圖示
  icon: {
    type: String,
    default: "",
  },
  // 圖示顏色
  iconColor: {
    type: String,
    default: "primary",
  },
  // 卡片背景色
  color: {
    type: String,
    default: undefined,
  },
  // 陰影層級
  elevation: {
    type: Number,
    default: 2,
  },
  // 是否有 hover 效果
  hover: {
    type: Boolean,
    default: true,
  },
  // 內容區額外 class
  contentClass: {
    type: String,
    default: "",
  },
});
</script>

<style scoped>
.base-card {
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.base-card--hover:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2) !important;
}
</style>
