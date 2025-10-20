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
        router.push('/');
    } catch (error) {
        toaster.error(error.response?.data?.message || 'Invalid credentials');
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width: 350px;">
            <h3 class="card-title text-center mb-3">CSV-App Login</h3>

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
    </div>
</template>

<style scoped>

</style>
