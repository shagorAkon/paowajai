import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Accept': 'application/json',
    },
    withCredentials: true, // For Sanctum CSRF
});

// Request interceptor to add the auth token if available
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
