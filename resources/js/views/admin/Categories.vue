<template>
  <div>
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Categories</h2>
        <p class="text-slate-500 dark:text-slate-400">Manage your product categories.</p>
      </div>
      <router-link to="/admin/categories/create" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-colors shadow-lg shadow-primary-500/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Category
      </router-link>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden relative">
      <div v-if="loading" class="absolute inset-0 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm flex items-center justify-center z-10">
        <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
          <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold">
            <tr>
              <th class="px-6 py-4">Category Name</th>
              <th class="px-6 py-4">Slug</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            <tr v-if="categories.length === 0 && !loading">
              <td colspan="4" class="px-6 py-12 text-center text-slate-500">No categories found.</td>
            </tr>
            <template v-for="category in categories" :key="category.id">
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                <td class="px-6 py-4 font-bold">{{ category.name }}</td>
                <td class="px-6 py-4 text-slate-500">{{ category.slug }}</td>
                <td class="px-6 py-4">
                  <span v-if="category.is_active" class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                  <span v-else class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">Draft</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <router-link :to="`/admin/categories/${category.id}/edit`" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">Edit</router-link>
                    <button @click="deleteCategory(category.id)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">Delete</button>
                  </div>
                </td>
              </tr>
              <!-- Render children -->
              <tr v-for="child in category.children" :key="child.id" class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors bg-slate-50/50 dark:bg-slate-900/30">
                <td class="px-6 py-4 pl-12 flex items-center gap-2">
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                  <span class="font-medium text-slate-700 dark:text-slate-300">{{ child.name }}</span>
                </td>
                <td class="px-6 py-4 text-slate-500">{{ child.slug }}</td>
                <td class="px-6 py-4">
                  <span v-if="child.is_active" class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                  <span v-else class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">Draft</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <router-link :to="`/admin/categories/${child.id}/edit`" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">Edit</router-link>
                    <button @click="deleteCategory(child.id)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">Delete</button>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../utils/api';

const categories = ref([]);
const loading = ref(false);

const fetchCategories = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/categories');
    categories.value = data.data; // Assuming paginated
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const deleteCategory = async (id) => {
  if (confirm('Are you sure you want to delete this category?')) {
    try {
      await api.delete(`/admin/categories/${id}`);
      fetchCategories();
    } catch (err) {
      alert('Failed to delete category.');
    }
  }
};

onMounted(() => fetchCategories());
</script>
