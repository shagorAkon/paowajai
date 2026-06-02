<template>
  <div class="flex h-screen bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white font-sans overflow-hidden transition-colors duration-300">
    
    <!-- Mobile Sidebar Backdrop -->
    <div 
      v-if="sidebarOpen" 
      class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-20 md:hidden transition-opacity"
      @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside :class="[
      'bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col transition-transform duration-300 z-30 shadow-sm fixed md:relative h-full',
      sidebarOpen ? 'w-64 translate-x-0' : 'w-64 -translate-x-full md:w-20 md:translate-x-0'
    ]">
      <!-- Logo area -->
      <div class="h-16 flex items-center justify-center border-b border-slate-200 dark:border-slate-700">
        <router-link to="/admin" class="flex items-center gap-2 overflow-hidden">
          <svg class="w-8 h-8 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          <span v-if="sidebarOpen" class="font-black text-xl tracking-tighter text-gradient animate-fade-in whitespace-nowrap">PAOWAZAY</span>
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
          
          <!-- Tooltip for collapsed mode (Desktop Only) -->
          <div v-if="!sidebarOpen" class="hidden md:block absolute left-14 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none z-50 whitespace-nowrap transition-opacity">
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
          <!-- Notifications -->
          <div class="relative">
            <button @click="showNotifications = !showNotifications" class="relative p-2 text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
              <span v-if="unreadCount > 0" class="absolute top-1 right-1.5 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
              </span>
            </button>

            <!-- Notifications Dropdown -->
            <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 z-50 overflow-hidden">
              <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-bold text-sm">Notifications</h3>
                <span class="bg-primary-100 text-primary-700 dark:bg-primary-900/50 dark:text-primary-400 text-xs px-2 py-0.5 rounded-full font-bold">{{ unreadCount }} new</span>
              </div>
              <div class="max-h-80 overflow-y-auto">
                <div v-if="notifications.length === 0" class="p-4 text-center text-slate-500 text-sm">No new notifications</div>
                <div v-for="notif in notifications" :key="notif.id" @click="markAsRead(notif)" class="p-4 border-b border-slate-100 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" :class="{'bg-primary-50/50 dark:bg-primary-900/10': !notif.read_at}">
                  <p class="text-sm text-slate-800 dark:text-slate-200">{{ notif.data.message }}</p>
                  <p class="text-xs text-slate-500 mt-1 font-medium">Order: {{ notif.data.order_number }}</p>
                  <p class="text-xs text-slate-400 mt-1">{{ new Date(notif.created_at).toLocaleString() }}</p>
                </div>
              </div>
            </div>
          </div>

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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/useAuthStore';
import api from '../utils/api';
import { 
  HomeIcon, 
  ShoppingBagIcon, 
  ClipboardDocumentListIcon, 
  UsersIcon, 
  Cog8ToothIcon,
  ArchiveBoxIcon,
  TagIcon,
  MegaphoneIcon,
  DocumentChartBarIcon,
  PhotoIcon
} from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

// Default to hidden on mobile, open on desktop
const sidebarOpen = ref(window.innerWidth >= 768);
const showNotifications = ref(false);
const unreadCount = ref(0);
const notifications = ref([]);
let pollInterval = null;

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

const fetchNotifications = async () => {
  try {
    const { data } = await api.get('/admin/dashboard/notifications');
    unreadCount.value = data.unread_count;
    notifications.value = data.notifications;
  } catch (err) {
    console.error('Failed to fetch notifications', err);
  }
};

const markAsRead = async (notif) => {
  if (notif.read_at) return; // already read
  try {
    await api.post(`/admin/dashboard/notifications/${notif.id}/read`);
    notif.read_at = new Date().toISOString();
    unreadCount.value = Math.max(0, unreadCount.value - 1);
  } catch (err) {
    console.error('Failed to mark as read', err);
  }
};

onMounted(() => {
  fetchNotifications();
  // Poll every 30 seconds
  pollInterval = setInterval(fetchNotifications, 30000);
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
});

const menuItems = [
  { name: 'Dashboard', path: '/admin/dashboard', icon: HomeIcon },
  { name: 'Categories', path: '/admin/categories', icon: TagIcon },
  { name: 'Products', path: '/admin/products', icon: ShoppingBagIcon },
  { name: 'Orders', path: '/admin/orders', icon: ClipboardDocumentListIcon },
  { name: 'Reports', path: '/admin/reports', icon: DocumentChartBarIcon },
  { name: 'Inventory', path: '/admin/inventory', icon: ArchiveBoxIcon, role: 'Super Admin' },
  { name: 'Marketing', path: '/admin/marketing', icon: MegaphoneIcon },
  { name: 'Customers', path: '/admin/customers', icon: UsersIcon },
  { name: 'Customize Home Page', path: '/admin/customize-home', icon: PhotoIcon, role: 'Super Admin' },
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
