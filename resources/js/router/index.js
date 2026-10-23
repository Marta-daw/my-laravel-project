import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '../layouts/MainLayout.vue';
import ProjectLayout from '../layouts/ProjectLayout.vue';
import Home from '../pages/Home.vue';
import DashboardBiblio from '../pages/DashboardBiblio.vue';
import LlibresPage from '../pages/LlibresPage.vue';

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: Home },
      { path: '/about', component: () => import('../pages/AboutMe.vue') },
      { path: '/Home2.vue', component: () => import('../pages/Home2.vue') },
    ],
  },
  {
    path: '/biblioteca',
    component: ProjectLayout,
    children: [
      { path: '', component: DashboardBiblio },
      { path: '/llibres', component: LlibresPage },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;