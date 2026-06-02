<template>
  <div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ isEdit ? 'Edit Category' : 'Add Category' }}</h2>
      </div>
      <div class="flex gap-3">
        <router-link to="/admin/categories" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 font-bold py-2.5 px-5 rounded-xl transition-colors">Cancel</router-link>
        <button @click="saveCategory" :disabled="loading" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl transition-colors">
          {{ loading ? 'Saving...' : 'Save' }}
        </button>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 space-y-6">
      <div>
        <label class="block text-sm font-semibold mb-2">Category Name <span class="text-red-500">*</span></label>
        <input v-model="form.name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-2">Parent Category</label>
        <select v-model="form.parent_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none">
          <option :value="null">None (Root Category)</option>
          <option v-for="cat in parentOptions" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-semibold mb-2">Description</label>
        <textarea v-model="form.description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none"></textarea>
      </div>

      <div>
        <label class="block text-sm font-semibold mb-2">Category Image/Banner</label>
        <div v-if="form.image && !imageFile" class="mb-4">
          <img :src="`/storage/${form.image}`" alt="Category Image" class="w-32 h-32 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
        </div>
        <div v-else-if="imagePreview" class="mb-4">
          <img :src="imagePreview" alt="Category Image Preview" class="w-32 h-32 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
        </div>
        <input type="file" @change="handleUpload" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
      </div>

      <div class="flex items-center gap-3 pt-4 border-t dark:border-slate-700">
        <input v-model="form.is_active" type="checkbox" id="active" class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
        <label for="active" class="font-semibold cursor-pointer">Active (Visible)</label>
      </div>

      <div class="flex items-center gap-3 pt-4 border-t dark:border-slate-700">
        <input v-model="form.is_featured" type="checkbox" id="featured" class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
        <label for="featured" class="font-semibold cursor-pointer">Featured (Show on Home Page)</label>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../utils/api';

const route = useRoute();
const router = useRouter();

const isEdit = route.name === 'admin.categories.edit';
const loading = ref(false);
const parentOptions = ref([]);
const imageFile = ref(null);
const imagePreview = ref(null);

const form = ref({
  name: '',
  parent_id: null,
  description: '',
  is_active: true,
  is_featured: false
});

const fetchParents = async () => {
  const { data } = await api.get('/admin/categories');
  parentOptions.value = data.data; // Note: Ensure API returns flat or root nodes. Assuming root nodes here.
};

const fetchCategory = async () => {
  const { data } = await api.get(`/admin/categories/${route.params.id}`);
  form.value = { ...data };
};

const handleUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    imageFile.value = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const saveCategory = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append('name', form.value.name);
    if(form.value.parent_id) formData.append('parent_id', form.value.parent_id);
    if(form.value.description) formData.append('description', form.value.description);
    formData.append('is_active', form.value.is_active ? 1 : 0);
    formData.append('is_featured', form.value.is_featured ? 1 : 0);
    if(imageFile.value) formData.append('image', imageFile.value);

    if (isEdit) {
      formData.append('_method', 'PUT');
      await api.post(`/admin/categories/${route.params.id}`, formData);
    } else {
      await api.post('/admin/categories', formData);
    }
    router.push('/admin/categories');
  } catch (err) {
    console.error(err.response?.data);
    alert(err.response?.data?.message || 'Failed to save category');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchParents();
  if (isEdit) fetchCategory();
});
</script>
