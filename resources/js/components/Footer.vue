<template>
  <footer class="bg-slate-50 dark:bg-slate-900 border-t dark:border-slate-800 pt-16 pb-8 mt-auto">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
        <div class="space-y-4">
          <span class="text-2xl font-black tracking-tighter text-gradient">PAOWAJAI</span>
          <p class="text-slate-500 dark:text-slate-400">Premium ecommerce experience for the modern shopper. Curated products, fast delivery.</p>
        </div>
        
        <div>
          <h3 class="font-bold text-lg mb-4">Shop</h3>
          <ul class="space-y-2 text-slate-500 dark:text-slate-400">
            <li><router-link to="/products" class="hover:text-primary-500 transition">All Products</router-link></li>
            <li><router-link to="/products?featured=1" class="hover:text-primary-500 transition">Featured</router-link></li>
            <li><router-link to="/products?flash=1" class="hover:text-primary-500 transition">Flash Sales</router-link></li>
          </ul>
        </div>
        
        <div>
          <h3 class="font-bold text-lg mb-4">Support</h3>
          <ul class="space-y-2 text-slate-500 dark:text-slate-400">
            <li><a href="#" class="hover:text-primary-500 transition">Contact Us</a></li>
            <li><a href="#" class="hover:text-primary-500 transition">FAQs</a></li>
            <li><a href="#" class="hover:text-primary-500 transition">Shipping Policy</a></li>
            <li><a href="#" class="hover:text-primary-500 transition">Returns & Exchanges</a></li>
          </ul>
        </div>

        <div>
          <h3 class="font-bold text-lg mb-4">Newsletter</h3>
          <p class="text-slate-500 dark:text-slate-400 mb-4">Subscribe for exclusive offers and updates.</p>
          <div class="flex">
            <input type="email" placeholder="Your email" class="w-full px-4 py-2 rounded-l-lg border border-slate-300 dark:border-slate-700 dark:bg-slate-800 focus:outline-none focus:border-primary-500">
            <button class="bg-primary-500 text-white px-4 py-2 rounded-r-lg hover:bg-primary-600 transition font-semibold">Subscribe</button>
          </div>
        </div>
      </div>
      
      <div class="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between text-slate-500 dark:text-slate-400 text-sm">
        <p>&copy; {{ new Date().getFullYear() }} Paowajai. All rights reserved.</p>
        <div class="flex gap-4 mt-4 md:mt-0">
          <a href="#" class="hover:text-primary-500 transition">Privacy Policy</a>
          <a href="#" class="hover:text-primary-500 transition">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
</script>
