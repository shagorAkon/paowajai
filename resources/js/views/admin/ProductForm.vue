<template>
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
          {{ isEdit ? 'Edit Product' : 'Add New Product' }}
        </h2>
        <p class="text-slate-500 dark:text-slate-400">Complete the information below to save your product.</p>
      </div>
      <div class="flex gap-3">
        <router-link to="/admin/products" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-bold py-2.5 px-5 rounded-xl transition-colors">
          Cancel
        </router-link>
        <button @click="saveProduct" :disabled="loading" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl transition-colors shadow-lg shadow-primary-500/30 flex items-center gap-2">
          <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          {{ loading ? 'Saving...' : 'Save Product' }}
        </button>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
      <!-- Main Content (Tabs & Forms) -->
      <div class="lg:w-2/3 space-y-6">
        
        <!-- Tabs -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-2 flex overflow-x-auto hide-scrollbar">
          <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="[activeTab === tab.id ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium', 'px-5 py-2.5 rounded-xl text-sm transition-all whitespace-nowrap']">
            {{ tab.name }}
          </button>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 lg:p-8">
          <!-- GENERAL TAB -->
          <div v-show="activeTab === 'general'" class="space-y-6 animate-fade-in">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Product Name <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
            </div>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">SKU</label>
                <input v-model="form.sku" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Barcode</label>
                <input v-model="form.barcode" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Short Description</label>
              <textarea v-model="form.short_description" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white"></textarea>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Full Description</label>
              <textarea v-model="form.description" rows="6" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white"></textarea>
            </div>
          </div>

          <!-- PRICING TAB -->
          <div v-show="activeTab === 'pricing'" class="space-y-6 animate-fade-in">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Regular Price <span class="text-red-500">*</span></label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">৳</span>
                  <input v-model="form.price" type="number" step="0.01" class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
                </div>
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Compare at Price</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">৳</span>
                  <input v-model="form.compare_price" type="number" step="0.01" class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
                </div>
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Cost Price (Not visible to customers)</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">৳</span>
                <input v-model="form.cost_price" type="number" step="0.01" class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
              </div>
            </div>
            
            <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
              <h3 class="font-bold text-lg mb-4">Flash Sale</h3>
              <div class="flex items-center gap-3 mb-4">
                <input v-model="form.is_flash_sale" type="checkbox" id="flash_sale" class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                <label for="flash_sale" class="font-semibold cursor-pointer">Enable Flash Sale</label>
              </div>
              <div v-if="form.is_flash_sale" class="grid grid-cols-1 sm:grid-cols-3 gap-6 bg-slate-50 dark:bg-slate-900 p-4 rounded-xl">
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Flash Price</label>
                  <input v-model="form.flash_sale_price" type="number" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Start Date</label>
                  <input v-model="form.flash_sale_start" type="datetime-local" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">End Date</label>
                  <input v-model="form.flash_sale_end" type="datetime-local" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
              </div>
            </div>
          </div>

          <!-- MEDIA TAB -->
          <div v-show="activeTab === 'media'" class="space-y-6 animate-fade-in">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Primary Thumbnail</label>
              <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-8 text-center hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors cursor-pointer relative overflow-hidden">
                <input type="file" @change="handleThumbnailUpload" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                <div v-if="thumbnailPreview" class="relative">
                  <img :src="thumbnailPreview" class="max-h-48 mx-auto rounded-lg shadow-sm">
                  <p class="mt-2 text-sm text-primary-500 font-medium">Click to change thumbnail</p>
                </div>
                <div v-else>
                  <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <p class="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">Upload a file or drag and drop</p>
                  <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                </div>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Gallery Images (Multiple)</label>
              <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-8 text-center hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors cursor-pointer relative">
                <input type="file" @change="handleGalleryUpload" multiple accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <p class="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">Select multiple images</p>
              </div>
              <div v-if="galleryPreviews.length > 0" class="mt-4 grid grid-cols-4 gap-4">
                <div v-for="(img, idx) in galleryPreviews" :key="idx" class="relative group">
                  <img :src="img.url" class="w-full h-24 object-cover rounded-lg border dark:border-slate-700">
                  <button @click="removeGalleryItem(idx)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
              </div>
            </div>
          </div>

          <!-- VARIANTS TAB -->
          <div v-show="activeTab === 'variants'" class="space-y-6 animate-fade-in">
            <p class="text-sm text-slate-500">Configure product variants like sizes or colors. If you add variants, the base price and stock are overridden by variant configurations.</p>
            
            <div v-for="(variant, index) in form.variants" :key="index" class="bg-slate-50 dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-700 relative">
              <button @click="removeVariant(index)" class="absolute top-4 right-4 text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mr-8">
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Color</label>
                  <input v-model="variant.color" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Size</label>
                  <input v-model="variant.size" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Price (৳)</label>
                  <input v-model="variant.price" type="number" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-500 mb-1">Stock</label>
                  <input v-model="variant.stock_quantity" type="number" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                </div>
              </div>
            </div>
            
            <button @click="addVariant" class="w-full py-3 border-2 border-dashed border-primary-300 dark:border-primary-500/30 text-primary-600 dark:text-primary-400 font-bold rounded-xl hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors">
              + Add Another Variant
            </button>
          </div>

          <!-- SEO TAB -->
          <div v-show="activeTab === 'seo'" class="space-y-6 animate-fade-in">
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Meta Title</label>
              <input v-model="form.meta_title" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Meta Description</label>
              <textarea v-model="form.meta_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white"></textarea>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Meta Keywords (Comma separated)</label>
              <input v-model="form.meta_keywords" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar (Status & Organization) -->
      <div class="lg:w-1/3 space-y-6">
        <!-- Status Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
          <h3 class="font-bold text-lg mb-4 text-slate-900 dark:text-white border-b dark:border-slate-700 pb-2">Visibility Status</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="font-medium">Active (Published)</span>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-primary-500"></div>
              </label>
            </div>
            <div class="flex items-center justify-between">
              <span class="font-medium">Featured Product</span>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" v-model="form.is_featured" class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-blue-500"></div>
              </label>
            </div>
          </div>
        </div>

        <!-- Organization Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
          <h3 class="font-bold text-lg mb-4 text-slate-900 dark:text-white border-b dark:border-slate-700 pb-2">Organization</h3>
          
          <div class="mb-4">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Category <span class="text-red-500">*</span></label>
            <select v-model="form.category_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
              <option value="" disabled>Select a category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Base Stock Quantity <span class="text-red-500">*</span></label>
            <input v-model="form.stock_quantity" type="number" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
          </div>
          <div class="mt-4">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Low Stock Threshold</label>
            <input v-model="form.low_stock_threshold" type="number" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-primary-500 outline-none dark:text-white">
          </div>
        </div>
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

const isEdit = route.name === 'admin.products.edit';
const loading = ref(false);

const activeTab = ref('general');
const tabs = [
  { id: 'general', name: 'General Information' },
  { id: 'pricing', name: 'Pricing & Flash Sale' },
  { id: 'media', name: 'Media Gallery' },
  { id: 'variants', name: 'Variants & Attributes' },
  { id: 'seo', name: 'Search Engine Optimization' },
];

const categories = ref([]);
const thumbnailFile = ref(null);
const thumbnailPreview = ref('');
const galleryFiles = ref([]);
const galleryPreviews = ref([]);

const form = ref({
  name: '',
  sku: '',
  barcode: '',
  short_description: '',
  description: '',
  price: 0,
  compare_price: null,
  cost_price: null,
  stock_quantity: 0,
  low_stock_threshold: 5,
  is_active: true,
  is_featured: false,
  is_flash_sale: false,
  flash_sale_price: null,
  flash_sale_start: null,
  flash_sale_end: null,
  meta_title: '',
  meta_description: '',
  meta_keywords: '',
  category_id: '',
  variants: []
});

const fetchCategories = async () => {
  try {
    const { data } = await api.get('/admin/categories');
    // Flatten categories for dropdown
    const flatten = (cats, prefix = '') => {
      let result = [];
      cats.forEach(c => {
        result.push({ id: c.id, name: prefix + c.name });
        if (c.children?.length) {
          result = result.concat(flatten(c.children, prefix + '-- '));
        }
      });
      return result;
    };
    categories.value = flatten(data.data || []);
  } catch (err) {
    console.error(err);
  }
};

const fetchProduct = async () => {
  try {
    const { data } = await api.get(`/admin/products/${route.params.id}`);
    form.value = { ...data };
    if (data.thumbnail) {
      thumbnailPreview.value = `/storage/${data.thumbnail}`;
    }
  } catch (err) {
    console.error(err);
  }
};

const handleThumbnailUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  thumbnailFile.value = file;
  thumbnailPreview.value = URL.createObjectURL(file);
};

const handleGalleryUpload = (e) => {
  const files = Array.from(e.target.files);
  files.forEach(f => {
    galleryFiles.value.push(f);
    galleryPreviews.value.push({ file: f, url: URL.createObjectURL(f) });
  });
};

const removeGalleryItem = (idx) => {
  galleryFiles.value.splice(idx, 1);
  galleryPreviews.value.splice(idx, 1);
};

const addVariant = () => {
  form.value.variants.push({ color: '', size: '', price: null, stock_quantity: 0 });
};

const removeVariant = (index) => {
  form.value.variants.splice(index, 1);
};

const saveProduct = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    // Append all basic fields
    Object.keys(form.value).forEach(key => {
      if (key !== 'variants' && key !== 'images' && key !== 'category' && form.value[key] !== null) {
        let value = form.value[key];
        // Convert booleans to 1 or 0 for Laravel validation
        if (typeof value === 'boolean') {
          value = value ? 1 : 0;
        }
        formData.append(key, value);
      }
    });

    if (thumbnailFile.value) formData.append('thumbnail', thumbnailFile.value);
    
    galleryFiles.value.forEach((file, idx) => {
      formData.append(`gallery[${idx}]`, file);
    });

    form.value.variants.forEach((v, idx) => {
      Object.keys(v).forEach(k => {
        if(v[k] !== null) formData.append(`variants[${idx}][${k}]`, v[k]);
      });
    });

    if (isEdit) {
      formData.append('_method', 'PUT');
      await api.post(`/admin/products/${route.params.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    } else {
      await api.post('/admin/products', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    }

    router.push('/admin/products');
  } catch (err) {
    alert('Failed to save product. Check the console for validation errors.');
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCategories();
  if (isEdit) {
    fetchProduct();
  }
});
</script>
