<template>
  <div>
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Order Management</h2>
        <p class="text-slate-500 dark:text-slate-400">Process and track customer orders.</p>
      </div>
      <div class="flex gap-4">
        <button @click="fetchOrders" class="p-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
          <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6 flex flex-wrap gap-4 items-center">
      <div class="flex-1 min-w-[200px]">
        <div class="relative">
          <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          <input v-model="filters.search" @keyup.enter="fetchOrders" type="text" placeholder="Search by Order ID, Name, Phone..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
      </div>
      <div class="w-full sm:w-auto">
        <select v-model="filters.status" @change="fetchOrders" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
          <option value="">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="processing">Processing</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden relative min-h-[400px]">
      <div v-if="loading" class="absolute inset-0 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm flex items-center justify-center z-10">
        <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
          <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4">Order ID</th>
              <th class="px-6 py-4">Date</th>
              <th class="px-6 py-4">Customer</th>
              <th class="px-6 py-4">Total</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            <tr v-if="orders.length === 0 && !loading">
              <td colspan="6" class="px-6 py-12 text-center text-slate-500">No orders found.</td>
            </tr>
            <tr v-for="order in orders" :key="order.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <td class="px-6 py-4 font-bold text-primary-600">#{{ order.order_number }}</td>
              <td class="px-6 py-4 text-slate-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
              <td class="px-6 py-4">
                <div class="font-bold">{{ order.customer_name }}</div>
                <div class="text-xs text-slate-500">{{ order.customer_phone }}</div>
              </td>
              <td class="px-6 py-4 font-bold">৳ {{ order.total }}</td>
              <td class="px-6 py-4">
                <span :class="getStatusBadgeClass(order.status)" class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <router-link :to="`/admin/orders/${order.id}`" class="inline-block px-4 py-2 bg-slate-100 hover:bg-primary-50 text-slate-700 hover:text-primary-600 font-bold rounded-lg transition-colors">
                  View Details
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="p-4 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
        <span class="text-sm text-slate-500">Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} orders</span>
        <div class="flex gap-2">
          <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="p-2 border rounded-lg hover:bg-slate-50 disabled:opacity-50">Prev</button>
          <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="p-2 border rounded-lg hover:bg-slate-50 disabled:opacity-50">Next</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../utils/api';

const orders = ref([]);
const loading = ref(false);
const filters = ref({ search: '', status: '' });
const pagination = ref({ current_page: 1, last_page: 1, total: 0 });

const fetchOrders = async (page = 1) => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/orders', {
      params: { ...filters.value, page }
    });
    orders.value = data.data;
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      total: data.total,
      from: data.from,
      to: data.to
    };
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    fetchOrders(page);
  }
};

const getStatusBadgeClass = (status) => {
  const map = {
    pending: 'bg-orange-100 text-orange-700',
    confirmed: 'bg-blue-100 text-blue-700',
    processing: 'bg-purple-100 text-purple-700',
    shipped: 'bg-indigo-100 text-indigo-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
    returned: 'bg-slate-100 text-slate-700',
    refunded: 'bg-red-100 text-red-700'
  };
  return map[status] || 'bg-slate-100 text-slate-700';
};

onMounted(() => fetchOrders());
</script>
