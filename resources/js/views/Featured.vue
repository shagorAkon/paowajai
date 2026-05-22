<template>
  <div class="container mx-auto px-4 py-12 space-y-12">
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4 animate-slide-up">
      <span class="text-primary-500 font-bold uppercase tracking-widest text-sm">Curated Collection</span>
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Featured & Trending</h1>
      <p class="text-lg text-slate-500 dark:text-slate-400">
        Discover our handpicked selection of premium products, trending items, and exclusive flash sales.
      </p>
    </div>

    <!-- Tabs -->
    <div class="flex justify-center border-b border-slate-200 dark:border-slate-800">
      <div class="flex space-x-8">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'pb-4 font-bold text-lg transition-colors border-b-2',
            activeTab === tab.id 
              ? 'text-primary-500 border-primary-500' 
              : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white border-transparent'
          ]"
        >
          {{ tab.name }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      <div v-for="n in 8" :key="n" class="animate-pulse space-y-4">
        <div class="bg-slate-200 dark:bg-slate-700 aspect-square rounded-2xl"></div>
        <div class="h-4 bg-slate-200 dark:bg-slate-700 w-2/3 rounded"></div>
        <div class="h-4 bg-slate-200 dark:bg-slate-700 w-1/2 rounded"></div>
      </div>
    </div>

    <!-- Products Grid -->
    <div v-else>
      <!-- Featured Tab -->
      <div v-show="activeTab === 'featured'" class="animate-fade-in grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
        <div v-if="!featuredProducts.length" class="col-span-full text-center py-12 text-slate-500">
          No featured products available at the moment.
        </div>
      </div>

      <!-- Trending Tab -->
      <div v-show="activeTab === 'trending'" class="animate-fade-in grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard v-for="product in trendingProducts" :key="product.id" :product="product" />
        <div v-if="!trendingProducts.length" class="col-span-full text-center py-12 text-slate-500">
          No trending products available at the moment.
        </div>
      </div>

      <!-- Flash Sale Tab -->
      <div v-show="activeTab === 'flash'" class="animate-fade-in grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard v-for="product in flashSaleProducts" :key="product.id" :product="product" />
        <div v-if="!flashSaleProducts.length" class="col-span-full text-center py-12 text-slate-500">
          No flash sales active right now.
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../utils/api';
import ProductCard from '../components/ProductCard.vue';

const activeTab = ref('featured');
const loading = ref(true);

const tabs = [
  { id: 'featured', name: 'Featured' },
  { id: 'trending', name: 'Trending' },
  { id: 'flash', name: 'Flash Sale' },
];

const featuredProducts = ref([]);
const trendingProducts = ref([]);
const flashSaleProducts = ref([]);

const loadData = async () => {
  loading.value = true;
  try {
    const [featuredRes, flashRes] = await Promise.all([
      api.get('/storefront/products/featured'),
      api.get('/storefront/products/flash-sale')
    ]);
    
    // Fallback: If APIs return paginated data (data.data) or direct arrays
    featuredProducts.value = featuredRes.data.data || featuredRes.data || [];
    flashSaleProducts.value = flashRes.data.data || flashRes.data || [];
    
    // For trending, we can just use the first few featured products as a demo if no distinct API exists
    trendingProducts.value = [...featuredProducts.value].sort(() => 0.5 - Math.random()).slice(0, 4);

  } catch (error) {
    console.error('Failed to load featured data:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>
