<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
      
      <div class="p-8">
        <div class="text-center mb-8">
          <router-link to="/" class="inline-block mb-4">
            <span class="text-3xl font-black tracking-tighter text-gradient">PAOWAZAY</span>
          </router-link>
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome Back</h2>
          <p class="text-slate-500 dark:text-slate-400 mt-2">Sign in to your admin account</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div v-if="error" class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 p-4 rounded-xl text-sm font-medium border border-red-100 dark:border-red-500/20">
            {{ error }}
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Address</label>
            <input 
              v-model="form.email" 
              type="email" 
              required
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors outline-none dark:text-white"
              placeholder="admin@paowazay.com"
            >
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
              <a href="#" class="text-sm font-medium text-primary-500 hover:text-primary-600 transition-colors">Forgot?</a>
            </div>
            <input 
              v-model="form.password" 
              type="password" 
              required
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors outline-none dark:text-white"
              placeholder="••••••••"
            >
          </div>

          <button 
            type="submit" 
            :disabled="authStore.loading"
            class="w-full bg-primary-500 hover:bg-primary-600 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-primary-500/30 flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed"
          >
            <svg v-if="authStore.loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            {{ authStore.loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/useAuthStore';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  email: '',
  password: ''
});

const error = ref('');

const handleLogin = async () => {
  try {
    error.value = '';
    await authStore.login(form.value);
    
    // Redirect logic based on role
    if (authStore.isAdmin) {
      router.push('/admin/dashboard');
    } else {
      router.push('/');
    }
  } catch (err) {
    if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors)[0][0];
    } else if (err.response?.data?.message) {
      error.value = err.response.data.message;
    } else {
      error.value = 'Failed to sign in. Please try again.';
    }
  }
};
</script>
