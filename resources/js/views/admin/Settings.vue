<template>
  <div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Store Settings</h2>
        <p class="text-slate-500 dark:text-slate-400">Manage global store configurations.</p>
      </div>
      <button @click="saveSettings" :disabled="loading" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl transition-colors shadow-lg flex items-center gap-2">
        <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        {{ loading ? 'Saving...' : 'Save Settings' }}
      </button>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
      <!-- Sidebar Navigation -->
      <div class="md:w-64 shrink-0">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col">
          <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" 
            :class="[activeTab === tab.id ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 border-l-4 border-primary-500 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium border-l-4 border-transparent', 'text-left px-5 py-4 text-sm transition-all border-b border-slate-50 dark:border-slate-700/50 last:border-0']">
            {{ tab.name }}
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-1">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 md:p-8 min-h-[500px]">
          
          <!-- GENERAL SETTINGS -->
          <div v-show="activeTab === 'general'" class="space-y-6 animate-fade-in">
            <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2">General Information</h3>
            <div>
              <label class="block text-sm font-semibold mb-2">Store Name</label>
              <input v-model="settings.store_name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold mb-2">Support Email</label>
                <input v-model="settings.support_email" type="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2">Support Phone</label>
                <input v-model="settings.support_phone" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">Store Address</label>
              <textarea v-model="settings.store_address" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none"></textarea>
            </div>
          </div>

          <!-- SEO SETTINGS -->
          <div v-show="activeTab === 'seo'" class="space-y-6 animate-fade-in">
            <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2">Default SEO</h3>
            <div>
              <label class="block text-sm font-semibold mb-2">Meta Title</label>
              <input v-model="settings.seo_meta_title" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">Meta Description</label>
              <textarea v-model="settings.seo_meta_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none"></textarea>
            </div>
          </div>

          <!-- SHIPPING SETTINGS -->
          <div v-show="activeTab === 'shipping'" class="space-y-6 animate-fade-in">
            <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2">Shipping Zones & Rates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold mb-2">Inside Dhaka Rate (৳)</label>
                <input v-model="settings.shipping_inside_dhaka" type="number" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2">Outside Dhaka Rate (৳)</label>
                <input v-model="settings.shipping_outside_dhaka" type="number" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">Free Shipping Threshold (৳)</label>
              <input v-model="settings.free_shipping_threshold" type="number" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
              <p class="text-xs text-slate-500 mt-1">Set to 0 to disable free shipping</p>
            </div>
          </div>

          <!-- SOCIAL SETTINGS -->
          <div v-show="activeTab === 'social'" class="space-y-6 animate-fade-in">
            <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2">Social Media Links</h3>
            <div>
              <label class="block text-sm font-semibold mb-2">Facebook URL</label>
              <input v-model="settings.social_facebook" type="url" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div>
              <label class="block text-sm font-semibold mb-2">Instagram URL</label>
              <input v-model="settings.social_instagram" type="url" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div class="pt-6">
              <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2 mb-4">Marketing Tools</h3>
              <label class="block text-sm font-semibold mb-2">Facebook Pixel ID</label>
              <input v-model="settings.fb_pixel_id" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../utils/api';

const loading = ref(false);
const activeTab = ref('general');
const tabs = [
  { id: 'general', name: 'General Settings' },
  { id: 'seo', name: 'SEO Optimization' },
  { id: 'shipping', name: 'Shipping Rates' },
  { id: 'payment', name: 'Payment Gateways' },
  { id: 'social', name: 'Social & Marketing' },
  { id: 'theme', name: 'Theme Colors' },
];

const settings = ref({
  store_name: '',
  support_email: '',
  support_phone: '',
  store_address: '',
  seo_meta_title: '',
  seo_meta_description: '',
  shipping_inside_dhaka: 0,
  shipping_outside_dhaka: 0,
  free_shipping_threshold: 0,
  social_facebook: '',
  social_instagram: '',
  fb_pixel_id: ''
});

const fetchSettings = async () => {
  try {
    const { data } = await api.get('/admin/settings');
    // Merge returned data with defaults
    Object.keys(data).forEach(key => {
      if (settings.value[key] !== undefined) {
        settings.value[key] = data[key];
      }
    });
  } catch (err) {
    console.error(err);
  }
};

const saveSettings = async () => {
  loading.value = true;
  try {
    await api.post('/admin/settings', settings.value);
    alert('Settings saved successfully!');
  } catch (err) {
    alert('Failed to save settings.');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchSettings();
});
</script>
