import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import LoginView from '@/views/LoginView.vue'
import GateLayout from '@/layouts/GateLayout.vue'
import RegisterView from '@/views/RegisterView.vue'
import { useAuthStore } from '@/stores/auth.store'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      meta: { requiresAuth: true },
      component: DefaultLayout,
      children: [
        {
          path: '',
          name: 'home',
          component: HomeView,
          meta: { requiresAuth: true },
        },
        {
          path: 'bookings',
          component: HomeView,
          meta: { requiresAuth: true },
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: GateLayout,
      children: [{ path: '', component: LoginView }],
    },
    {
      path: '/register',
      name: 'register',
      component: GateLayout,
      children: [{ path: '', component: RegisterView }],
    },
    {
      path: '/admin/',
      name: 'dashboard',
      meta: { requiresAuth: true },
      component: AdminLayout,
      children: [
        { path: '', component: HomeView },
        { path: 'bookings', component: HomeView },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    next({ path: '/login' })
  } else {
    next()
  }
})

export default router
