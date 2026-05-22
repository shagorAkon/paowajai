<template>
  <div>
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Overview</h2>
      <p class="text-slate-500 dark:text-slate-400">Welcome to your admin dashboard.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.name" class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ stat.name }}</p>
          <span class="p-2 rounded-lg" :class="stat.bgColor">
            <component :is="stat.icon" class="w-5 h-5" :class="stat.textColor" />
          </span>
        </div>
        <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">{{ stat.value }}</p>
        <div class="mt-4 flex items-center text-sm">
          <span :class="stat.change > 0 ? 'text-green-500' : 'text-red-500'" class="font-medium flex items-center gap-1">
            <svg v-if="stat.change > 0" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            {{ Math.abs(stat.change) }}%
          </span>
          <span class="text-slate-500 ml-2">vs last month</span>
        </div>
      </div>
    </div>

    <!-- Recent Orders (Mock Table) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white">Recent Orders</h3>
        <router-link to="/admin/orders" class="text-sm font-medium text-primary-500 hover:text-primary-600">View All</router-link>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
          <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold">
            <tr>
              <th class="px-6 py-4">Order ID</th>
              <th class="px-6 py-4">Customer</th>
              <th class="px-6 py-4">Date</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Total</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            <tr v-if="recentOrders.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-slate-500">No recent orders</td>
            </tr>
            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <td class="px-6 py-4 font-medium">#{{ order.order_number }}</td>
              <td class="px-6 py-4">{{ order.customer_name }}</td>
              <td class="px-6 py-4 text-slate-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
              <td class="px-6 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize" :class="statusColor(order.status)">{{ order.status }}</span>
              </td>
              <td class="px-6 py-4 text-right font-bold">৳ {{ Number(order.total).toLocaleString('en-IN') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import api from '../../utils/api';
import { 
  CurrencyDollarIcon, 
  ShoppingBagIcon, 
  UsersIcon, 
  ChartBarIcon,
  ClockIcon,
  TruckIcon,
  CheckBadgeIcon
} from '@heroicons/vue/24/outline';

const rawStats = ref(null);
const recentOrders = ref([]);
const stats = ref([]);
let pollInterval = null;

const fetchDashboardData = async () => {
  try {
    const [statsRes, ordersRes] = await Promise.all([
      api.get('/admin/dashboard/stats'),
      api.get('/admin/dashboard/recent-orders')
    ]);

    const data = statsRes.data;
    recentOrders.value = ordersRes.data;

    stats.value = [
      { name: 'Total Revenue', value: `৳ ${Number(data.total_revenue).toLocaleString()}`, change: 0, icon: CurrencyDollarIcon, bgColor: 'bg-primary-100 dark:bg-primary-500/20', textColor: 'text-primary-600 dark:text-primary-400' },
      { name: 'Total Orders', value: data.total_orders, change: 0, icon: ShoppingBagIcon, bgColor: 'bg-blue-100 dark:bg-blue-500/20', textColor: 'text-blue-600 dark:text-blue-400' },
      { name: 'Pending Orders', value: data.pending_orders, change: 0, icon: ClockIcon, bgColor: 'bg-yellow-100 dark:bg-yellow-500/20', textColor: 'text-yellow-600 dark:text-yellow-400' },
      { name: 'Processing', value: data.processing_orders, change: 0, icon: TruckIcon, bgColor: 'bg-indigo-100 dark:bg-indigo-500/20', textColor: 'text-indigo-600 dark:text-indigo-400' },
      { name: 'Delivered', value: data.delivered_orders, change: 0, icon: CheckBadgeIcon, bgColor: 'bg-green-100 dark:bg-green-500/20', textColor: 'text-green-600 dark:text-green-400' },
      { name: 'Total Customers', value: data.total_customers, change: 0, icon: UsersIcon, bgColor: 'bg-purple-100 dark:bg-purple-500/20', textColor: 'text-purple-600 dark:text-purple-400' },
    ];
  } catch (error) {
    console.error('Failed to load dashboard data', error);
  }
};

const statusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400',
    processing: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400',
    shipped: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
    delivered: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    cancelled: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
  };
  return colors[status] || 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300';
};

onMounted(() => {
  fetchDashboardData();
  pollInterval = setInterval(fetchDashboardData, 30000);
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
});
</script>
