<script setup>
import {ref, computed, watchEffect} from 'vue';
import {useRouter, useRoute} from 'vue-router';
import HeaderNav from './components/HeaderNav.vue';

const router = useRouter();
const route = useRoute();

const token = ref(localStorage.getItem('token'));

window.addEventListener('storage', () => {
    token.value = localStorage.getItem('token');
});

const isLoggedIn = computed(() => !!token.value);

const showHeader = computed(() => isLoggedIn.value && route.path !== '/login');

watchEffect(() => {
    if (!isLoggedIn.value && route.path !== '/login') {
        router.push('/login');
    }
});
</script>

<template>
    <div>
        <HeaderNav v-if="showHeader"/>

        <main class="container">
            <div class="container-body">
                <router-view/>
            </div>
        </main>
    </div>
</template>

<style scoped>
body {
    background-color: #f8f9fa;
    font-family: 'Nunito', sans-serif;
}

.container-body {
    background: white;
    margin: 1rem auto;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}
</style>
