<template>
  <div class="max-w-6xl mx-auto" v-if="order">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
      <div>
        <div class="flex items-center gap-3">
          <router-link to="/admin/orders" class="p-2 bg-white rounded-lg border hover:bg-slate-50 transition-colors">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          </router-link>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Order #{{ order.order_number }}</h2>
          <span :class="getStatusBadgeClass(order.status)" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">{{ order.status }}</span>
        </div>
        <p class="text-slate-500 mt-2 ml-12">Placed on {{ new Date(order.created_at).toLocaleString() }}</p>
      </div>
      <div class="flex gap-3">
        <button @click="downloadInvoice" :disabled="downloadingInvoice" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold py-2.5 px-5 rounded-xl transition-colors flex items-center gap-2 disabled:opacity-50">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          {{ downloadingInvoice ? 'Generating...' : 'PDF Invoice' }}
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Column -->
      <div class="lg:col-span-2 space-y-8">
        
        <!-- Order Items -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
          <h3 class="font-bold text-lg mb-4 pb-2 border-b">Ordered Items</h3>
          <div class="space-y-4 max-h-[500px] overflow-y-auto">
            <div v-for="item in order.items" :key="item.id" class="flex justify-between items-center py-2 border-b last:border-0 border-slate-100">
              <div>
                <p class="font-bold text-slate-900">{{ item.product_name }}</p>
                <p class="text-sm text-slate-500" v-if="item.variant_label">Variant: {{ item.variant_label }}</p>
                <p class="text-sm text-slate-500">Qty: {{ item.quantity }} × ৳ {{ item.price }}</p>
              </div>
              <div class="font-black text-primary-600">
                ৳ {{ item.total }}
              </div>
            </div>
          </div>
          
          <div class="mt-6 pt-4 border-t border-slate-200 space-y-2">
            <div class="flex justify-between text-slate-600">
              <span>Subtotal</span>
              <span class="font-semibold">৳ {{ order.subtotal }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
              <span>Shipping</span>
              <span class="font-semibold">৳ {{ order.shipping_cost }}</span>
            </div>
            <div class="flex justify-between items-center mt-4 pt-4 border-t border-slate-200 text-lg">
              <span class="font-bold">Total Amount</span>
              <span class="font-black text-2xl text-primary-600">৳ {{ order.total }}</span>
            </div>
          </div>
        </div>

      </div>

      <!-- Right Column -->
      <div class="space-y-8">
        
        <!-- Action Management -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
          <h3 class="font-bold text-lg mb-4 pb-2 border-b">Order Processing</h3>
          
          <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Update Status</label>
            <div class="flex gap-2">
              <select v-model="statusUpdate" class="flex-1 px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="packed">Packed</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
                <option value="returned">Returned</option>
                <option value="refunded">Refunded</option>
              </select>
              <button @click="updateOrderStatus" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-4 py-2 rounded-xl transition-colors">Update</button>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2">Assign Tracking</label>
            <div class="space-y-3">
              <select v-model="tracking.courier" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Select Courier</option>
                <option value="steadfast">Steadfast</option>
                <option value="pathao">Pathao</option>
                <option value="redx">RedX</option>
                <option value="other">Other</option>
              </select>
              <input v-model="tracking.number" type="text" placeholder="Tracking Number" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary-500 outline-none">
              <button @click="updateTrackingInfo" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-xl transition-colors">Save Tracking</button>
            </div>
          </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
          <h3 class="font-bold text-lg mb-4 pb-2 border-b">Customer Details</h3>
          <div class="space-y-4">
            <div>
              <p class="text-sm font-semibold text-slate-500 uppercase">Contact Info</p>
              <p class="font-bold text-slate-900">{{ order.customer_name }}</p>
              <p class="text-slate-600 flex items-center gap-2 mt-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                {{ order.customer_phone }}
              </p>
              <p v-if="order.customer_email" class="text-slate-600 flex items-center gap-2 mt-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                {{ order.customer_email }}
              </p>
            </div>
            
            <div class="pt-4 border-t border-slate-100">
              <p class="text-sm font-semibold text-slate-500 uppercase">Shipping Address</p>
              <p class="text-slate-800 mt-1">{{ order.shipping_address }}</p>
              <p class="text-slate-800">{{ order.shipping_city }} {{ order.shipping_zip }}</p>
            </div>
            
            <div class="pt-4 border-t border-slate-100">
              <p class="text-sm font-semibold text-slate-500 uppercase">Payment Method</p>
              <p class="font-bold text-slate-900 uppercase mt-1">{{ order.payment_method }}</p>
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
const order = ref(null);
const statusUpdate = ref('');
const tracking = ref({ courier: '', number: '' });
const downloadingInvoice = ref(false);

const fetchOrder = async () => {
  try {
    const { data } = await api.get(`/admin/orders/${route.params.id}`);
    order.value = data;
    statusUpdate.value = data.status;
    tracking.value.courier = data.courier || '';
    tracking.value.number = data.tracking_number || '';
  } catch (err) {
    console.error('Failed to load order');
  }
};

const updateOrderStatus = async () => {
  try {
    await api.patch(`/admin/orders/${order.value.id}/status`, { status: statusUpdate.value });
    alert('Status updated successfully');
    fetchOrder();
  } catch (err) {
    alert('Failed to update status');
  }
};

const updateTrackingInfo = async () => {
  try {
    await api.patch(`/admin/orders/${order.value.id}/tracking`, { 
      tracking_number: tracking.value.number,
      courier: tracking.value.courier
    });
    alert('Tracking updated successfully');
    fetchOrder();
  } catch (err) {
    alert('Failed to update tracking');
  }
};

const downloadInvoice = async () => {
  downloadingInvoice.value = true;
  try {
    const response = await api.get(`/admin/orders/${order.value.id}/invoice`, {
      responseType: 'blob'
    });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `invoice-${order.value.order_number}.pdf`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (err) {
    console.error('Invoice download failed', err);
    alert('Failed to download invoice. Ensure dompdf is correctly configured.');
  } finally {
    downloadingInvoice.value = false;
  }
};

const getStatusBadgeClass = (status) => {
  const map = {
    pending: 'bg-orange-100 text-orange-700',
    confirmed: 'bg-blue-100 text-blue-700',
    processing: 'bg-purple-100 text-purple-700',
    shipped: 'bg-indigo-100 text-indigo-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700'
  };
  return map[status] || 'bg-slate-100 text-slate-700';
};

onMounted(() => fetchOrder());
</script>
