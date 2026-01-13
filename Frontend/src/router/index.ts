import { useUserStore } from '@/stores/userStore'
import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import UsersView from '../views/UsersView.vue'
import AlumnosView from '@/views/AlumnosView.vue'
import AlumnosTutorView from '../views/tutor/AlumnosTutorView.vue'
import SeguimientoView from '@/views/tutor/SeguimientoView.vue'
import AsignarEmpresaView from '@/views/tutor/AsignarEmpresaView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/users',
      name: 'users',
      component: UsersView,
    },
     {
       path: '/alumnos',
       name: 'alumnos',
       component: AlumnosView
     },
    {
      path: '/alumnosTutor/seguimiento',
      name: 'seguimientoAlumno',
      component: SeguimientoView
    },
    
    {
      path: '/alumnosTutor/asignarEmpresa',
      name: 'asignarEmpresa',
      component: AsignarEmpresaView
    }
    
  ],
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()

  const userAuth = await userStore.getUser()
  const isAdmin = userStore.user?.tipo == 'admin';
  if (!userAuth && to.path !== '/') {
    return next('/')
  }

  if (userAuth && to.path === '/' || !isAdmin && to.path == '/users') {
    return next('/home')
  }

    console.log()
  next()
})



export default router
