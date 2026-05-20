<template>
  <div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Inventory Management</h2>
        <p class="text-slate-500 dark:text-slate-400">Track warehouse stock and supplier orders.</p>
      </div>
      <button class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2.5 px-5 rounded-xl transition-colors shadow-lg shadow-primary-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New Purchase Order
      </button>
    </div>

    <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl p-6 mb-8">
      <h3 class="text-lg font-bold text-red-700 dark:text-red-400 mb-2 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        Low Stock Alerts
      </h3>
      <p class="text-red-600 dark:text-red-300">You have 4 products running low on inventory.</p>
      <!-- List will be populated here -->
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
      <h3 class="text-lg font-bold border-b dark:border-slate-700 pb-2 mb-4">Stock History</h3>
      <p class="text-slate-500">The Supplier Management and detailed Stock History features are pending backend tables generation.</p>
    </div>
  </div>
</template>
