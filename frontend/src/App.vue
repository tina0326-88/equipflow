<template>
  <v-app>
    <AppNavbar @toggle-sidebar="drawer = !drawer" />
    <AppSidebar v-model="drawer" />

    <v-main>
      <v-container fluid class="pa-6">
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from "vue";
import AppNavbar from "@/components/common/AppNavbar.vue";
import AppSidebar from "@/components/common/AppSidebar.vue";

const drawer = ref(true);
</script>