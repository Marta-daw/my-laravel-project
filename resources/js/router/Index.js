import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '../layouts/MainLayout.vue';
import ProjectLayout from '../layouts/ProjectLayout.vue';
import Home from '../pages/Home.vue';
import DasboardBiblio from '../pages/DasboardBiblio.vue';

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: Home },
      { path: '/Home2.vue', component: () => import('../pages/Home2.vue') },
    ],
  },
  {
    path: '/biblioteca',
    component: ProjectLayout,
    children: [
      { path: '', component: DasboardBiblio },
      { path: 'principal', component: () => import('../pages/DasboardBiblio.vue') },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;