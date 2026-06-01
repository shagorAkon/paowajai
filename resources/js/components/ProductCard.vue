<template>
  <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 dark:border-slate-700 flex flex-col h-full">
    <!-- Image Area -->
    <div class="relative aspect-square overflow-hidden bg-slate-50 dark:bg-slate-900">
      <router-link :to="`/product/${product.slug}`">
        <img 
          :src="product.thumbnail ? (product.thumbnail.startsWith('http') ? product.thumbnail : `/storage/${product.thumbnail}`) : 'https://placehold.co/600x600/f8fafc/94a3b8?text=Product'" 
          :alt="product.name"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        >
      </router-link>
      
      <!-- Badges -->
      <div class="absolute top-3 left-3 flex flex-col gap-2">
        <span v-if="product.is_flash_sale" class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wider">Flash Sale</span>
        <span v-if="product.compare_price > product.price" class="bg-slate-900 text-white text-xs font-bold px-2 py-1 rounded-md">
          -{{ Math.round(((product.compare_price - product.price) / product.compare_price) * 100) }}%
        </span>
      </div>

      <!-- Quick Add (Desktop Hover) -->
      <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-300 hidden md:block">
        <button @click.prevent="addToCart" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-xl font-semibold shadow-lg transition-colors">
          Quick Add
        </button>
      </div>
    </div>

    <!-- Content Area -->
    <div class="p-5 flex flex-col flex-grow">
      <div class="text-xs text-slate-500 dark:text-slate-400 mb-1 font-medium">{{ product.category?.name }}</div>
      <router-link :to="`/product/${product.slug}`" class="block mb-2">
        <h3 class="font-bold text-lg text-slate-900 dark:text-white leading-tight line-clamp-2 hover:text-primary-500 transition-colors">
          {{ product.name }}
        </h3>
      </router-link>
      
      <div class="mt-auto pt-4 flex items-center justify-between">
        <div class="flex flex-col">
          <span v-if="product.compare_price > product.price" class="text-xs text-slate-400 line-through">
            ৳ {{ formatPrice(product.compare_price) }}
          </span>
          <span class="font-black text-lg text-slate-900 dark:text-white">
            ৳ {{ formatPrice(product.price) }}
          </span>
        </div>
        
        <!-- Mobile Add Button -->
        <button @click.prevent="addToCart" class="md:hidden bg-slate-100 hover:bg-primary-500 hover:text-white dark:bg-slate-700 dark:hover:bg-primary-500 text-slate-900 dark:text-white p-3 rounded-full transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '../stores/useCartStore';

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const cartStore = useCartStore();

const formatPrice = (price) => {
  return Number(price).toLocaleString('en-IN');
};

const addToCart = () => {
  cartStore.addToCart(props.product);
};
</script>
