<template>
  <div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Products</h2>
        <p class="text-slate-500 dark:text-slate-400">Manage your product catalog.</p>
      </div>
      <router-link to="/admin/products/create" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-colors shadow-lg shadow-primary-500/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Product
      </router-link>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6 flex flex-col sm:flex-row gap-4">
      <div class="flex-1 relative">
        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <input v-model="search" @input="fetchProducts" type="text" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white transition-colors">
      </div>
      <div class="flex gap-4">
        <select v-model="filterStatus" @change="fetchProducts" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
          <option value="">All Status</option>
          <option value="1">Active</option>
          <option value="0">Draft</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden relative">
      <!-- Loading Overlay -->
      <div v-if="loading" class="absolute inset-0 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm flex items-center justify-center z-10">
        <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
          <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold">
            <tr>
              <th class="px-6 py-4">Product</th>
              <th class="px-6 py-4">SKU</th>
              <th class="px-6 py-4">Price</th>
              <th class="px-6 py-4">Stock</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            <tr v-if="products.length === 0 && !loading">
              <td colspan="6" class="px-6 py-12 text-center text-slate-500">No products found.</td>
            </tr>
            <tr v-for="product in products" :key="product.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <img :src="product.thumbnail ? `/storage/${product.thumbnail}` : 'https://placehold.co/100x100'" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                  <div>
                    <p class="font-bold text-slate-900 dark:text-white line-clamp-1 max-w-[200px]">{{ product.name }}</p>
                    <p class="text-xs text-slate-500">{{ product.category?.name }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ product.sku || '-' }}</td>
              <td class="px-6 py-4 font-bold">৳ {{ Number(product.price).toLocaleString('en-IN') }}</td>
              <td class="px-6 py-4">
                <span :class="product.stock_quantity <= product.low_stock_threshold ? 'text-red-500 font-bold' : ''">{{ product.stock_quantity }}</span>
              </td>
              <td class="px-6 py-4">
                <span v-if="product.is_active" class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">Active</span>
                <span v-else class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400">Draft</span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <router-link :to="`/admin/products/${product.id}/edit`" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                  </router-link>
                  <button @click="deleteProduct(product.id)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination Controls (Simple) -->
      <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center text-sm">
        <span class="text-slate-500">Showing page {{ currentPage }} of {{ totalPages }}</span>
        <div class="flex gap-2">
          <button @click="currentPage--; fetchProducts()" :disabled="currentPage <= 1" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50">Prev</button>
          <button @click="currentPage++; fetchProducts()" :disabled="currentPage >= totalPages" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-50">Next</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../utils/api';

const products = ref([]);
const loading = ref(false);
const search = ref('');
const filterStatus = ref('');
const currentPage = ref(1);
const totalPages = ref(1);

const fetchProducts = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/products', {
      params: {
        page: currentPage.value,
        search: search.value,
        is_active: filterStatus.value
      }
    });
    products.value = data.data;
    totalPages.value = data.last_page;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const deleteProduct = async (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    try {
      await api.delete(`/admin/products/${id}`);
      fetchProducts();
    } catch (err) {
      alert('Failed to delete product.');
    }
  }
};

onMounted(() => {
  fetchProducts();
});
</script>
