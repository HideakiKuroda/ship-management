<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const roles = ref([]);

onMounted(() => {
  axios.get('users/rolePermission')
    .then(response => {
      roles.value = response.data;
    })
    .catch(error => {
      console.error('Error:', error);
    });
});
</script>

<template>
    <div>
      <div v-for="role in roles" :key="role.id">
        <h3>{{ role.name }}</h3>
        <ul>
          <li v-for="permission in role.permissions" :key="permission.id">
            {{ permission.name }}
          </li>
        </ul>
      </div>
    </div>
  </template>
