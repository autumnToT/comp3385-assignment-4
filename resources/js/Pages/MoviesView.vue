<template>
  <div>
    <h1>Movies</h1>
    <div v-if="movies.length === 0" class="alert alert-info">
      Loading movies or no movies added yet...
    </div>

    <div v-else class="row">
      <div v-for="movie in movies" :key="movie.id" class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
          <img v-if="movie.poster" :src="movie.poster" class="card-img-top" :alt="movie.title + ' Poster'" style="max-height: 400px; object-fit: cover;">
          <div v-else class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px;">
            No Poster Available
          </div>

          <div class="card-body">
            <h5 class="card-title">{{ movie.title }}</h5>
            <p class="card-text">{{ movie.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const movies = ref([]);

function fetchMovies() {

  const token = localStorage.getItem('token');

  if (!token) {
    console.error('Authentication token not found. Please log in.');
    return;
  }
  fetch("/api/v1/movies", {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
	  'Authorization': `Bearer ${token}`
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
  })
  .then(data => {
    if (data && Array.isArray(data.movies)) {
       movies.value = data.movies;
    } else {
        console.error("API response is not in the expected format:", data);
        movies.value = [];
    }
    console.log("Movies fetched:", movies.value);
  })
  .catch(error => {
    console.error('Error fetching movies:', error);
    movies.value = [];
  });
}
onMounted(() => {
  fetchMovies();
});
</script>