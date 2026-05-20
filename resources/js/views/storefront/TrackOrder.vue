<template>
  <div class="min-h-screen bg-slate-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Track Your Order</h1>
        <p class="text-slate-500 mt-2">Enter your Order Number to see real-time status updates.</p>
      </div>

      <!-- Search Box -->
      <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex gap-4 mb-8">
        <input v-model="searchQuery" type="text" placeholder="e.g. ORD-XYZ123" class="flex-1 px-4 py-3 rounded-xl bg-slate-50 border focus:ring-2 focus:ring-primary-500 outline-none font-medium">
        <button @click="trackOrder" :disabled="loading || !searchQuery" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 rounded-xl transition-colors">
          {{ loading ? 'Searching...' : 'Track' }}
        </button>
      </div>

      <div v-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl text-center font-semibold mb-8">
        {{ error }}
      </div>

      <!-- Order Results -->
      <div v-if="order" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in">
        <div class="bg-primary-600 p-6 text-white flex flex-col sm:flex-row justify-between items-center gap-4">
          <div>
            <h2 class="text-xl font-bold">Order #{{ order.order_number }}</h2>
            <p class="text-primary-100 mt-1">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
          </div>
          <div class="text-right">
            <div class="text-sm text-primary-100">Total Amount</div>
            <div class="text-2xl font-black">৳ {{ order.total }}</div>
          </div>
        </div>

        <div class="p-8">
          <!-- Status Timeline -->
          <h3 class="font-bold text-lg mb-6">Delivery Status</h3>
          <div class="relative flex flex-col sm:flex-row justify-between gap-6 sm:gap-0 before:content-[''] before:absolute before:left-[19px] sm:before:left-0 sm:before:top-[19px] sm:before:w-full before:h-full sm:before:h-0.5 before:bg-slate-200">
            <div v-for="(step, index) in timelineSteps" :key="index" class="relative z-10 flex sm:flex-col items-center gap-4 sm:gap-2">
              <div :class="[isStepCompleted(step.status) ? 'bg-primary-600 text-white border-primary-600 ring-4 ring-primary-100' : 'bg-white text-slate-300 border-slate-200', 'w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold transition-all duration-500']">
                <svg v-if="isStepCompleted(step.status)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <div class="sm:text-center">
                <p :class="[isStepCompleted(step.status) ? 'text-slate-900 font-bold' : 'text-slate-500 font-medium']">{{ step.label }}</p>
              </div>
            </div>
          </div>

          <div class="mt-12 bg-slate-50 rounded-2xl p-6 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-8">
            <div>
              <h4 class="font-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Shipping Details</h4>
              <p class="text-slate-600 font-medium">{{ order.customer_name }}</p>
              <p class="text-slate-600">{{ order.shipping_address }}</p>
              <p class="text-slate-600">{{ order.shipping_city }} {{ order.shipping_zip }}</p>
              <p class="text-slate-600 mt-2">📞 {{ order.customer_phone }}</p>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 mb-3 text-sm uppercase tracking-wider">Courier Info</h4>
              <template v-if="order.tracking_number">
                <p class="text-slate-600"><span class="font-medium">Courier:</span> <span class="capitalize">{{ order.courier }}</span></p>
                <p class="text-slate-600"><span class="font-medium">Tracking ID:</span> {{ order.tracking_number }}</p>
              </template>
              <p v-else class="text-slate-500 italic">Not assigned yet</p>
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
const loading = ref(false);
const error = ref('');

const timelineSteps = [
  { label: 'Order Placed', status: 'pending' },
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

  try {
    const { data } = await api.get(`/storefront/track-order/${searchQuery.value}`);
    order.value = data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Order not found. Please check your tracking number.';
  } finally {
    loading.value = false;
  }
};
</script>
