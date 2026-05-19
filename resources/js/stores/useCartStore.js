import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('cart_items')) || [],
        isOpen: false,
    }),

    getters: {
        totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
        
        subtotal: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
    },

    actions: {
        toggleCart() {
            this.isOpen = !this.isOpen;
        },

        addToCart(product, variant = null, quantity = 1) {
            const price = variant && variant.price ? variant.price : product.effective_price || product.price;
            const variantId = variant ? variant.id : null;
            const variantLabel = variant ? variant.label : null;

            const existingItem = this.items.find(
                item => item.product_id === product.id && item.variant_id === variantId
            );

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({
                    product_id: product.id,
                    variant_id: variantId,
                    name: product.name,
                    image: product.thumbnail,
                    variant_label: variantLabel,
                    price: parseFloat(price),
                    quantity: quantity,
                    max_stock: variant ? variant.stock_quantity : product.stock_quantity
                });
            }

            this.saveCart();
            this.isOpen = true; // Open cart feedback
        },

        updateQuantity(productId, variantId, quantity) {
            const item = this.items.find(i => i.product_id === productId && i.variant_id === variantId);
            if (item) {
                if (quantity <= 0) {
                    this.removeFromCart(productId, variantId);
                } else {
                    item.quantity = Math.min(quantity, item.max_stock || 99);
                    this.saveCart();
                }
            }
        },

        removeFromCart(productId, variantId) {
            this.items = this.items.filter(
                item => !(item.product_id === productId && item.variant_id === variantId)
            );
            this.saveCart();
        },

        clearCart() {
            this.items = [];
            this.saveCart();
        },

        saveCart() {
            localStorage.setItem('cart_items', JSON.stringify(this.items));
        }
    }
});
