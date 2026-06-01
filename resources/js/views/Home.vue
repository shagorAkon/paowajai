<template>
  <div class="space-y-24 pb-24">
    
    <!-- Premium Hero Carousel -->
    <section v-if="displayBanners.length" class="relative h-[70vh] min-h-[500px] bg-slate-950 overflow-hidden group">
      <div 
        v-for="(banner, index) in displayBanners" 
        :key="banner.id"
        class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
        :class="index === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0'"
      >
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-slate-900/40 z-10"></div>
        <img 
          :src="banner.image ? `/storage/${banner.image}` : (banner.fallbackImage || 'https://images.pexels.com/photos/135620/pexels-photo-135620.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2')" 
          :alt="banner.title"
          class="absolute inset-0 w-full h-full object-cover transition-transform duration-[10000ms] ease-out"
          :class="index === currentSlide ? 'scale-105' : 'scale-100'"
        >
        
        <div class="container mx-auto px-4 z-20 relative text-white h-full flex items-center">
          <div class="max-w-3xl space-y-6" :class="{ 'animate-slide-left': index === currentSlide }">
            <span class="text-primary-400 font-bold uppercase tracking-widest text-sm bg-primary-900/30 px-3 py-1 rounded-full border border-primary-500/30">
              {{ banner.subtitle || 'Exclusive Offer' }}
            </span>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tight leading-none text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-300">
              {{ banner.title }}
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-xl font-light">
              Elevate your retail experience with curated premium goods, trending fashion, and cutting-edge tech.
            </p>
            <div class="pt-6">
              <router-link :to="banner.link || '/products'" class="bg-primary-500 hover:bg-primary-600 text-white font-bold px-10 py-4 rounded-full transition-all duration-300 shadow-[0_0_20px_rgba(20,184,166,0.4)] hover:shadow-[0_0_30px_rgba(20,184,166,0.6)] hover:-translate-y-1 inline-block uppercase tracking-wider text-sm">
                {{ banner.button_text || 'Shop Collection' }}
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
      <button @click="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-sm border border-white/10 transition-all opacity-0 group-hover:opacity-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </button>
      <button @click="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white backdrop-blur-sm border border-white/10 transition-all opacity-0 group-hover:opacity-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      </button>

      <!-- Carousel Indicators -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex gap-3">
        <button 
          v-for="(_, index) in displayBanners" 
          :key="index"
          @click="setSlide(index)"
          class="h-2 rounded-full transition-all duration-300"
          :class="index === currentSlide ? 'w-10 bg-primary-500' : 'w-2 bg-white/50 hover:bg-white/80'"
        ></button>
      </div>
    </section>

    <!-- Trust Badges Section -->
    <section class="container mx-auto px-4 -mt-16 relative z-30 hidden md:block">
      <div class="glass-card premium-shadow rounded-2xl p-8 grid grid-cols-4 divide-x divide-slate-200 dark:divide-slate-700">
        <div class="flex items-center gap-4 justify-center px-4">
          <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
          </div>
          <div>
            <h4 class="font-bold text-sm">Free Delivery</h4>
            <p class="text-xs text-slate-500">Orders over ৳5,000</p>
          </div>
        </div>
        <div class="flex items-center gap-4 justify-center px-4">
          <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
          </div>
          <div>
            <h4 class="font-bold text-sm">Secure Payment</h4>
            <p class="text-xs text-slate-500">100% secure checkout</p>
          </div>
        </div>
        <div class="flex items-center gap-4 justify-center px-4">
          <div class="w-12 h-12 rounded-full bg-green-50 dark:bg-green-900/20 text-green-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          </div>
          <div>
            <h4 class="font-bold text-sm">Genuine Products</h4>
            <p class="text-xs text-slate-500">Sourced from brands</p>
          </div>
        </div>
        <div class="flex items-center gap-4 justify-center px-4">
          <div class="w-12 h-12 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-500 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          </div>
          <div>
            <h4 class="font-bold text-sm">24/7 Support</h4>
            <p class="text-xs text-slate-500">Dedicated assistance</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Categories -->
    <section class="container mx-auto px-4 mt-16 md:mt-0">
      <div class="flex items-end justify-between mb-10">
        <div>
          <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Browse Categories</h2>
          <p class="text-slate-500 dark:text-slate-400 mt-2">Explore our premium catalog designed to delight.</p>
        </div>
        <router-link to="/products" class="hidden md:flex items-center gap-1 text-primary-500 font-bold hover:underline">
          View All <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </router-link>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        <router-link 
          v-for="cat in productStore.homeData?.categories" 
          :key="cat.id" 
          :to="`/category/${cat.slug}`"
          class="group glass-card premium-shadow p-6 flex flex-col items-center justify-center text-center gap-4 hover-lift cursor-pointer border-t-4 border-t-transparent hover:border-t-primary-500"
        >
          <div class="text-4xl w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors duration-300">
            {{ cat.icon || '📦' }}
          </div>
          <span class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ cat.name }}</span>
        </router-link>
      </div>
    </section>

    <!-- Promotional Banner -->
    <section class="container mx-auto px-4">
      <div class="relative rounded-3xl overflow-hidden premium-shadow bg-slate-900 group">
        <img src="https://images.pexels.com/photos/974911/pexels-photo-974911.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Promo" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:scale-105 transition-transform duration-700">
        <div class="relative z-10 p-12 md:p-20 flex flex-col md:flex-row items-center justify-between gap-8">
          <div class="max-w-xl text-center md:text-left">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-4">Summer Luxury Collection</h2>
            <p class="text-lg text-slate-300">Up to 40% off on premium fashion accessories and electronics. Limited time offer.</p>
          </div>
          <router-link to="/featured" class="bg-white text-slate-900 hover:bg-primary-500 hover:text-white font-bold px-8 py-4 rounded-full transition-colors shrink-0 uppercase tracking-wider text-sm shadow-xl">
            Explore Collection
          </router-link>
        </div>
      </div>
    </section>

    <!-- Flash Sales Section -->
    <section v-if="productStore.homeData?.flash_sale_products?.length" class="bg-red-50/50 dark:bg-red-950/20 py-16 border-y border-red-100 dark:border-red-900/50">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
          <div>
            <span class="bg-red-500 text-white font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-full shadow-sm animate-pulse">Live Now</span>
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mt-3 text-red-950 dark:text-red-100">Flash Sale Events</h2>
          </div>
          <router-link to="/featured" class="text-red-500 font-bold hover:underline flex items-center gap-1">
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
        <router-link to="/featured" class="text-primary-500 font-bold hover:underline flex items-center gap-1">
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

    <!-- Newsletter CTA -->
    <section class="container mx-auto px-4">
      <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-12 text-center text-white premium-shadow relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="relative z-10 max-w-2xl mx-auto space-y-6">
          <h2 class="text-3xl md:text-4xl font-black">Join The Exclusive Club</h2>
          <p class="text-slate-400 text-lg">Subscribe to our newsletter and get 10% off your first premium order. Be the first to know about new arrivals.</p>
          <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto pt-4">
            <input type="email" placeholder="Enter your email address" class="flex-1 px-6 py-4 rounded-full bg-white/10 border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 backdrop-blur-md">
            <button class="bg-primary-500 hover:bg-primary-600 text-white font-bold px-8 py-4 rounded-full transition-colors shadow-lg">
              Subscribe
            </button>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useProductStore } from '../stores/useProductStore';
import ProductCard from '../components/ProductCard.vue';

const productStore = useProductStore();
const currentSlide = ref(0);
let slideInterval = null;

const defaultBanners = [
  {
    id: 'default-1',
    title: 'Premium E-Commerce Experience',
    subtitle: 'Exclusive Offer',
    image: null,
    fallbackImage: 'https://images.pexels.com/photos/5632402/pexels-photo-5632402.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
    link: '/products',
    button_text: 'Shop Collection'
  },
  {
    id: 'default-2',
    title: 'Discover Next-Gen Tech',
    subtitle: 'New Arrivals',
    image: null,
    fallbackImage: 'https://images.pexels.com/photos/39284/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
    link: '/products',
    button_text: 'Explore Now'
  },
  {
    id: 'default-3',
    title: 'Elevate Your Wardrobe',
    subtitle: 'Trending Fashion',
    image: null,
    fallbackImage: 'https://images.pexels.com/photos/974911/pexels-photo-974911.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
    link: '/products',
    button_text: 'View Fashion'
  }
];

const displayBanners = computed(() => {
  return productStore.homeData?.banners?.length 
    ? productStore.homeData.banners 
    : defaultBanners;
});

const nextSlide = () => {
  if (!displayBanners.value.length) return;
  currentSlide.value = (currentSlide.value + 1) % displayBanners.value.length;
};

const prevSlide = () => {
  if (!displayBanners.value.length) return;
  currentSlide.value = currentSlide.value === 0 
    ? displayBanners.value.length - 1 
    : currentSlide.value - 1;
};

const setSlide = (index) => {
  currentSlide.value = index;
};

const startCarousel = () => {
  if (slideInterval) clearInterval(slideInterval);
  slideInterval = setInterval(nextSlide, 5000);
};

onMounted(async () => {
  await productStore.fetchHomeData();
  if (displayBanners.value.length > 1) {
    startCarousel();
  }
});

onUnmounted(() => {
  if (slideInterval) clearInterval(slideInterval);
});
</script>
