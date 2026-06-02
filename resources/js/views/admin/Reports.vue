<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Reports</h2>
        <p class="text-slate-500 dark:text-slate-400">Generate and export reports for your store.</p>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 max-w-2xl">
      <div class="space-y-6">
        <div>
          <label class="block text-sm font-medium mb-2">Report Type</label>
          <select v-model="form.type" class="w-full px-4 py-2 rounded-xl border dark:border-slate-600 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-primary-500">
            <option value="sales">Sales Report (All Orders)</option>
            <option value="revenue">Revenue Report (Paid Orders Only)</option>
          </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Start Date</label>
            <input type="date" v-model="form.start_date" class="w-full px-4 py-2 rounded-xl border dark:border-slate-600 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-primary-500">
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">End Date</label>
            <input type="date" v-model="form.end_date" class="w-full px-4 py-2 rounded-xl border dark:border-slate-600 dark:bg-slate-900 outline-none focus:ring-2 focus:ring-primary-500">
          </div>
        </div>

        <button @click="exportReport" :disabled="loading" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors disabled:opacity-50">
          {{ loading ? 'Generating...' : 'Export to CSV' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../../utils/api';

const form = ref({
  type: 'sales',
  start_date: new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0],
  end_date: new Date().toISOString().split('T')[0],
});

const loading = ref(false);

const exportReport = async () => {
  loading.value = true;
  try {
    const response = await api.get('/admin/dashboard/reports/export', {
      params: form.value,
      responseType: 'blob'
    });

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `${form.value.type}_report_${new Date().getTime()}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error('Export failed', error);
    alert('Failed to export report');
  } finally {
    loading.value = false;
  }
};
</script>
