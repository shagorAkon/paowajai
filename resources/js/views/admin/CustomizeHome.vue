<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Customize Home Page</h2>
        <p class="text-slate-500 dark:text-slate-400">Manage your homepage carousel banners.</p>
      </div>
      <button @click="openCreateModal" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Banner
      </button>
    </div>

    <!-- Error/Loading states -->
    <div v-if="loading" class="text-center py-12 text-slate-500">Loading banners...</div>
    <div v-if="error" class="bg-red-50 text-red-600 p-4 rounded-lg">{{ error }}</div>

    <!-- Banners Grid -->
    <div v-if="!loading && !error" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="banner in banners" :key="banner.id" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden group">
        <div class="relative aspect-[16/9] bg-slate-100 dark:bg-slate-900 overflow-hidden">
          <img :src="`/storage/${banner.image}`" :alt="banner.title" class="w-full h-full object-cover">
          <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
            <button @click="openEditModal(banner)" class="bg-white text-slate-900 p-2 rounded-lg hover:bg-primary-500 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <button @click="deleteBanner(banner.id)" class="bg-white text-red-600 p-2 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
          </div>
        </div>
        <div class="p-4">
          <h3 class="font-bold text-slate-900 dark:text-white truncate">{{ banner.title || '(No Title)' }}</h3>
          <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ banner.subtitle || '(No Subtitle)' }}</p>
          <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
            <span>Button: {{ banner.button_text || 'Default' }}</span>
            <span>Link: {{ banner.link || 'Default' }}</span>
          </div>
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-if="banners.length === 0" class="col-span-full bg-white dark:bg-slate-800 rounded-xl p-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-700">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No banners added yet</h3>
        <p class="text-slate-500 mb-6">Upload some beautiful images to showcase on your homepage.</p>
        <button @click="openCreateModal" class="text-primary-500 font-medium hover:underline">Add your first banner</button>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="closeModal"></div>
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <form @submit.prevent="saveBanner">
            <div class="bg-white dark:bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
              <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-6">
                {{ isEditing ? 'Edit Banner' : 'Add New Banner' }}
              </h3>
              
              <div class="space-y-4">
                <!-- Image Upload -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Image (Required)</label>
                  <input type="file" ref="fileInput" @change="handleFileChange" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                  <div v-if="previewImage" class="mt-3 relative aspect-[16/9] rounded-lg overflow-hidden border dark:border-slate-700">
                    <img :src="previewImage" class="w-full h-full object-cover">
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Title</label>
                  <input v-model="form.title" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 focus:border-primary-500 focus:ring-primary-500" placeholder="e.g. Summer Collection">
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Subtitle</label>
                  <input v-model="form.subtitle" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 focus:border-primary-500 focus:ring-primary-500" placeholder="e.g. Exclusive Offer">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Button Text</label>
                    <input v-model="form.button_text" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 focus:border-primary-500 focus:ring-primary-500" placeholder="e.g. Shop Now">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Button Link</label>
                    <input v-model="form.link" type="text" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 focus:border-primary-500 focus:ring-primary-500" placeholder="e.g. /products">
                  </div>
                </div>
              </div>
            </div>
            
            <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="submit" :disabled="saving" class="inline-flex w-full justify-center rounded-lg bg-primary-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 sm:ml-3 sm:w-auto disabled:opacity-50">
                {{ saving ? 'Saving...' : 'Save Banner' }}
              </button>
              <button type="button" @click="closeModal" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-slate-700 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:mt-0 sm:w-auto">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../utils/api';

const banners = ref([]);
const loading = ref(true);
const error = ref(null);
const showModal = ref(false);
const saving = ref(false);
const isEditing = ref(false);
const currentId = ref(null);
const fileInput = ref(null);
const previewImage = ref(null);

const form = ref({
  title: '',
  subtitle: '',
  button_text: '',
  link: '',
  image: null
});

const fetchBanners = async () => {
  loading.value = true;
  try {
    const { data } = await api.get('/admin/banners');
    banners.value = data;
  } catch (err) {
    error.value = 'Failed to load banners.';
  } finally {
    loading.value = false;
  }
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      previewImage.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const openCreateModal = () => {
  isEditing.value = false;
  currentId.value = null;
  form.value = { title: '', subtitle: '', button_text: '', link: '', image: null };
  previewImage.value = null;
  if (fileInput.value) fileInput.value.value = '';
  showModal.value = true;
};

const openEditModal = (banner) => {
  isEditing.value = true;
  currentId.value = banner.id;
  form.value = {
    title: banner.title || '',
    subtitle: banner.subtitle || '',
    button_text: banner.button_text || '',
    link: banner.link || '',
    image: null
  };
  previewImage.value = `/storage/${banner.image}`;
  if (fileInput.value) fileInput.value.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveBanner = async () => {
  if (!isEditing.value && !form.value.image) {
    alert('Please select an image for the banner.');
    return;
  }

  saving.value = true;
  const formData = new FormData();
  formData.append('title', form.value.title);
  formData.append('subtitle', form.value.subtitle);
  formData.append('button_text', form.value.button_text);
  formData.append('link', form.value.link);
  
  if (form.value.image) {
    formData.append('image', form.value.image);
  }

  try {
    if (isEditing.value) {
      await api.post(`/admin/banners/${currentId.value}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    } else {
      await api.post('/admin/banners', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    }
    await fetchBanners();
    closeModal();
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to save banner.');
  } finally {
    saving.value = false;
  }
};

const deleteBanner = async (id) => {
  if (!confirm('Are you sure you want to delete this banner?')) return;
  
  try {
    await api.delete(`/admin/banners/${id}`);
    await fetchBanners();
  } catch (err) {
    alert('Failed to delete banner.');
  }
};

onMounted(() => {
  fetchBanners();
});
</script>
