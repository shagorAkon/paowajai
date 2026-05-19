import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import ProductListing from '../views/ProductListing.vue';
import ProductDetail from '../views/ProductDetail.vue';
import Checkout from '../views/Checkout.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/products',
        name: 'products',
        component: ProductListing,
    },
    {
        path: '/category/:slug',
        name: 'category',
        component: ProductListing,
    },
    {
        path: '/product/:slug',
        name: 'product.detail',
        component: ProductDetail,
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: Checkout,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0, behavior: 'smooth' };
        }
    }
});

export default router;
