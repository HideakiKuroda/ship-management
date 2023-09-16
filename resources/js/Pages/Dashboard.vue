<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Link } from '@inertiajs/vue3';

const test = async () => {
  try {
    const response = await axios.post('/testtest', { 
    });
    index.projects = response.data;
  } catch (error) {
    console.error('Error:', error);
  }
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <FlashMessage  />
          <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900"></div>
                    <Link as="button" :href="route('user.register')" class=" ml-auto h-10 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規ユーザー登録</Link>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
