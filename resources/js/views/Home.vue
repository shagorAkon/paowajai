<template>
  <div class="space-y-20 pb-20">
    
    <!-- Hero Slider -->
    <section v-if="productStore.homeData?.banners?.length" class="relative h-[70vh] bg-slate-950 overflow-hidden">
      <div class="absolute inset-0">
        <!-- Render first active banner for simplicity, or loop -->
        <div class="relative w-full h-full flex items-center">
          <div class="absolute inset-0 bg-slate-900/60 z-10"></div>
          <img 
            src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=2070&auto=format&fit=crop" 
            alt="Hero Banner"
            class="absolute inset-0 w-full h-full object-cover"
          >
          <div class="container mx-auto px-4 z-20 relative text-white space-y-6 max-w-4xl animate-slide-up">
            <span class="text-primary-400 font-bold uppercase tracking-widest text-sm">{{ productStore.homeData.banners[0].subtitle }}</span>
            <h1 class="text-5xl md:text-7xl font-black tracking-tight leading-none">
              {{ productStore.homeData.banners[0].title }}
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-xl">
              Elevate your retail experience with curated Chinese imports, trending fashion, and premium tech goods.
            </p>
            <div class="pt-4">
              <router-link :to="productStore.homeData.banners[0].link || '/products'" class="bg-primary-500 hover:bg-primary-600 text-white font-bold px-8 py-4 rounded-xl transition duration-300 shadow-lg inline-block">
                {{ productStore.homeData.banners[0].button_text || 'Shop Collection' }}
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Categories -->
    <section class="container mx-auto px-4">
      <div class="text-center max-w-2xl mx-auto mb-12">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Browse Categories</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Explore our premium catalog designed to delight.</p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        <router-link 
          v-for="cat in productStore.homeData?.categories" 
          :key="cat.id" 
          :to="`/category/${cat.slug}`"
          class="group glass-card p-6 flex flex-col items-center justify-center text-center gap-4 hover:border-primary-500 hover:scale-105 transition-all duration-300 cursor-pointer"
        >
          <div class="text-4xl w-16 h-16 rounded-2xl bg-primary-50 dark:bg-slate-700/50 flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors duration-300">
            {{ cat.icon || '📦' }}
          </div>
          <span class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ cat.name }}</span>
        </router-link>
      </div>
    </section>

    <!-- Flash Sales Section -->
    <section v-if="productStore.homeData?.flash_sale_products?.length" class="bg-red-50/50 dark:bg-red-950/20 py-16 border-y border-red-100 dark:border-red-900/50">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
          <div>
            <span class="bg-red-500 text-white font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-md">Limited Time</span>
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mt-3">Flash Sale Events</h2>
          </div>
          <router-link to="/products?flash=1" class="text-red-500 font-bold hover:underline flex items-center gap-1">
            See All Offers
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </router-link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
          <ProductCard 
            v-for="product in productStore.homeData.flash_sale_products" 
            :key="product.id" 
            :product="product" 
          />
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="container mx-auto px-4">
      <div class="flex items-end justify-between mb-12">
        <div>
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Featured Favorites</h2>
          <p class="text-slate-500 dark:text-slate-400 mt-2">Curated high-quality options trending this season.</p>
        </div>
        <router-link to="/products?featured=1" class="text-primary-500 font-bold hover:underline flex items-center gap-1">
          Explore All
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </router-link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard 
          v-for="product in productStore.homeData?.featured_products" 
          :key="product.id" 
          :product="product" 
        />
      </div>
    </section>

    <!-- New Arrivals -->
    <section class="container mx-auto px-4">
      <div class="flex items-end justify-between mb-12">
        <div>
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">New Arrivals</h2>
          <p class="text-slate-500 dark:text-slate-400 mt-2">Fresh arrivals directly from manufacturer.</p>
        </div>
        <router-link to="/products" class="text-primary-500 font-bold hover:underline flex items-center gap-1">
          View All New
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </router-link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard 
          v-for="product in productStore.homeData?.new_arrivals" 
          :key="product.id" 
          :product="product" 
        />
      </div>
    </section>

  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useProductStore } from '../stores/useProductStore';
import ProductCard from '../components/ProductCard.vue';

const productStore = useProductStore();

onMounted(() => {
  productStore.fetchHomeData();
});
</script>
