import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/HomePage.vue'
import TasksPage from '@/pages/TasksPage.vue'
import DevelopersPage from '@/pages/DevelopersPage.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomePage
    },
    {
      path: '/tasks',
      name: 'tasks',
      component: TasksPage
    },
    {
      path: '/developers',
      name: 'developers',
      component: DevelopersPage
    }
  ]
})

export default router
