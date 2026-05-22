<template>
  <component :is="layoutComponent">
    <!-- The layout component will render <router-view> inside itself -->
  </component>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from './stores/useAuthStore';
import { useThemeStore } from './stores/useThemeStore';
import StorefrontLayout from './layouts/StorefrontLayout.vue';
import AdminLayout from './layouts/AdminLayout.vue';

const route = useRoute();
const authStore = useAuthStore();
const themeStore = useThemeStore();

// Dynamically resolve the layout based on route meta
const layoutComponent = computed(() => {
  if (route.meta.layout === 'admin') {
    return AdminLayout;
  }
  return StorefrontLayout;
});

onMounted(() => {
  authStore.fetchUser();
  themeStore.initTheme();
});
</script>
