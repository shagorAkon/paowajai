<template>
  <div class="flex h-screen bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white font-sans overflow-hidden transition-colors duration-300">
    
    <!-- Sidebar -->
    <aside :class="['bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col transition-all duration-300 z-20 shadow-sm', sidebarOpen ? 'w-64' : 'w-20']">
      <!-- Logo area -->
      <div class="h-16 flex items-center justify-center border-b border-slate-200 dark:border-slate-700">
        <router-link to="/admin" class="flex items-center gap-2 overflow-hidden">
          <svg class="w-8 h-8 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          <span v-if="sidebarOpen" class="font-black text-xl tracking-tighter text-gradient animate-fade-in whitespace-nowrap">PAOWAJAI</span>
        </router-link>
      </div>

      <!-- Nav Links -->
      <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
        <router-link 
          v-for="item in menuItems" 
          :key="item.path"
          :to="item.path" 
          v-show="!item.role || hasRole(item.role)"
          class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors group relative"
          :class="$route.path === item.path ? 'bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400' : 'hover:bg-slate-100 text-slate-600 dark:text-slate-400 dark:hover:bg-slate-700'"
        >
          <component :is="item.icon" class="w-5 h-5 shrink-0" />
          <span v-if="sidebarOpen" class="font-medium whitespace-nowrap">{{ item.name }}</span>
          
          <!-- Tooltip for collapsed mode -->
          <div v-if="!sidebarOpen" class="absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap transition-opacity">
            {{ item.name }}
          </div>
        </router-link>
      </nav>

      <!-- Bottom Profile -->
      <div class="p-4 border-t border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400 flex items-center justify-center font-bold shrink-0">
            {{ userInitials }}
          </div>
          <div v-if="sidebarOpen" class="flex-1 overflow-hidden">
            <p class="text-sm font-semibold truncate">{{ authStore.user?.name }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ authStore.user?.email }}</p>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
      <!-- Topbar -->
      <header class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-4 z-10 shadow-sm">
        <div class="flex items-center gap-4">
          <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-900 dark:hover:text-white p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </button>
          <h1 class="text-xl font-bold hidden sm:block">{{ routeName }}</h1>
        </div>

        <div class="flex items-center gap-4">
          <a href="/" target="_blank" class="text-sm font-medium text-slate-500 hover:text-primary-500 hidden sm:flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            View Store
          </a>
          <button @click="handleLogout" class="flex items-center gap-2 text-red-500 hover:text-red-700 font-medium text-sm px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span class="hidden sm:inline">Logout</span>
          </button>
        </div>
      </header>

      <!-- Main Scrollable Area -->
      <main class="flex-1 overflow-auto bg-slate-50 dark:bg-slate-900 p-4 sm:p-6 lg:p-8">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/useAuthStore';
import { 
  HomeIcon, 
  ShoppingBagIcon, 
  ClipboardDocumentListIcon, 
  UsersIcon, 
  Cog8ToothIcon,
  ArchiveBoxIcon,
  TagIcon,
  MegaphoneIcon
} from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const sidebarOpen = ref(true);

const routeName = computed(() => {
  return route.name ? route.name.toString().replace('admin.', '').replace(/\b\w/g, l => l.toUpperCase()) : 'Dashboard';
});

const userInitials = computed(() => {
  return authStore.user?.name?.substring(0, 2).toUpperCase() || 'AD';
});

const hasRole = (role) => {
  if (!authStore.user || !authStore.user.roles) return false;
  return authStore.user.roles.some(r => r.name === role || r.name === 'Super Admin');
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

const menuItems = [
  { name: 'Dashboard', path: '/admin/dashboard', icon: HomeIcon },
  { name: 'Categories', path: '/admin/categories', icon: TagIcon },
  { name: 'Products', path: '/admin/products', icon: ShoppingBagIcon },
  { name: 'Orders', path: '/admin/orders', icon: ClipboardDocumentListIcon },
  { name: 'Inventory', path: '/admin/inventory', icon: ArchiveBoxIcon, role: 'Super Admin' },
  { name: 'Marketing', path: '/admin/marketing', icon: MegaphoneIcon },
  { name: 'Customers', path: '/admin/customers', icon: UsersIcon },
  { name: 'Settings', path: '/admin/settings', icon: Cog8ToothIcon, role: 'Super Admin' },
];
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
