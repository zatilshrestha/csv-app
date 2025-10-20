<script setup>
import {useRouter} from 'vue-router';

const router = useRouter();

function logout() {
    localStorage.removeItem('token');
    router.push('/login');
}

</script>

<template>
    <header class="navbar navbar-light bg-light px-5 border-bottom shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">CSV-App</h5>

            <div>
                <RouterLink to="/" class="btn fw-medium">Companies</RouterLink>

                <button @click="logout" class="btn fw-medium">Logout</button>
            </div>
        </div>
    </header>
</template>

<style scoped>

</style>
