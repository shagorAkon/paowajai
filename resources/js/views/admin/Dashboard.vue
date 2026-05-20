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
            <tr v-for="i in 5" :key="i" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <td class="px-6 py-4 font-medium">#ORD-{{ 1000 + i }}</td>
              <td class="px-6 py-4">Customer {{ i }}</td>
              <td class="px-6 py-4 text-slate-500">Today, 10:{{ i }}0 AM</td>
              <td class="px-6 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400">Processing</span>
              </td>
              <td class="px-6 py-4 text-right font-bold">৳ {{ (1200 * i).toLocaleString('en-IN') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { 
  CurrencyDollarIcon, 
  ShoppingBagIcon, 
  UsersIcon, 
  ChartBarIcon 
} from '@heroicons/vue/24/outline';

const stats = [
  { name: 'Total Revenue', value: '৳ 124,500', change: 12.5, icon: CurrencyDollarIcon, bgColor: 'bg-primary-100 dark:bg-primary-500/20', textColor: 'text-primary-600 dark:text-primary-400' },
  { name: 'Total Orders', value: '256', change: 8.2, icon: ShoppingBagIcon, bgColor: 'bg-blue-100 dark:bg-blue-500/20', textColor: 'text-blue-600 dark:text-blue-400' },
  { name: 'Total Customers', value: '1,420', change: -2.4, icon: UsersIcon, bgColor: 'bg-purple-100 dark:bg-purple-500/20', textColor: 'text-purple-600 dark:text-purple-400' },
  { name: 'Conversion Rate', value: '3.6%', change: 1.1, icon: ChartBarIcon, bgColor: 'bg-green-100 dark:bg-green-500/20', textColor: 'text-green-600 dark:text-green-400' },
];
</script>
