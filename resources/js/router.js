import { createRouter, createWebHistory } from 'vue-router';
import Home from '/resources/js/components/Home.vue';
import About from '/resources/js/components/About.vue';


const routes = [
    { path: '/', component: Home },
    { path: '/about', component: About },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
