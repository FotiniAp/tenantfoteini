import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '@/views/admin/LoginView.vue'
import MainView from '@/views/user/MainView.vue'
import Users from '@/components/Users.vue'
import AddUsers from '@/components/AddUser.vue'
import ProfileView from '@/components/ProfileView.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: {
      requiresAuth: false,
    },
  },
  {
    path: '/',
    name: 'mainView',
    component: MainView,
    redirect: "/home",
    children: [
      {
        path: '/home',
        name: 'home',
        component: HomeView,
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: '/users',
        name: 'users',
        component: Users,
        meta: {
          requiresAuth: true,
          roles: 'admin',
        },
      },
      {
        path: '/users/add/:id?',
        name: 'addusers',
        component: AddUsers,
        meta: {
          requiresAuth: true,
          roles: 'admin',
        },
      },
      {
        path: '/users/profile/:id?',
        name: 'userprofile',
        component: ProfileView,
        meta: {
          requiresAuth: true,
          roles: 'admin',
        },
      },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const { requiresAuth, role } = to.meta;
  const token = localStorage.getItem("token");
  const isAdmin = localStorage.getItem("role") === "tenant_admin" ? true : false;
  const { path } = to;
  if (requiresAuth) {
    if (!token) {
      next('/login');
    } else if (token && path === '/login' || (role === 'admin' && !isAdmin)) {
      next('/home');
    } else {
      next();
    }
  } else {
    if (token && path === '/login') {
      next('/home');
    } else {
      next();
    }
  }
});

export default router;