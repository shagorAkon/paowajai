<template>
  <div class="min-h-screen flex flex-col font-sans text-slate-900 bg-slate-50 dark:bg-slate-900 dark:text-white transition-colors duration-300">
    <Navbar />
    
    <main class="flex-grow">
      <!-- Route Transitions -->
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <Footer />
    
    <Toast />

    <!-- Simple Cart Slide-over -->
    <div v-if="cartStore.isOpen" class="fixed inset-0 z-[100] overflow-hidden">
      <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="cartStore.toggleCart"></div>
      <div class="fixed inset-y-0 right-0 max-w-md w-full flex">
        <div class="w-full h-full bg-white dark:bg-slate-800 shadow-2xl flex flex-col animate-slide-left">
          
          <div class="p-6 border-b dark:border-slate-700 flex items-center justify-between">
            <h2 class="text-xl font-bold">Your Cart ({{ cartStore.totalItems }})</h2>
            <button @click="cartStore.toggleCart" class="text-slate-500 hover:text-slate-900 dark:hover:text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>

          <div class="flex-1 overflow-y-auto p-6">
            <div v-if="cartStore.items.length === 0" class="h-full flex flex-col items-center justify-center text-slate-500">
              <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
              <p>Your cart is empty.</p>
              <button @click="cartStore.toggleCart" class="mt-6 text-primary-500 font-semibold hover:underline">Continue Shopping</button>
            </div>
            
            <ul v-else class="space-y-6">
              <li v-for="item in cartStore.items" :key="`${item.product_id}-${item.variant_id}`" class="flex gap-4">
                <img :src="item.image ? (item.image.startsWith('http') ? item.image : `/storage/${item.image}`) : 'https://placehold.co/100x100/f8fafc/94a3b8?text=P'" class="w-20 h-20 rounded-lg object-cover bg-slate-100">
                <div class="flex-1 flex flex-col">
                  <div class="flex justify-between">
                    <h3 class="font-semibold line-clamp-1 pr-4">{{ item.name }}</h3>
                    <button @click="cartStore.removeFromCart(item.product_id, item.variant_id)" class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                  </div>
                  <div v-if="item.variants && item.variants.length > 0" class="mt-1">
                    <select :value="item.variant_id" @change="cartStore.updateItemVariant(item.product_id, item.variant_id, parseInt($event.target.value))" class="text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded py-1 px-2 focus:outline-none focus:ring-1 focus:ring-primary-500 w-full max-w-[140px] truncate">
                      <option v-for="v in item.variants" :key="v.id" :value="v.id">
                        {{ v.label }}
                      </option>
                    </select>
                  </div>
                  <p v-else-if="item.variant_label" class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">{{ item.variant_label }}</p>
                  <div class="mt-auto flex items-center justify-between pt-2">
                    <div class="flex items-center border dark:border-slate-700 rounded-md">
                      <button @click="cartStore.updateQuantity(item.product_id, item.variant_id, item.quantity - 1)" class="px-3 py-1 hover:bg-slate-100 dark:hover:bg-slate-700">-</button>
                      <span class="px-3 text-sm font-medium">{{ item.quantity }}</span>
                      <button @click="cartStore.updateQuantity(item.product_id, item.variant_id, item.quantity + 1)" class="px-3 py-1 hover:bg-slate-100 dark:hover:bg-slate-700">+</button>
                    </div>
                    <span class="font-bold">৳ {{ (item.price * item.quantity).toLocaleString('en-IN') }}</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <div v-if="cartStore.items.length > 0" class="p-6 border-t dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <div class="flex justify-between mb-4 text-lg font-bold">
              <span>Subtotal</span>
              <span>৳ {{ cartStore.subtotal.toLocaleString('en-IN') }}</span>
            </div>
            <p class="text-sm text-slate-500 mb-4">Shipping and taxes calculated at checkout.</p>
            <router-link to="/checkout" @click="cartStore.toggleCart" class="block w-full text-center bg-primary-500 hover:bg-primary-600 text-white py-4 rounded-xl font-bold transition-colors shadow-lg">
              Proceed to Checkout
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue';
import Footer from '../components/Footer.vue';
import Toast from '../components/Toast.vue';
import { useCartStore } from '../stores/useCartStore';

const cartStore = useCartStore();
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.animate-slide-left {
  animation: slideLeft 0.3s ease-out forwards;
}

@keyframes slideLeft {
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}
</style>
