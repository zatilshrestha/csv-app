<script setup>
import {useRouter} from 'vue-router';

const router = useRouter();

function logout() {
    localStorage.removeItem('token');
    router.push('/login');
}

</script>

<template>
    <header class="d-flex justify-content-between align-items-center">
        <RouterLink to="/" class="app-name">CSV-App</RouterLink>
        <button @click="logout" class="logout-btn">Logout</button>
    </header>
</template>

<style scoped>
header {
    background-color: white;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 4rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.app-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #343a40;
    text-decoration: none;
}

.logout-btn {
    background: none;
    border: none;
    color: #dc3545;
    font-weight: 500;
}
</style>
