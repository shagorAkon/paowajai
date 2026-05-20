<template>
  <div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Marketing & Campaigns</h2>
        <p class="text-slate-500 dark:text-slate-400">Manage coupons, banners, and flash sales.</p>
      </div>
      <button class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl transition-colors shadow-lg shadow-primary-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Campaign
      </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Active Coupons</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">12</p>
      </div>
      <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Campaign Revenue</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">৳ 45,200</p>
      </div>
      <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Newsletter Subscribers</p>
        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">3,450</p>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
      <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2 mb-4">Upcoming Feature</h3>
      <p class="text-slate-500">The advanced marketing workflow (Coupon builder, Banner Drag & Drop, Newsletter Dispatcher) is currently under construction and will be integrated into this module shortly.</p>
    </div>
  </div>
</template>
