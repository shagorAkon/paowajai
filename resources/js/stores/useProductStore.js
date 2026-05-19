import { defineStore } from 'pinia';
import api from '../utils/api';

export const useProductStore = defineStore('products', {
    state: () => ({
        homeData: null,
        categories: [],
        products: [],
        currentProduct: null,
        loading: false,
        error: null,
        pagination: null,
    }),

    actions: {
        async fetchHomeData() {
            this.loading = true;
            try {
                const { data } = await api.get('/storefront/home');
                this.homeData = data;
            } catch (error) {
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },

        async fetchCategories() {
            try {
                const { data } = await api.get('/storefront/categories');
                this.categories = data;
            } catch (error) {
                this.error = error.message;
            }
        },

        async fetchProducts(params = {}) {
            this.loading = true;
            try {
                const { data } = await api.get('/storefront/products', { params });
                this.products = data.data;
                this.pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    total: data.total,
                };
            } catch (error) {
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },

        async fetchProductDetails(slug) {
            this.loading = true;
            try {
                const { data } = await api.get(`/storefront/products/${slug}`);
                this.currentProduct = data.product;
                return data; // Return related products too
            } catch (error) {
                this.error = error.message;
                throw error;
            } finally {
                this.loading = false;
            }
        }
    }
});
