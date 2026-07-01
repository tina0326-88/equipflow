<template>
  <v-app-bar elevation="2" color="red">
    <v-app-bar-nav-icon @click="$emit('toggle-sidebar')" />

    <v-app-bar-title>
      <span class="text-caption ml-2" style="opacity: 0.8"
        >設備報修管理系統</span
      >
    </v-app-bar-title>

    <template #append>
      <v-btn
        icon="mdi-bell-outline"
        variant="text"
        color="white"
        class="mr-1"
      />
      <v-avatar color="white" size="36" class="mr-3" style="cursor: pointer">
        <span class="text-caption font-weight-bold text-error">管理</span>
      </v-avatar>
    </template>
  </v-app-bar>
</template>

<script setup>
defineEmits(["toggle-sidebar"]);
</script>