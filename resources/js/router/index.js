import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/useAuthStore';

// Public Views
import Home from '../views/Home.vue';
import ProductListing from '../views/ProductListing.vue';
import ProductDetail from '../views/ProductDetail.vue';
import Checkout from '../views/storefront/Checkout.vue';
import TrackOrder from '../views/storefront/TrackOrder.vue';
import Login from '../views/auth/Login.vue';
import About from '../views/About.vue';
import Featured from '../views/Featured.vue';

// Admin Views
import AdminDashboard from '../views/admin/Dashboard.vue';
import AdminProducts from '../views/admin/Products.vue';
import AdminProductForm from '../views/admin/ProductForm.vue';
import AdminCategories from '../views/admin/Categories.vue';
import AdminCategoryForm from '../views/admin/CategoryForm.vue';
import AdminOrders from '../views/admin/Orders.vue';
import AdminOrderDetails from '../views/admin/OrderDetails.vue';
import AdminInventory from '../views/admin/Inventory.vue';
import AdminMarketing from '../views/admin/Marketing.vue';
import AdminSettings from '../views/admin/Settings.vue';

const routes = [
    // --- Public Routes ---
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/about',
        name: 'about',
        component: About,
    },
    {
        path: '/featured',
        name: 'featured',
        component: Featured,
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
        name: 'storefront.checkout',
        component: Checkout,
        meta: { layout: 'storefront' }
    },
    {
        path: '/track-order/:order_number?',
        name: 'storefront.track',
        component: TrackOrder,
        meta: { layout: 'storefront' }
    },
    
    // --- Admin Routes ---
    {
        path: '/admin',
        redirect: '/admin/dashboard'
    },
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/products',
        name: 'admin.products',
        component: AdminProducts,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/products/create',
        name: 'admin.products.create',
        component: AdminProductForm,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/products/:id/edit',
        name: 'admin.products.edit',
        component: AdminProductForm,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/categories',
        name: 'admin.categories',
        component: AdminCategories,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/categories/create',
        name: 'admin.categories.create',
        component: AdminCategoryForm,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/categories/:id/edit',
        name: 'admin.categories.edit',
        component: AdminCategoryForm,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/orders',
        name: 'admin.orders',
        component: AdminOrders,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/orders/:id',
        name: 'admin.orders.show',
        component: AdminOrderDetails,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: AdminSettings,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/inventory',
        name: 'admin.inventory',
        component: AdminInventory,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/marketing',
        name: 'admin.marketing',
        component: AdminMarketing,
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    {
        path: '/admin/reports',
        name: 'admin.reports',
        component: () => import('../views/admin/Reports.vue'),
        meta: { layout: 'admin', requiresAuth: true, isAdmin: true }
    },
    // Add catch-all redirect for now on missing admin routes
    {
        path: '/admin/:pathMatch(.*)*',
        redirect: '/admin/dashboard'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) return savedPosition;
        return { top: 0, behavior: 'smooth' };
    }
});

// Navigation Guards
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    
    // If we have a token but no user, fetch the user first to check roles
    if (authStore.token && !authStore.user) {
        await authStore.fetchUser();
    }

    const isAuthenticated = authStore.isAuthenticated;
    const isAdmin = authStore.isAdmin;

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else if (to.meta.guestOnly && isAuthenticated) {
        if (isAdmin) {
            next('/admin/dashboard');
        } else {
            next('/');
        }
    } else if (to.meta.isAdmin && !isAdmin) {
        next('/'); // Redirect unauthorized users to storefront
    } else {
        next();
    }
});

export default router;
