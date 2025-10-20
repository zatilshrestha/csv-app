import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';
import Companies from '../pages/Companies.vue';
import NotFound from '../pages/NotFound.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/', component: Companies },
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');

    if (to.path === '/login' && token) {
        next('/');
    } else if (to.path !== '/login' && !token) {
        next('/login');
    } else {
        next();
    }
});


export default router;
