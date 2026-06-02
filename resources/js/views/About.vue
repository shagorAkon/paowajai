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
          <img src="https://scontent.fdac207-1.fna.fbcdn.net/v/t39.30808-1/691157371_4442264816011209_1759085503674971376_n.jpg?stp=dst-jpg_tt6&cstp=mx960x960&ctp=s720x720&_nc_cat=106&ccb=1-7&_nc_sid=3ab345&_nc_ohc=S-F--jfISZAQ7kNvwF4Bapx&_nc_oc=AdrhrSO9ldjCNOdL_5es1fImxuLXMfibba5e-NkSwYFpJk4_8mPx7cYfRWTwnAGgBt0&_nc_zt=24&_nc_ht=scontent.fdac207-1.fna&_nc_gid=dklnvPXn7tSsMoWEXW25MA&_nc_ss=7f20f&oh=00_Af-FuagLknI3qxLyZUbgj1kUcFoT8-na8n95FpXfBGTubQ&oe=6A240EA9" alt="Nisharul Islam Nirob - Founder" class="relative rounded-3xl premium-shadow w-full aspect-[4/5] object-cover">
        </div>
        <div class="w-full md:w-1/2 space-y-6">
          <h2 class="text-4xl font-black">Meet Our Founder</h2>
          <h3 class="text-xl text-primary-500 font-bold">Nisharul Islam Nirob</h3>
          <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
            With a deep passion for quality and customer satisfaction, Nisharul Islam Nirob founded Paowajai with a simple yet powerful vision: to make premium imported products accessible to everyone without compromising on authenticity or service.
          </p>
          <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
            "সম্পর্ক হউক হালাল, উদ্দেশ্য হউক জান্নাত — Our focus has always been integrity and trust. Every product we source and every feature we build is designed to make your shopping journey unforgettable."
          </p>
          <div class="flex gap-4 pt-4">
            <a href="https://www.facebook.com/nisharulislam.nirob1" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-colors" title="Follow on Facebook">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
</script>
