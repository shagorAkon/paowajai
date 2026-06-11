import { defineStore } from 'pinia';
import { useToastStore } from './useToastStore';

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
                    max_stock: variant ? variant.stock_quantity : product.stock_quantity,
                    variants: product.variants || []
                });
            }

            this.saveCart();
            this.isOpen = true; // Open cart feedback
            
            const toast = useToastStore();
            toast.add(`${product.name} has been added to your cart!`, 'success');
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

        updateItemVariant(productId, oldVariantId, newVariantId) {
            const itemIndex = this.items.findIndex(i => i.product_id === productId && i.variant_id === oldVariantId);
            if (itemIndex !== -1) {
                const item = this.items[itemIndex];
                const newVariant = item.variants?.find(v => v.id === newVariantId);
                
                if (newVariant) {
                    const existingIndex = this.items.findIndex(i => i.product_id === productId && i.variant_id === newVariantId);
                    
                    if (existingIndex !== -1 && existingIndex !== itemIndex) {
                        this.items[existingIndex].quantity += item.quantity;
                        this.items.splice(itemIndex, 1);
                    } else {
                        item.variant_id = newVariant.id;
                        item.variant_label = newVariant.label;
                        item.price = parseFloat(newVariant.price ? newVariant.price : item.price);
                        item.max_stock = newVariant.stock_quantity;
                        
                        if (item.quantity > item.max_stock) {
                            item.quantity = item.max_stock;
                        }
                    }
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
