<script setup>
import {ref} from 'vue';
import axios from '../axios';
import {useRouter} from 'vue-router';
import {createToaster} from '@meforma/vue-toaster';

const email = ref('');
const password = ref('');
const loading = ref(false);
const router = useRouter();
const toaster = createToaster({position: 'bottom-right'});

const login = async () => {
    loading.value = true;

    try {
        const response = await axios.post('/login', {email: email.value, password: password.value});
        localStorage.setItem('token', response.data.access_token);
        window.dispatchEvent(new Event('storage'));
        router.push('/');
    } catch (error) {
        toaster.error(error.response?.data?.message || 'Invalid credentials');
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div class="login-card">
        <div class="app-title">CSV-App Login</div>

        <form @submit.prevent="login">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" v-model="email" class="form-control"
                       placeholder="Enter your email" required/>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" v-model="password" class="form-control"
                       placeholder="Enter your password" required/>
            </div>

            <button type="submit" class="btn btn-success w-100" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                Login
            </button>
        </form>
    </div>
</template>

<style scoped>
.login-card {
    max-width: 400px;
    margin: 80px auto;
    padding: 2rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.app-title {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 1.5rem;
}
</style>
