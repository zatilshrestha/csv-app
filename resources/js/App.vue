<script setup>
import { computed, watchEffect } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import HeaderNav from './components/HeaderNav.vue';

const router = useRouter();
const route = useRoute();

const isLoggedIn = computed(() => {
    return !!localStorage.getItem('token');
});

const showHeader = computed(() => {
    return isLoggedIn.value && route.path !== '/login';
});

watchEffect(() => {
    if (!isLoggedIn.value && route.path !== '/login') {
        router.push('/login');
    }
});
</script>

<template>
    <div>
        <HeaderNav v-if="showHeader" />

        <main>
            <router-view />
        </main>
    </div>
</template>

<style scoped>
body {
    margin: 0;
    font-family: sans-serif;
}
</style>
