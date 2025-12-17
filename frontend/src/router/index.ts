import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import LoginView from '@/views/LoginView.vue'
import GateLayout from '@/layouts/GateLayout.vue'
import RegisterView from '@/views/RegisterView.vue'
import { useAuthStore } from '@/stores/auth.store'
import AdminBookingsView from '@/views/admin/AdminBookingsView.vue'

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
      component: GateLayout,
      children: [{ path: '', name: 'login', component: LoginView }],
    },
    {
      path: '/register',
      component: GateLayout,
      children: [{ path: '', name: 'register', component: RegisterView }],
    },
    {
      path: '/admin/',
      meta: { requiresAuth: true },
      component: AdminLayout,
      children: [
        { path: '', name: 'admin', component: AdminBookingsView },
        { path: 'bookings', name: 'adminBookings', component: AdminBookingsView },
        { path: 'users', name: 'adminUsers', component: AdminBookingsView },
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
