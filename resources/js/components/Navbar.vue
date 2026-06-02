<template>
  <nav class="sticky top-0 z-50 glass w-full transition-all duration-300 border-b">
    <div class="container mx-auto px-4 h-20 flex items-center justify-between">
      
      <!-- Mobile Hamburger & Logo -->
      <div class="flex items-center gap-4">
        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="md:hidden p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
          </svg>
        </button>
        <router-link to="/" class="flex items-center gap-2">
          <span class="text-2xl font-black tracking-tighter text-gradient">PAOWAZAY</span>
        </router-link>
      </div>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center gap-8 font-medium">
        <router-link to="/" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">Home</router-link>
        <router-link to="/products" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">Shop</router-link>
        <router-link to="/featured" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">Featured</router-link>
        <router-link to="/about" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">About</router-link>
        <router-link to="/track-order" class="text-primary-600 dark:text-primary-400 font-bold hover:text-primary-700 transition-colors flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
          Track Order
        </router-link>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-4 sm:gap-6">
        <!-- Theme Toggle -->
        <button @click="themeStore.toggleTheme" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors text-slate-600 dark:text-slate-300">
          <svg v-if="themeStore.isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
        
        <!-- Search Button -->
        <button @click="openSearch" class="hover:text-primary-500 transition-colors hidden sm:block" id="navbar-search-btn">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
        
        <button @click="cartStore.toggleCart" class="relative hover:text-primary-500 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          <span v-if="cartStore.totalItems > 0" class="absolute -top-2 -right-2 bg-primary-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-fade-in">
            {{ cartStore.totalItems }}
          </span>
        </button>

        <router-link v-if="!authStore.isAuthenticated" to="/login" class="hidden md:flex items-center gap-2 bg-slate-900 text-white px-5 py-2 rounded-full hover:bg-slate-800 transition-colors dark:bg-primary-500 dark:hover:bg-primary-600 font-semibold">
          Sign In
        </router-link>
        <div v-else class="hidden md:block font-semibold">
            {{ authStore.user?.name }}
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div 
      v-show="isMobileMenuOpen" 
      class="md:hidden absolute top-20 inset-x-0 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-xl"
      @click="isMobileMenuOpen = false"
    >
      <div class="flex flex-col p-4 space-y-4 font-medium text-lg">
        <!-- Mobile Search -->
        <div class="relative" @click.stop>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search products..."
            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
            @input="onSearchInput"
          >
          <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <router-link to="/" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">Home</router-link>
        <router-link to="/products" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">Shop</router-link>
        <router-link to="/featured" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">Featured</router-link>
        <router-link to="/about" class="hover:text-primary-500 transition-colors" active-class="text-primary-500 font-bold">About</router-link>
        <router-link to="/track-order" class="text-primary-600 dark:text-primary-400 font-bold hover:text-primary-700 transition-colors">
          Track Order
        </router-link>
        <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
          <router-link v-if="!authStore.isAuthenticated" to="/login" class="text-slate-600 dark:text-slate-300">Sign In</router-link>
          <div v-else class="text-slate-600 dark:text-slate-300">Welcome, {{ authStore.user?.name }}</div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Search Overlay -->
  <teleport to="body">
    <transition name="search-overlay">
      <div 
        v-if="isSearchOpen" 
        class="fixed inset-0 z-[100] flex flex-col"
        @keydown.esc="closeSearch"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeSearch"></div>

        <!-- Search Panel -->
        <div class="relative z-10 w-full max-w-2xl mx-auto mt-24 px-4">
          <!-- Search Input Container -->
          <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 dark:border-slate-700">
              <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              <input
                ref="searchInputRef"
                v-model="searchQuery"
                type="text"
                placeholder="Search for products..."
                class="flex-1 bg-transparent text-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none"
                @input="onSearchInput"
                id="navbar-search-input"
              >
              <div class="flex items-center gap-2 shrink-0">
                <div v-if="searchLoading" class="w-5 h-5 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                <kbd class="hidden sm:inline-flex items-center px-2 py-0.5 text-xs font-medium text-slate-400 bg-slate-100 dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600">ESC</kbd>
              </div>
            </div>

            <!-- Search Results -->
            <div class="max-h-[60vh] overflow-y-auto">
              <!-- No query state -->
              <div v-if="!searchQuery" class="p-8 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <p class="font-medium">Start typing to search products</p>
                <p class="text-sm mt-1">Search by product name, category, or keyword</p>
              </div>

              <!-- Loading state -->
              <div v-else-if="searchLoading && !searchResults.length" class="p-6 space-y-4">
                <div v-for="n in 3" :key="n" class="flex items-center gap-4 animate-pulse">
                  <div class="w-14 h-14 bg-slate-200 dark:bg-slate-700 rounded-xl shrink-0"></div>
                  <div class="flex-1 space-y-2">
                    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                    <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-1/2"></div>
                  </div>
                </div>
              </div>

              <!-- No results -->
              <div v-else-if="searchQuery && !searchLoading && !searchResults.length" class="p-8 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 2a10 10 0 100 20 10 10 0 000-20z"></path></svg>
                <p class="font-medium">No products found</p>
                <p class="text-sm mt-1">Try a different search term</p>
              </div>

              <!-- Results list -->
              <div v-else class="divide-y divide-slate-100 dark:divide-slate-700">
                <router-link
                  v-for="product in searchResults"
                  :key="product.id"
                  :to="`/product/${product.slug}`"
                  class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer group/item"
                  @click="closeSearch"
                >
                  <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700 shrink-0 border border-slate-200 dark:border-slate-600">
                    <img 
                      :src="product.thumbnail ? (product.thumbnail.startsWith('http') ? product.thumbnail : `/storage/${product.thumbnail}`) : 'https://placehold.co/120x120/f8fafc/94a3b8?text=P'" 
                      :alt="product.name"
                      class="w-full h-full object-cover group-hover/item:scale-110 transition-transform duration-300"
                    >
                  </div>
                  <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-slate-900 dark:text-white truncate group-hover/item:text-primary-500 transition-colors">
                      {{ product.name }}
                    </h4>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span class="text-xs text-slate-400">{{ product.category?.name }}</span>
                      <span v-if="product.category?.name" class="text-slate-300 dark:text-slate-600">·</span>
                      <span class="font-bold text-sm text-primary-500">৳ {{ formatPrice(product.price) }}</span>
                      <span v-if="product.compare_price > product.price" class="text-xs text-slate-400 line-through">৳ {{ formatPrice(product.compare_price) }}</span>
                    </div>
                  </div>
                  <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover/item:text-primary-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </router-link>
              </div>

              <!-- View all results link -->
              <div v-if="searchResults.length && searchQuery" class="p-3 border-t border-slate-100 dark:border-slate-700">
                <router-link 
                  :to="{ path: '/products', query: { search: searchQuery } }"
                  class="flex items-center justify-center gap-2 py-2 text-sm font-semibold text-primary-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-colors"
                  @click="closeSearch"
                >
                  View all results for "{{ searchQuery }}"
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { ref, nextTick, onUnmounted } from 'vue';
import { useCartStore } from '../stores/useCartStore';
import { useAuthStore } from '../stores/useAuthStore';
import { useThemeStore } from '../stores/useThemeStore';
import api from '../utils/api';

const cartStore = useCartStore();
const authStore = useAuthStore();
const themeStore = useThemeStore();

const isMobileMenuOpen = ref(false);

// Search state
const isSearchOpen = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const searchLoading = ref(false);
const searchInputRef = ref(null);
let debounceTimer = null;

const formatPrice = (price) => {
  return Number(price).toLocaleString('en-IN');
};

const openSearch = () => {
  isSearchOpen.value = true;
  searchQuery.value = '';
  searchResults.value = [];
  nextTick(() => {
    searchInputRef.value?.focus();
  });
};

const closeSearch = () => {
  isSearchOpen.value = false;
  searchQuery.value = '';
  searchResults.value = [];
  if (debounceTimer) clearTimeout(debounceTimer);
};

const onSearchInput = () => {
  if (debounceTimer) clearTimeout(debounceTimer);

  const query = searchQuery.value.trim();
  if (!query) {
    searchResults.value = [];
    searchLoading.value = false;
    return;
  }

  searchLoading.value = true;
  debounceTimer = setTimeout(async () => {
    try {
      const { data } = await api.get('/storefront/products', {
        params: { search: query }
      });
      searchResults.value = data.data || [];
    } catch (error) {
      console.error('Search error:', error);
      searchResults.value = [];
    } finally {
      searchLoading.value = false;
    }
  }, 300);
};

onUnmounted(() => {
  if (debounceTimer) clearTimeout(debounceTimer);
});
</script>

<style scoped>
/* Search overlay transitions */
.search-overlay-enter-active {
  transition: opacity 0.2s ease-out;
}
.search-overlay-leave-active {
  transition: opacity 0.15s ease-in;
}
.search-overlay-enter-from,
.search-overlay-leave-to {
  opacity: 0;
}
.search-overlay-enter-active .relative {
  transition: transform 0.2s ease-out, opacity 0.2s ease-out;
}
.search-overlay-enter-from .relative {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
