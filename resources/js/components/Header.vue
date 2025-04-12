<script setup>
	import { ref, computed, watchEffect } from 'vue';
	import { useRouter } from 'vue-router';
	import { RouterLink } from 'vue-router';

	const router = useRouter();
	const token = ref(localStorage.getItem('token'));
	const isLoggedIn = computed(() => !!token.value);

	watchEffect(() => {
		token.value = localStorage.getItem('token');
	});

	const logout = async () => {
	  const currentToken = localStorage.getItem('token');

	  if (!currentToken) {
		console.warn('Attempted logout without a token.');
		router.push('/login');
		return;
	  }

	  try {
		console.log('Sending logout request to server...');
		const response = await fetch('/api/v1/logout', {
		  method: 'POST',
		  headers: {
			'Accept': 'application/json',
			'Authorization': `Bearer ${currentToken}`
		  }
		});

		if (response.ok) {
			const data = await response.json();
			console.log('Server logout successful:', data.message);
		} else {
			console.error('Server logout failed:', response.status, await response.text());
		}

	  } catch (error) {
		console.error("Error calling logout API:", error);
	  } finally {
		console.log('Performing client-side logout...');
		localStorage.removeItem('token');
		token.value = null;
		router.push('/login');
	  }
	};
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">COMP3385</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <RouterLink class="nav-link" :class="{ active: $route.path === '/'}" to="/">Home</RouterLink>
                    </li>
                    <li class="nav-item">
                        <RouterLink class="nav-link" :class="{ active: $route.path === '/about'}" to="/about">About</RouterLink>
                    </li>
					<li class="nav-item">
						<RouterLink class="nav-link" :class="{ active: $route.path === '/movies'}" to="/movies">Movies</Routerlink>
					</li>
					<li class="nav-item">
						<RouterLink class="nav-link" :class="{ active: $route.path === '/movies/create'}" to="/movies/create">Add Movies</Routerlink>
					</li>
                </ul>
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li v-if="!isLoggedIn" class="nav-item">
                        <RouterLink class="nav-link" active-class="active" to="/login">Login</RouterLink>
                    </li>
                    <li v-if="isLoggedIn" class="nav-item">
                        <a class="nav-link" href="#" @click.prevent="logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
