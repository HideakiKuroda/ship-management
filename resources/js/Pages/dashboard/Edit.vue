<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import moment from 'moment';
import {onMounted, computed,reactive,ref,watch } from 'vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'

</script>

<template>
<Head title="タイトル" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">タイトル</h2>
    </template>

    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <BreezeValidationErrors :errors="errors" />  
                    <!-- <form @submit.prevent="updateShip(form.id)" >   -->
                     <section class="text-gray-600 body-font relative">


                     </section> 
                    </div>
                </div>
            </div>
        </div>
</AuthenticatedLayout>
</template>