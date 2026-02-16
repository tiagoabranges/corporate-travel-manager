import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Dashboard.vue";
import Profile from "../views/Profile.vue";
import MainLayout from "../layouts/MainLayout.vue";

const routes = [
  {
    path: "/",
    name: "Login",
    component: Login,
  },
  {
    path: "/register",
    name: "Register",
    component: Register,
  },

  // 🔥 Rotas protegidas com layout
  {
    path: "/",
    component: MainLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        component: Dashboard,
      },
      {
        path: "profile",
        name: "Profile",
        component: Profile,
      },
    ],
  },
];


const router = createRouter({
  history: createWebHistory(),
  routes,
});

// 🔥 Route Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");

  if (to.meta.requiresAuth && !token) {
    next("/");
  } else {
    next();
  }
});

export default router;
