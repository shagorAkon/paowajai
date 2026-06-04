<template>
  <div class="container mx-auto px-4 py-12 space-y-20">
    <div v-if="productStore.loading" class="animate-pulse grid grid-cols-1 md:grid-cols-2 gap-12">
      <div class="bg-slate-200 dark:bg-slate-700 aspect-square rounded-2xl"></div>
      <div class="space-y-6">
        <div class="h-8 bg-slate-200 dark:bg-slate-700 w-2/3 rounded"></div>
        <div class="h-6 bg-slate-200 dark:bg-slate-700 w-1/3 rounded"></div>
        <div class="h-24 bg-slate-200 dark:bg-slate-700 rounded"></div>
      </div>
    </div>

    <div v-else-if="productStore.currentProduct" class="grid grid-cols-1 md:grid-cols-2 gap-12">
      
      <!-- Image Gallery -->
      <div class="space-y-4">
        <div 
          class="aspect-square rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-900 border cursor-zoom-in relative group"
          ref="imageContainer"
          @mouseenter="isZoomed = true"
          @mouseleave="isZoomed = false"
          @mousemove="handleZoom"
        >
          <img 
            :src="activeImage ? `/storage/${activeImage}` : 'https://placehold.co/600x600'" 
            :alt="productStore.currentProduct.name"
            class="w-full h-full object-cover transition-transform duration-200"
            :class="isZoomed ? 'scale-[2.5]' : 'scale-100'"
            :style="{ transformOrigin: `${zoomX}% ${zoomY}%` }"
          >
        </div>
        <div v-if="productStore.currentProduct.images?.length" class="flex gap-4 overflow-x-auto pb-2">
          <button 
            @click="activeImage = productStore.currentProduct.thumbnail"
            class="w-20 h-20 rounded-lg overflow-hidden border bg-slate-50 flex-shrink-0"
            :class="[activeImage === productStore.currentProduct.thumbnail ? 'border-primary-500 ring-2 ring-primary-500/20' : '']"
          >
            <img :src="`/storage/${productStore.currentProduct.thumbnail}`" class="w-full h-full object-cover">
          </button>
          <button 
            v-for="img in productStore.currentProduct.images" 
            :key="img.id"
            @click="activeImage = img.image_path"
            class="w-20 h-20 rounded-lg overflow-hidden border bg-slate-50 flex-shrink-0"
            :class="[activeImage === img.image_path ? 'border-primary-500 ring-2 ring-primary-500/20' : '']"
          >
            <img :src="`/storage/${img.image_path}`" class="w-full h-full object-cover">
          </button>
        </div>
      </div>

      <!-- Details -->
      <div class="space-y-6">
        <div class="space-y-2">
          <span class="text-sm text-primary-500 font-bold uppercase tracking-wider">{{ productStore.currentProduct.category?.name }}</span>
          <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
            {{ productStore.currentProduct.name }}
          </h1>
        </div>

        <!-- Pricing -->
        <div class="flex items-baseline gap-4">
          <span class="text-3xl font-black text-slate-900 dark:text-white">
            ৳ {{ formatPrice(displayPrice) }}
          </span>
          <span v-if="!selectedVariant && displayPrice < Math.max(productStore.currentProduct.compare_price || 0, productStore.currentProduct.price)" class="text-lg text-slate-400 line-through">
            ৳ {{ formatPrice(Math.max(productStore.currentProduct.compare_price || 0, productStore.currentProduct.price)) }}
          </span>
        </div>

        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
          {{ productStore.currentProduct.short_description }}
        </p>

        <!-- Variants -->
        <div v-if="productStore.currentProduct.variants?.length" class="space-y-4 pt-4 border-t dark:border-slate-800">
          <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Available Options</h3>
          <div class="flex flex-wrap gap-3">
            <button 
              v-for="variant in productStore.currentProduct.variants" 
              :key="variant.id"
              @click="selectedVariant = variant"
              class="px-4 py-2 rounded-xl border text-sm font-semibold transition-all duration-200"
              :class="[
                selectedVariant?.id === variant.id 
                  ? 'border-primary-500 bg-primary-500/10 text-primary-600 dark:text-primary-400' 
                  : 'border-slate-200 hover:border-slate-400 dark:border-slate-700'
              ]"
            >
              {{ variant.label }}
            </button>
          </div>
        </div>

        <!-- Stock Status & Add to Cart -->
        <div class="space-y-4 pt-6 border-t dark:border-slate-800">
          <div class="flex items-center gap-3">
            <span class="font-semibold">Availability:</span>
            <span :class="[stockQuantity > 0 ? 'text-green-500' : 'text-red-500', 'font-bold']">
              {{ stockQuantity > 0 ? `${stockQuantity} in stock` : 'Out of stock' }}
            </span>
          </div>

          <div class="flex gap-4">
            <div class="flex items-center border dark:border-slate-700 rounded-xl">
              <button @click="quantity > 1 && quantity--" class="px-4 py-3 font-bold hover:bg-slate-100 dark:hover:bg-slate-700">-</button>
              <span class="px-6 font-bold">{{ quantity }}</span>
              <button @click="quantity < stockQuantity && quantity++" class="px-4 py-3 font-bold hover:bg-slate-100 dark:hover:bg-slate-700">+</button>
            </div>
            <button 
              @click="addToCart" 
              :disabled="stockQuantity <= 0"
              class="flex-grow bg-primary-500 hover:bg-primary-600 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-center"
            >
              Add to Shopping Cart
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Full Description -->
    <section v-if="productStore.currentProduct?.description" class="space-y-6 pt-12 border-t dark:border-slate-800">
      <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Product Details</h2>
      <div class="prose prose-slate dark:prose-invert max-w-none prose-lg whitespace-pre-line text-slate-600 dark:text-slate-300">
        {{ productStore.currentProduct.description }}
      </div>
    </section>

    <!-- Related Products -->
    <section v-if="relatedProducts.length" class="space-y-8">
      <h2 class="text-3xl font-extrabold tracking-tight">You May Also Like</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard 
          v-for="product in relatedProducts" 
          :key="product.id" 
          :product="product" 
        />
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useProductStore } from '../stores/useProductStore';
import { useCartStore } from '../stores/useCartStore';
import ProductCard from '../components/ProductCard.vue';

const route = useRoute();
const productStore = useProductStore();
const cartStore = useCartStore();

const activeImage = ref('');
const selectedVariant = ref(null);
const quantity = ref(1);
const relatedProducts = ref([]);

// Image Zoom Logic
const imageContainer = ref(null);
const isZoomed = ref(false);
const zoomX = ref(50);
const zoomY = ref(50);

const handleZoom = (e) => {
  if (!imageContainer.value) return;
  const { left, top, width, height } = imageContainer.value.getBoundingClientRect();
  zoomX.value = ((e.clientX - left) / width) * 100;
  zoomY.value = ((e.clientY - top) / height) * 100;
};

const displayPrice = computed(() => {
  if (selectedVariant.value && selectedVariant.value.price) {
    return selectedVariant.value.price;
  }
  
  const price = productStore.currentProduct?.price || 0;
  const comparePrice = productStore.currentProduct?.compare_price;
  
  if (comparePrice && comparePrice != price) {
    return Math.min(price, comparePrice);
  }
  
  return productStore.currentProduct?.effective_price || price;
});

const stockQuantity = computed(() => {
  if (selectedVariant.value) {
    return selectedVariant.value.stock_quantity;
  }
  return productStore.currentProduct?.stock_quantity || 0;
});

const formatPrice = (price) => {
  return Number(price).toLocaleString('en-IN');
};

const loadProductData = async () => {
  try {
    const data = await productStore.fetchProductDetails(route.params.slug);
    activeImage.value = productStore.currentProduct.thumbnail;
    relatedProducts.value = data.related || [];
    selectedVariant.value = productStore.currentProduct.variants?.[0] || null;
    quantity.value = 1;
  } catch (error) {
    console.error(error);
  }
};

const addToCart = () => {
  if (productStore.currentProduct) {
    cartStore.addToCart(productStore.currentProduct, selectedVariant.value, quantity.value);
  }
};

watch(() => route.params.slug, () => {
  loadProductData();
});

onMounted(() => {
  loadProductData();
});
</script>
