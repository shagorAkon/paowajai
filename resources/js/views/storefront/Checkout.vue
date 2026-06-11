<template>
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">Secure Checkout</h1>

    <div class="flex flex-col lg:flex-row gap-12">
      <!-- Checkout Form -->
      <div class="lg:w-2/3 space-y-8">
        <!-- Contact & Shipping -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
          <h2 class="text-xl font-bold mb-6 pb-2 border-b dark:border-slate-700">1. Delivery Information</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold mb-2">Full Name <span class="text-red-500">*</span></label>
              <input v-model="form.customer_name" type="text" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold mb-2">Phone Number <span class="text-red-500">*</span></label>
                <input v-model="form.customer_phone" type="text" placeholder="01XXXXXXXXX" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2">Email Address (Optional)</label>
                <input v-model="form.customer_email" type="email" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">Detailed Address <span class="text-red-500">*</span></label>
              <textarea v-model="form.shipping_address" rows="3" placeholder="House No, Road No, Area" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold mb-2">City/District <span class="text-red-500">*</span></label>
                <select v-model="form.shipping_city" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none bg-white dark:bg-slate-900">
                  <option value="Dhaka">Inside Dhaka</option>
                  <option value="Outside">Outside Dhaka</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2">Postal/ZIP Code</label>
                <input v-model="form.shipping_zip" type="text" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
          <h2 class="text-xl font-bold mb-6 pb-2 border-b dark:border-slate-700">2. Payment Method</h2>
          <div class="space-y-4">
            <label class="flex items-center justify-between p-4 border dark:border-slate-600 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors" :class="{'border-primary-500 bg-primary-50 dark:bg-primary-900/30': form.payment_method === 'cod'}">
              <div class="flex items-center gap-3">
                <input type="radio" v-model="form.payment_method" value="cod" class="w-5 h-5 text-primary-600 focus:ring-primary-500">
                <span class="font-bold text-slate-900 dark:text-white">Cash on Delivery (COD)</span>
              </div>
              <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </label>
            <label class="flex items-center justify-between p-4 border dark:border-slate-600 rounded-xl opacity-60 cursor-not-allowed transition-colors">
              <div class="flex items-center gap-3">
                <input disabled type="radio" v-model="form.payment_method" value="bkash" class="w-5 h-5 text-slate-400 cursor-not-allowed">
                <span class="font-bold text-slate-900 dark:text-white">bKash (Coming Soon)</span>
              </div>
            </label>
          </div>
        </div>
      </div>

      <!-- Order Summary Sidebar -->
      <div class="lg:w-1/3">
        <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 sticky top-24">
          <h2 class="text-xl font-bold mb-6 pb-2 border-b dark:border-slate-700">Order Summary</h2>
          
          <div v-if="cartStore.items.length === 0" class="py-8 text-center text-slate-500">
            <p class="font-medium">Your cart is empty.</p>
            <router-link to="/products" class="text-primary-500 font-semibold hover:underline mt-2 inline-block">Continue Shopping</router-link>
          </div>

          <div v-else class="space-y-4 max-h-[400px] overflow-y-auto mb-6 pr-2">
            <div v-for="item in cartStore.items" :key="`${item.product_id}-${item.variant_id}`" class="flex gap-4 items-start">
              <img :src="item.image ? (item.image.startsWith('http') ? item.image : `/storage/${item.image}`) : 'https://placehold.co/100x100/f8fafc/94a3b8?text=P'" class="w-16 h-16 object-cover rounded-lg border dark:border-slate-600 bg-white">
              <div class="flex-1">
                <h4 class="font-bold text-sm text-slate-900 dark:text-white leading-tight line-clamp-2">{{ item.name }}</h4>
                <p class="text-xs text-slate-500 mt-1" v-if="item.variant_label">Variant: {{ item.variant_label }}</p>
                <div class="flex justify-between items-center mt-2">
                  <span class="text-sm font-semibold">৳ {{ Number(item.price).toLocaleString('en-IN') }} x {{ item.quantity }}</span>
                  <span class="text-sm font-bold text-primary-600">৳ {{ (item.price * item.quantity).toLocaleString('en-IN') }}</span>
                </div>
              </div>
            </div>
          </div>

          <div v-if="cartStore.items.length > 0" class="space-y-3 pt-4 border-t border-slate-200 dark:border-slate-700 text-sm">
            <div class="flex justify-between text-slate-600">
              <span>Subtotal</span>
              <span class="font-semibold text-slate-900 dark:text-white">৳ {{ cartStore.subtotal.toLocaleString('en-IN') }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
              <span>Shipping ({{ form.shipping_city }})</span>
              <span class="font-semibold text-slate-900 dark:text-white">৳ {{ shippingCost }}</span>
            </div>
          </div>

          <div v-if="cartStore.items.length > 0" class="flex justify-between items-center pt-4 mt-4 border-t border-slate-200 dark:border-slate-700">
            <span class="font-bold text-lg">Total Amount</span>
            <span class="font-black text-2xl text-primary-600">৳ {{ total.toLocaleString('en-IN') }}</span>
          </div>

          <button @click="placeOrder" :disabled="loading || cartStore.items.length === 0" class="w-full mt-8 bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-4 rounded-xl transition-colors shadow-xl shadow-primary-600/30 flex justify-center items-center gap-2">
            <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            {{ loading ? 'Processing...' : 'Confirm Order' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../utils/api';
import { useAuthStore } from '../../stores/useAuthStore';
import { useCartStore } from '../../stores/useCartStore';

const router = useRouter();
const authStore = useAuthStore();
const cartStore = useCartStore();
const loading = ref(false);

const form = ref({
  customer_name: '',
  customer_phone: '',
  customer_email: '',
  shipping_address: '',
  shipping_city: 'Dhaka',
  shipping_zip: '',
  payment_method: 'cod'
});

onMounted(() => {
  if (authStore.user) {
    form.value.customer_name = authStore.user.name || '';
    form.value.customer_email = authStore.user.email || '';
    form.value.customer_phone = authStore.user.phone || '';
    form.value.shipping_address = authStore.user.address || '';
    if (authStore.user.city) form.value.shipping_city = authStore.user.city;
    if (authStore.user.zip) form.value.shipping_zip = authStore.user.zip;
  }

  // Watch for auth changes in case user data is loaded after mount
  watch(() => authStore.user, (user) => {
    if (user) {
      if (!form.value.customer_name) form.value.customer_name = user.name || '';
      if (!form.value.customer_email) form.value.customer_email = user.email || '';
      if (!form.value.customer_phone) form.value.customer_phone = user.phone || '';
      if (!form.value.shipping_address) form.value.shipping_address = user.address || '';
      if (!form.value.shipping_zip) form.value.shipping_zip = user.zip || '';
    }
  });
});

const shippingCost = computed(() => {
  return form.value.shipping_city === 'Dhaka' ? 60 : 120;
});

const total = computed(() => cartStore.subtotal + shippingCost.value);

const placeOrder = async () => {
  if (!form.value.customer_name || !form.value.customer_phone || !form.value.shipping_address) {
    alert("Please fill in all required fields");
    return;
  }

  loading.value = true;
  try {
    const payload = {
      ...form.value,
      items: cartStore.items.map(i => ({
        product_id: i.product_id,
        variant_id: i.variant_id || null,
        quantity: i.quantity
      }))
    };

    const { data } = await api.post('/storefront/checkout', payload);
    
    // Clear the cart properly via the store
    cartStore.clearCart();
    
    // Redirect to tracking page
    router.push({ name: 'storefront.track', params: { order_number: data.order_number } });
  } catch (err) {
    alert(err.response?.data?.message || 'Checkout failed');
  } finally {
    loading.value = false;
  }
};
</script>

