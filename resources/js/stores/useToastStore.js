import { defineStore } from 'pinia';

export const useToastStore = defineStore('toast', {
    state: () => ({
        toasts: []
    }),
    actions: {
        add(message, type = 'success', duration = 3000) {
            const id = Date.now() + Math.random().toString(36).substr(2, 9);
            this.toasts.push({ id, message, type });
            
            setTimeout(() => {
                this.remove(id);
            }, duration);
        },
        remove(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts.splice(index, 1);
            }
        }
    }
});
