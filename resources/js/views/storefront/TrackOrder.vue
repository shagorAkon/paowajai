<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 px-4">
    <div class="max-w-3xl mx-auto">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Track Your Order</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Enter your Order Number, Tracking ID, or Mobile Number.</p>
      </div>

      <!-- Search Box -->
      <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4 mb-8">
        <input
          v-model="searchQuery"
          @keyup.enter="trackOrder"
          type="text"
          placeholder="e.g. PAO-20260520-832CA0 or 01XXXXXXXXX"
          class="flex-1 px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:ring-2 focus:ring-primary-500 outline-none font-semibold text-emerald-600 dark:text-emerald-400 placeholder:text-slate-400 dark:placeholder:text-slate-500"
        >
        <button @click="trackOrder" :disabled="loading || !searchQuery" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3 rounded-xl transition-colors disabled:opacity-50">
          {{ loading ? 'Searching...' : 'Track' }}
        </button>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-xl text-center font-semibold mb-8">
        {{ error }}
      </div>

      <!-- Multiple Orders Dropdown (when phone has multiple orders) -->
      <div v-if="multipleOrders.length > 0 && !order" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-8 animate-fade-in">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Multiple Orders Found</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">We found {{ multipleOrders.length }} orders linked to this number. Select one to view details:</p>
        <div class="space-y-3">
          <button
            v-for="o in multipleOrders"
            :key="o.id"
            @click="selectOrder(o.order_number)"
            class="w-full text-left flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-primary-400 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all group"
          >
            <div>
              <span class="font-bold text-slate-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">#{{ o.order_number }}</span>
              <span class="text-slate-500 dark:text-slate-400 text-sm ml-3">{{ new Date(o.created_at).toLocaleDateString() }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-sm font-bold text-slate-700 dark:text-slate-300">৳ {{ Number(o.total).toLocaleString() }}</span>
              <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize" :class="statusBadgeClass(o.status)">{{ o.status }}</span>
              <svg class="w-5 h-5 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
          </button>
        </div>
      </div>

      <!-- Order Results -->
      <div v-if="order" class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in">
        <!-- Back button when came from multi-select -->
        <button v-if="cameFromMulti" @click="goBackToList" class="flex items-center gap-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 px-6 pt-4">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          Back to order list
        </button>

        <div class="bg-primary-600 p-6 text-white flex flex-col sm:flex-row justify-between items-center gap-4">
          <div>
            <h2 class="text-xl font-bold">Order #{{ order.order_number }}</h2>
            <p class="text-primary-100 mt-1">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
          </div>
          <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="text-center sm:text-right">
              <div class="text-sm text-primary-100">Total Amount</div>
              <div class="text-2xl font-black mb-2">৳ {{ Number(order.total).toLocaleString() }}</div>
            </div>
            <div class="flex gap-2">
              <button @click="downloadInvoice(false)" :disabled="downloadingInvoice" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-xl transition-colors disabled:opacity-50" title="Download Invoice">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
              </button>
              <button @click="downloadInvoice(true)" :disabled="downloadingInvoice" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-xl transition-colors disabled:opacity-50" title="Print Invoice">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
              </button>
            </div>
          </div>
        </div>

        <div class="p-8">
          <!-- Status Timeline -->
          <h3 class="font-bold text-lg mb-6 text-slate-900 dark:text-white">Delivery Status</h3>
          <div class="relative flex flex-col sm:flex-row justify-between gap-6 sm:gap-0 before:content-[''] before:absolute before:left-[19px] sm:before:left-0 sm:before:top-[19px] sm:before:w-full before:h-full sm:before:h-0.5 before:bg-slate-200 dark:before:bg-slate-700">
            <div v-for="(step, index) in timelineSteps" :key="index" class="relative z-10 flex sm:flex-col items-center gap-4 sm:gap-2">
              <div :class="[isStepCompleted(step.status) ? 'bg-primary-600 text-white border-primary-600 ring-4 ring-primary-100 dark:ring-primary-900' : 'bg-white dark:bg-slate-800 text-slate-300 dark:text-slate-600 border-slate-200 dark:border-slate-600', 'w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold transition-all duration-500']">
                <svg v-if="isStepCompleted(step.status)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <div class="sm:text-center">
                <p :class="[isStepCompleted(step.status) ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-500 dark:text-slate-400 font-medium']">{{ step.label }}</p>
              </div>
            </div>
          </div>

          <!-- Ordered Products -->
          <div v-if="order.items && order.items.length > 0" class="mt-12">
            <h3 class="font-bold text-lg mb-4 text-slate-900 dark:text-white">Ordered Products</h3>
            <div class="space-y-3">
              <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                <img :src="item.product?.images?.[0] ? `/storage/${item.product.images[0]}` : 'https://placehold.co/60x60'" class="w-14 h-14 rounded-lg object-cover bg-slate-100" />
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2">
                    <p class="font-semibold text-slate-900 dark:text-white truncate">{{ item.product_name }}</p>
                    <span :class="itemStatusBadgeClass(item.status)" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">{{ item.status || 'pending' }}</span>
                  </div>
                  <p v-if="item.variant_label" class="text-xs text-slate-500">{{ item.variant_label }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm text-slate-500">x{{ item.quantity }}</p>
                  <p class="font-bold text-slate-900 dark:text-white">৳ {{ Number(item.total).toLocaleString() }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-12 bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div>
              <h4 class="font-bold text-slate-900 dark:text-white mb-3 text-sm uppercase tracking-wider">Shipping Details</h4>
              <p class="text-slate-600 dark:text-slate-300 font-medium">{{ order.customer_name }}</p>
              <p class="text-slate-600 dark:text-slate-400">{{ order.shipping_address }}</p>
              <p class="text-slate-600 dark:text-slate-400">{{ order.shipping_city }} {{ order.shipping_zip }}</p>
              <p class="text-slate-600 dark:text-slate-400 mt-2">📞 {{ order.customer_phone }}</p>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 dark:text-white mb-3 text-sm uppercase tracking-wider">Order Info</h4>
              <p class="text-slate-600 dark:text-slate-400"><span class="font-medium">Tracking Number:</span> {{ order.tracking_number || 'N/A' }}</p>
              <p class="text-slate-600 dark:text-slate-400"><span class="font-medium">Courier:</span> <span class="capitalize">{{ order.courier || 'N/A' }}</span></p>
              <p class="text-slate-600 dark:text-slate-400"><span class="font-medium">Payment:</span> <span class="capitalize">{{ order.payment_method }} ({{ order.payment_status }})</span></p>
            </div>
          </div>

          <!-- Order History Logs -->
          <div v-if="order.histories && order.histories.length > 0" class="mt-12">
            <h3 class="font-bold text-lg mb-6 text-slate-900 dark:text-white">Detailed Tracking History</h3>
            <div class="space-y-4">
              <div v-for="history in order.histories" :key="history.id" class="flex gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 items-start">
                <div class="bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 p-2 rounded-lg mt-1">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                  <p class="font-bold text-slate-900 dark:text-white capitalize">{{ history.status }}</p>
                  <p class="text-slate-600 dark:text-slate-400 text-sm mt-1" v-if="history.note">{{ history.note }}</p>
                  <p class="text-slate-400 dark:text-slate-500 text-xs mt-2">{{ new Date(history.created_at).toLocaleString() }}</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../utils/api';

const route = useRoute();
const searchQuery = ref('');
const order = ref(null);
const multipleOrders = ref([]);
const cameFromMulti = ref(false);
const savedPhone = ref('');
const loading = ref(false);
const error = ref('');

const timelineSteps = [
  { label: 'Placed', status: 'pending' },
  { label: 'Confirmed', status: 'confirmed' },
  { label: 'Processing', status: 'processing' },
  { label: 'Shipped', status: 'shipped' },
  { label: 'Delivered', status: 'delivered' }
];

const statusWeights = {
  'pending': 1,
  'confirmed': 2,
  'processing': 3,
  'packed': 4,
  'shipped': 5,
  'delivered': 6,
  'cancelled': -1,
  'returned': -1,
  'refunded': -1
};

const statusBadgeClass = (status) => {
  const map = {
    pending: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400',
    confirmed: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
    processing: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400',
    shipped: 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-400',
    delivered: 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
    cancelled: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
    returned: 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400',
    refunded: 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400',
  };
  return map[status] || 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300';
};

const itemStatusBadgeClass = (status) => {
  const map = {
    pending: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400',
    accepted: 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
    shipped: 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-400',
    rejected: 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
  };
  return map[status || 'pending'] || 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300';
};

onMounted(() => {
  if (route.params.order_number) {
    searchQuery.value = route.params.order_number;
    trackOrder();
  }
});

const isStepCompleted = (stepStatus) => {
  if (!order.value) return false;
  const currentWeight = statusWeights[order.value.status];
  const stepWeight = statusWeights[stepStatus];
  return currentWeight >= stepWeight;
};

const trackOrder = async () => {
  if (!searchQuery.value) return;
  loading.value = true;
  error.value = '';
  order.value = null;
  multipleOrders.value = [];
  cameFromMulti.value = false;

  try {
    const { data } = await api.get(`/storefront/tracking/search`, {
      params: { query: searchQuery.value.trim() }
    });

    if (data.type === 'single') {
      order.value = data.order;
    } else if (data.type === 'multiple') {
      multipleOrders.value = data.orders;
      savedPhone.value = searchQuery.value.trim();
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Order not found. Please check your tracking number, order ID, or phone number.';
  } finally {
    loading.value = false;
  }
};

const selectOrder = async (orderNumber) => {
  loading.value = true;
  error.value = '';
  try {
    const { data } = await api.get(`/storefront/tracking/search`, {
      params: { query: orderNumber }
    });
    if (data.type === 'single') {
      order.value = data.order;
      cameFromMulti.value = true;
    }
  } catch (err) {
    error.value = 'Failed to load order details.';
  } finally {
    loading.value = false;
  }
};

const goBackToList = () => {
  order.value = null;
  cameFromMulti.value = false;
  // multipleOrders stays intact so the list is still visible
};

const downloadingInvoice = ref(false);

const downloadInvoice = async (print = false) => {
  if (!order.value) return;
  downloadingInvoice.value = true;
  try {
    const response = await api.get(`/storefront/tracking/${order.value.order_number}/invoice`, {
      responseType: 'blob'
    });
    const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
    
    if (print) {
      const iframe = document.createElement('iframe');
      iframe.style.display = 'none';
      iframe.src = url;
      document.body.appendChild(iframe);
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
    } else {
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `invoice-${order.value.order_number}.pdf`);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  } catch (err) {
    console.error('Invoice download failed', err);
    alert('Failed to load invoice.');
  } finally {
    downloadingInvoice.value = false;
  }
};
</script>
