<template>
  <div class="container mx-auto px-4 py-12">
    <div v-if="successOrder" class="max-w-2xl mx-auto text-center space-y-8 py-16 animate-fade-in">
      <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto text-5xl">
        ✓
      </div>
      <div class="space-y-3">
        <h1 class="text-4xl font-extrabold tracking-tight">Order Placed Successfully!</h1>
        <p class="text-slate-500 dark:text-slate-400">Thank you for shopping with Paowazay. Your order number is <strong class="text-slate-900 dark:text-white">{{ successOrder.order_number }}</strong>.</p>
      </div>
      <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-2xl max-w-md mx-auto text-left space-y-4">
        <h3 class="font-bold border-b pb-2">Delivery Details</h3>
        <p><strong>Recipient:</strong> {{ successOrder.customer_name }}</p>
        <p><strong>Phone:</strong> {{ successOrder.customer_phone }}</p>
        <p><strong>Address:</strong> {{ successOrder.shipping_address }}, {{ successOrder.shipping_city }}</p>
        <p class="font-bold pt-2 flex justify-between border-t">
          <span>Amount Payable:</span>
          <span>৳ {{ Number(successOrder.total).toLocaleString('en-IN') }}</span>
        </p>
      </div>
      <div class="pt-4">
        <router-link to="/" class="bg-primary-500 hover:bg-primary-600 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg inline-block">
          Continue Shopping
        </router-link>
      </div>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-12">
      <!-- Checkout Form -->
      <div class="lg:col-span-7 space-y-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Checkout Information</h1>
        
        <form @submit.prevent="placeOrder" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-bold text-slate-500">Full Name</label>
              <input v-model="form.customer_name" required type="text" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500">
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-bold text-slate-500">Phone Number</label>
              <input v-model="form.customer_phone" required type="text" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500">
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-500">Email Address (Optional)</label>
            <input v-model="form.customer_email" type="email" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-bold text-slate-500">Division</label>
              <select v-model="form.shipping_division" @change="updateShipping" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500">
                <option value="">Select Division</option>
                <option value="Dhaka">Dhaka</option>
                <option value="Chattogram">Chattogram</option>
                <option value="Rajshahi">Rajshahi</option>
                <option value="Khulna">Khulna</option>
                <option value="Sylhet">Sylhet</option>
                <option value="Barishal">Barishal</option>
                <option value="Rangpur">Rangpur</option>
                <option value="Mymensingh">Mymensingh</option>
              </select>
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-bold text-slate-500">District / City</label>
              <input v-model="form.shipping_city" required type="text" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500">
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-bold text-slate-500">Zip Code</label>
              <input v-model="form.shipping_zip" type="text" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500">
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-bold text-slate-500">Full Shipping Address</label>
            <textarea v-model="form.shipping_address" required rows="3" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-primary-500"></textarea>
          </div>

          <!-- Payment Method -->
          <div class="space-y-4 pt-4 border-t dark:border-slate-800">
            <h3 class="font-bold text-lg">Payment Method</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border cursor-pointer hover:border-primary-500 transition" :class="[form.payment_method === 'cod' ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-slate-200 dark:border-slate-700']">
                <input v-model="form.payment_method" type="radio" value="cod" class="text-primary-500 focus:ring-primary-500">
                <div class="flex flex-col">
                  <span class="font-bold text-sm">Cash on Delivery</span>
                  <span class="text-xs text-slate-500">Pay when delivered</span>
                </div>
              </label>
              
              <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border cursor-pointer hover:border-primary-500 transition" :class="[form.payment_method === 'bkash' ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-slate-200 dark:border-slate-700']">
                <input v-model="form.payment_method" type="radio" value="bkash" class="text-primary-500 focus:ring-primary-500">
                <div class="flex flex-col">
                  <span class="font-bold text-sm">bKash</span>
                  <span class="text-xs text-slate-500">Fast mobile checkout</span>
                </div>
              </label>

              <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border cursor-pointer hover:border-primary-500 transition" :class="[form.payment_method === 'nagad' ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-slate-200 dark:border-slate-700']">
                <input v-model="form.payment_method" type="radio" value="nagad" class="text-primary-500 focus:ring-primary-500">
                <div class="flex flex-col">
                  <span class="font-bold text-sm">Nagad</span>
                  <span class="text-xs text-slate-500">Secure digital payment</span>
                </div>
              </label>
            </div>
          </div>

          <button type="submit" :disabled="loading || cartStore.items.length === 0" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 disabled:opacity-50 text-lg">
            {{ loading ? 'Placing Order...' : 'Confirm Order & Place' }}
          </button>
        </form>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24 h-fit">
        <h2 class="text-2xl font-extrabold tracking-tight">Order Summary</h2>

        <div class="glass-card p-6 space-y-6">
          <ul v-if="cartStore.items.length" class="divide-y divide-slate-100 dark:divide-slate-700">
            <li v-for="item in cartStore.items" :key="`${item.product_id}-${item.variant_id}`" class="py-4 flex gap-4 first:pt-0 last:pb-0">
              <img :src="item.image ? `/storage/${item.image}` : 'https://placehold.co/100x100'" class="w-16 h-16 object-cover rounded-lg bg-slate-50 border">
              <div class="flex-grow flex flex-col justify-between">
                <div>
                  <h4 class="font-bold text-sm line-clamp-2 pr-6">{{ item.name }}</h4>
                  <p v-if="item.variant_label" class="text-xs text-slate-500 mt-1">{{ item.variant_label }}</p>
                </div>
                <div class="flex justify-between items-baseline mt-2">
                  <span class="text-xs text-slate-500">Qty: {{ item.quantity }}</span>
                  <span class="font-extrabold text-sm">৳ {{ (item.price * item.quantity).toLocaleString('en-IN') }}</span>
                </div>
              </div>
            </li>
          </ul>

          <div v-else class="text-center py-8 text-slate-500">
            Your cart is empty.
          </div>

          <!-- Total Calculation breakdown -->
          <div class="space-y-4 pt-6 border-t dark:border-slate-700 text-sm font-semibold">
            <div class="flex justify-between">
              <span class="text-slate-500">Subtotal</span>
              <span>৳ {{ cartStore.subtotal.toLocaleString('en-IN') }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Shipping Cost</span>
              <span>৳ {{ shippingCost.toLocaleString('en-IN') }}</span>
            </div>
            <div class="flex justify-between text-lg font-black pt-4 border-t dark:border-slate-700">
              <span>Total Payable</span>
              <span class="text-primary-500">৳ {{ (cartStore.subtotal + shippingCost).toLocaleString('en-IN') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue';
import { useCartStore } from '../stores/useCartStore';
import api from '../utils/api';

const cartStore = useCartStore();

const loading = ref(false);
const successOrder = ref(null);
const shippingCost = ref(120);

const form = reactive({
  customer_name: '',
  customer_phone: '',
  customer_email: '',
  shipping_division: '',
  shipping_city: '',
  shipping_zip: '',
  shipping_address: '',
  payment_method: 'cod',
  coupon_code: '',
});

const updateShipping = () => {
  if (form.shipping_division.toLowerCase() === 'dhaka') {
    shippingCost.value = 60;
  } else {
    shippingCost.value = 120;
  }
};

const placeOrder = async () => {
  loading.value = true;
  try {
    const payload = {
      ...form,
      items: cartStore.items.map(item => ({
        product_id: item.product_id,
        variant_id: item.variant_id,
        quantity: item.quantity,
      })),
    };

    const { data } = await api.post('/storefront/checkout', payload);
    successOrder.value = data.order;
    cartStore.clearCart();
  } catch (error) {
    console.error(error);
    alert(error.response?.data?.message || 'Error placing order. Please try again.');
  } finally {
    loading.value = false;
  }
};
</script>
