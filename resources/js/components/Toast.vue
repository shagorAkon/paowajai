<template>
  <div class="fixed top-5 right-5 z-[200] flex flex-col gap-3 pointer-events-none">
    <transition-group name="toast">
      <div 
        v-for="toast in toastStore.toasts" 
        :key="toast.id"
        class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl min-w-[280px] max-w-sm border backdrop-blur-md"
        :class="[
          toast.type === 'success' ? 'bg-green-500/90 border-green-400 text-white' : 
          toast.type === 'error' ? 'bg-red-500/90 border-red-400 text-white' : 
          'bg-slate-800/90 border-slate-700 text-white'
        ]"
      >
        <div class="flex-shrink-0">
          <svg v-if="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <svg v-else-if="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="flex-1 text-sm font-semibold">{{ toast.message }}</p>
        <button @click="toastStore.remove(toast.id)" class="text-white/80 hover:text-white transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useToastStore } from '../stores/useToastStore';

const toastStore = useToastStore();
</script>

<style>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(30px);
}
.toast-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}
</style>
