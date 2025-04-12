<template>
  <!-- the bonus with the messages not included was giving problems -->
  <form @submit.prevent="saveMovie" id="movieForm" enctype="multipart/form-data">
    <div class="form-group mb-3">
      <label for="title" class="form-label">Movie Title</label>
      <input type="text" name="title" class="form-control" />
    </div>
    <div class="form-group mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="form-group mb-3">
      <label for="poster" class="form-label">Poster</label>
      <input type="file" name="poster" class="form-control" accept="image/*"/>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</template>
<script setup>
import { ref } from 'vue';

function saveMovie() {
  let movieForm = document.getElementById('movieForm');
  let form_data = new FormData(movieForm);
  
  const token = localStorage.getItem('token');

  if (!token){
	console.error('Authentication token not found. Please log in.');
	return;
  }
  fetch("/api/v1/movies", {
    method: 'POST',
    body: form_data,
    headers: {
      'Accept': 'application/json',
	  'Authorization': `Bearer ${token}`
    }
  })
  .then(response => {
      if (!response.ok) {
          return response.json().then(errData => {
              throw { status: response.status, data: errData };
          });
      }
      return response.json();
  })
  .then(data => {
    console.log('Success:', data);
    movieForm.reset();
  })
  .catch(error => {
    console.error('Error:', error);
    if (error.status === 422) {
      console.log('Validation Errors:', error.data.errors);
    } 
	else {
      console.log('An error occurred:', error.data ? error.data.message : 'Network error');
    }
  });
}
</script>