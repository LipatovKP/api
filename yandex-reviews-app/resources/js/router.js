import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './components/Dashboard.vue';
import Login from './components/Login.vue';
import Settings from './components/Settings.vue';
import Reviews from './components/Reviews.vue';

const routes = [
  { path: '/login', name: 'login', component: Login },
  {
    path: '/',
    component: Dashboard,
    children: [
      { path: '', redirect: '/settings' },
      { path: '/settings', name: 'settings', component: Settings },
      { path: '/reviews', name: 'reviews', component: Reviews },
    ],
  },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
