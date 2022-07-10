import { createWebHistory, createRouter } from "vue-router";
import Login from '../Pages/Auth/Login.vue'

const routes = [
    {
        path: '',
        redirect: '/login',
        // meta: { layout: 'auth-layout' }
    },
    {
        path: '/login',
        // meta: { layout: 'auth-layout' },
        component: Login
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

