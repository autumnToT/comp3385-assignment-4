<template>
  <div class="container mt-5">
    <h2>Login</h2>
    <form @submit.prevent="handleLogin" id="loginForm">
      <div v-if="errorMsg" class="alert alert-danger">
        {{ errorMsg }}
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" v-model="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" v-model="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router'; 

const email = ref('');
const password = ref('');
const errorMsg = ref('');
const router = useRouter();

const handleLogin = async () => {
  errorMsg.value = ''; 
  try {
    const response = await fetch('/api/v1/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    });

    const data = await response.json();

    if (!response.ok) {
      errorMsg.value = data.error || 'Login failed. Please check your credentials.';
      password.value = ''; 
    } else {
      console.log('Login successful:', data);
      localStorage.setItem('token', data.access_token);
      router.push('/movies'); 
    }
  } catch (error) {
    console.error('Login error:', error);
    errorMsg.value = 'An error occurred during login.';
    password.value = ''; 
  }
};
</script>