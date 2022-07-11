import { createWebHistory, createRouter } from 'vue-router'
import Login from '../Pages/Auth/Login.vue'
import Register from '../Pages/Auth/Register.vue'
import ItemsList from '../Pages/Items/ItemsList.vue'
import MyProfile from '../Pages/Profile/Show.vue'
import authService from "../services/authService"

const routes = [
    {
        path: '',
        redirect: '/login'
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/register',
        component: Register
    },
    {
        path: '/my-profile',
        component: MyProfile
    },
    {
        path: '/items',
        name: 'items',
        component: ItemsList
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const publicPages = ['/login', '/register']

    // allow public routes without checking for token
    if (publicPages.includes(to.path)) {
      return   next()
    }

    // if no token redirect to login page
    if (!authService.getToken()) {
        authService.removeSession()
        return next('/login')
    }
    next()
})

export default router

