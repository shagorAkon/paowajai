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
          <span class="text-2xl font-black tracking-tighter text-gradient">PAOWAJAI</span>
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
        
        <button class="hover:text-primary-500 transition-colors hidden sm:block">
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
</template>

<script setup>
import { ref } from 'vue';
import { useCartStore } from '../stores/useCartStore';
import { useAuthStore } from '../stores/useAuthStore';
import { useThemeStore } from '../stores/useThemeStore';

const cartStore = useCartStore();
const authStore = useAuthStore();
const themeStore = useThemeStore();

const isMobileMenuOpen = ref(false);
</script>
