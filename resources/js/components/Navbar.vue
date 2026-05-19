<template>
  <nav class="sticky top-0 z-50 glass w-full transition-all duration-300 border-b">
    <div class="container mx-auto px-4 h-20 flex items-center justify-between">
      
      <!-- Logo -->
      <router-link to="/" class="flex items-center gap-2">
        <span class="text-2xl font-black tracking-tighter text-gradient">PAOWAJAI</span>
      </router-link>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center gap-8 font-medium">
        <router-link to="/" class="hover:text-primary-500 transition-colors">Home</router-link>
        <router-link to="/products" class="hover:text-primary-500 transition-colors">Shop</router-link>
        <router-link to="/products?featured=1" class="hover:text-primary-500 transition-colors">Featured</router-link>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-6">
        <button class="hover:text-primary-500 transition-colors">
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
  </nav>
</template>

<script setup>
import { useCartStore } from '../stores/useCartStore';
import { useAuthStore } from '../stores/useAuthStore';

const cartStore = useCartStore();
const authStore = useAuthStore();
</script>
