<template>
  <div class="space-y-24 pb-24">
    <!-- Hero Section -->
    <section class="relative h-[60vh] flex items-center justify-center overflow-hidden">
      <div class="absolute inset-0 z-0">
        <img 
          src="https://images.unsplash.com/photo-1556761175-4b46a572b786?q=80&w=2000&auto=format&fit=crop" 
          alt="About Us Hero"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-slate-900/70 mix-blend-multiply"></div>
      </div>
      
      <div class="relative z-10 text-center px-4 max-w-4xl mx-auto animate-slide-up">
        <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight">Our Story</h1>
        <p class="text-xl md:text-2xl text-slate-200 font-light leading-relaxed">
          Bringing premium products from around the world directly to your doorstep. Experience the new standard of e-commerce.
        </p>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="container mx-auto px-4">
      <div class="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
        <div class="glass-card p-10 premium-shadow hover-lift">
          <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 text-primary-500 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          </div>
          <h2 class="text-3xl font-extrabold mb-4">Our Mission</h2>
          <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
            To provide a seamless, luxurious shopping experience by curating only the highest quality products and delivering them with unmatched customer service and reliability.
          </p>
        </div>
        
        <div class="glass-card p-10 premium-shadow hover-lift">
          <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 text-blue-500 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
          </div>
          <h2 class="text-3xl font-extrabold mb-4">Our Vision</h2>
          <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
            To become the most trusted destination for premium electronics and fashion, setting the benchmark for e-commerce excellence in the region.
          </p>
        </div>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="bg-slate-50 dark:bg-slate-900 py-24 border-y dark:border-slate-800">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-black mb-4">Why Choose Us</h2>
          <p class="text-xl text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">We don't just sell products, we deliver an experience.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
          <!-- Feature 1 -->
          <div class="text-center p-6 transition-transform duration-300 hover:-translate-y-2">
            <div class="w-20 h-20 mx-auto bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-lg mb-6 border border-slate-100 dark:border-slate-700">
              <span class="text-3xl">✨</span>
            </div>
            <h3 class="text-xl font-bold mb-3">Authentic Products</h3>
            <p class="text-slate-500 dark:text-slate-400">100% genuine products sourced directly from trusted manufacturers.</p>
          </div>
          <!-- Feature 2 -->
          <div class="text-center p-6 transition-transform duration-300 hover:-translate-y-2">
            <div class="w-20 h-20 mx-auto bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-lg mb-6 border border-slate-100 dark:border-slate-700">
              <span class="text-3xl">🚀</span>
            </div>
            <h3 class="text-xl font-bold mb-3">Fast Delivery</h3>
            <p class="text-slate-500 dark:text-slate-400">Lightning-fast shipping with real-time tracking for every order.</p>
          </div>
          <!-- Feature 3 -->
          <div class="text-center p-6 transition-transform duration-300 hover:-translate-y-2">
            <div class="w-20 h-20 mx-auto bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-lg mb-6 border border-slate-100 dark:border-slate-700">
              <span class="text-3xl">🛡️</span>
            </div>
            <h3 class="text-xl font-bold mb-3">Secure Payment</h3>
            <p class="text-slate-500 dark:text-slate-400">Enterprise-grade security ensuring your transactions are safe.</p>
          </div>
          <!-- Feature 4 -->
          <div class="text-center p-6 transition-transform duration-300 hover:-translate-y-2">
            <div class="w-20 h-20 mx-auto bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-lg mb-6 border border-slate-100 dark:border-slate-700">
              <span class="text-3xl">💬</span>
            </div>
            <h3 class="text-xl font-bold mb-3">24/7 Support</h3>
            <p class="text-slate-500 dark:text-slate-400">Our dedicated team is always here to assist you with any inquiries.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="container mx-auto px-4">
      <div class="bg-primary-500 rounded-3xl p-12 text-white premium-shadow">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-primary-400">
          <div class="animate-count-up">
            <div class="text-4xl md:text-5xl font-black mb-2">5K+</div>
            <div class="text-primary-100 font-medium">Happy Customers</div>
          </div>
          <div class="animate-count-up" style="animation-delay: 100ms">
            <div class="text-4xl md:text-5xl font-black mb-2">10K+</div>
            <div class="text-primary-100 font-medium">Orders Delivered</div>
          </div>
          <div class="animate-count-up" style="animation-delay: 200ms">
            <div class="text-4xl md:text-5xl font-black mb-2">500+</div>
            <div class="text-primary-100 font-medium">Premium Products</div>
          </div>
          <div class="animate-count-up" style="animation-delay: 300ms">
            <div class="text-4xl md:text-5xl font-black mb-2">5+</div>
            <div class="text-primary-100 font-medium">Years Experience</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team / Owner -->
    <section class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-12">
        <div class="w-full md:w-1/2 relative group">
          <div class="absolute inset-0 bg-gradient-to-tr from-primary-500 to-blue-500 rounded-3xl rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20"></div>
          <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=800&auto=format&fit=crop" alt="Founder" class="relative rounded-3xl premium-shadow w-full aspect-[4/5] object-cover">
        </div>
        <div class="w-full md:w-1/2 space-y-6">
          <h2 class="text-4xl font-black">Meet Our Founder</h2>
          <h3 class="text-xl text-primary-500 font-bold">Kamrul Hasan Nahid</h3>
          <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
            With a passion for technology and fashion, Kamrul started Paowajai with a simple idea: to make premium imported goods accessible to everyone without compromising on quality or service.
          </p>
          <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
            "Our focus has always been the customer. Every product we source and every feature we build is designed to make your shopping journey unforgettable."
          </p>
          <div class="flex gap-4 pt-4">
            <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            <a href="#" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
</script>
