<template>
  <div class="container mx-auto px-4 py-12 space-y-12">
    <!-- Header -->
    <div class="space-y-4">
      <h1 class="text-4xl font-extrabold tracking-tight">
        {{ currentCategoryName || 'All Products' }}
      </h1>
      <p class="text-slate-500 dark:text-slate-400">Discover premium goods imported directly for you.</p>
    </div>

    <!-- Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Filters Sidebar (Desktop) -->
      <aside class="space-y-8 hidden lg:block">
        <!-- Search -->
        <div class="space-y-3">
          <h3 class="font-bold text-lg">Search</h3>
          <div class="relative">
            <input 
              v-model="filters.search" 
              type="text" 
              placeholder="Search products..." 
              class="w-full px-4 py-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 focus:outline-none focus:border-primary-500"
              @input="handleSearch"
            >
          </div>
        </div>

        <!-- Categories Filter -->
        <div class="space-y-3">
          <h3 class="font-bold text-lg">Categories</h3>
          <ul class="space-y-2">
            <li>
              <router-link 
                to="/products" 
                class="hover:text-primary-500 font-medium transition"
                :class="[!route.params.slug ? 'text-primary-500 font-bold' : 'text-slate-600 dark:text-slate-400']"
              >
                All Categories
              </router-link>
            </li>
            <li v-for="cat in productStore.categories" :key="cat.id">
              <router-link 
                :to="`/category/${cat.slug}`" 
                class="hover:text-primary-500 font-medium transition"
                :class="[route.params.slug === cat.slug ? 'text-primary-500 font-bold' : 'text-slate-600 dark:text-slate-400']"
              >
                {{ cat.name }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Price Filter -->
        <div class="space-y-3">
          <h3 class="font-bold text-lg">Price Filter</h3>
          <div class="flex items-center gap-2">
            <input 
              v-model="filters.min_price" 
              type="number" 
              placeholder="Min" 
              class="w-full px-3 py-2 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 text-sm"
            >
            <span>-</span>
            <input 
              v-model="filters.max_price" 
              type="number" 
              placeholder="Max" 
              class="w-full px-3 py-2 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 text-sm"
            >
            <button @click="applyFilters" class="bg-primary-500 text-white p-2 rounded-lg hover:bg-primary-600 transition">
              Apply
            </button>
          </div>
        </div>
      </aside>

      <!-- Listing -->
      <div class="lg:col-span-3 space-y-12">
        <div v-if="productStore.loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
          <div v-for="n in 6" :key="n" class="animate-pulse space-y-4">
            <div class="bg-slate-200 dark:bg-slate-700 aspect-square rounded-2xl"></div>
            <div class="h-4 bg-slate-200 dark:bg-slate-700 w-2/3 rounded"></div>
            <div class="h-4 bg-slate-200 dark:bg-slate-700 w-1/2 rounded"></div>
          </div>
        </div>

        <div v-else-if="!productStore.products.length" class="text-center py-20 text-slate-500">
          <p class="text-xl font-semibold">No products found.</p>
          <p class="mt-2">Try adjusting your filters or search criteria.</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
          <ProductCard 
            v-for="product in productStore.products" 
            :key="product.id" 
            :product="product" 
          />
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, watch, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useProductStore } from '../stores/useProductStore';
import ProductCard from '../components/ProductCard.vue';

const route = useRoute();
const productStore = useProductStore();

const filters = reactive({
  search: route.query.search || '',
  min_price: '',
  max_price: '',
  category: route.params.slug || '',
});

const currentCategoryName = computed(() => {
  if (!route.params.slug) return null;
  const cat = productStore.categories.find(c => c.slug === route.params.slug);
  return cat ? cat.name : null;
});

const loadProducts = () => {
  const params = {
    search: filters.search,
    min_price: filters.min_price,
    max_price: filters.max_price,
    category: route.params.slug || '',
  };
  productStore.fetchProducts(params);
};

const handleSearch = () => {
  // Simple debounce
  loadProducts();
};

const applyFilters = () => {
  loadProducts();
};

watch(() => route.params.slug, (newSlug) => {
  filters.category = newSlug || '';
  loadProducts();
});

// Watch for search query param changes (from navbar search "View all results" link)
watch(() => route.query.search, (newSearch) => {
  filters.search = newSearch || '';
  loadProducts();
});

onMounted(() => {
  productStore.fetchCategories();
  loadProducts();
});
</script>
