<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, computed } from 'vue';
import { toRefs, reactive } from 'vue';

const props = defineProps({
    users: Array,
    ships: Array,
});

const state = reactive({
    selectedUser: ""
});

const displayedShips = computed(() => {
    if (state.selectedUser) {
        const user = props.users.find(user => user.id === state.selectedUser);
        return user ? user.ships.map(ship => ({...ship, userName: user.name })) : [];
    } else {
        return props.ships.map(ship => {
            const user = props.users.find(user => user.ships.some(s => s.id === ship.id));
            return {...ship, userName: user ? user.name : 'N/A'};
        });
    }
});

</script>

<template>
    <Head title="船舶一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                船舶一覧
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                        <div class="container px-5 py-8 mx-auto">
                           <FlashMessage /> 
                           <div class="flex pl-4 my-4 lg:w-2/3 w-full mx-auto">
                            <div>
                                <!-- ユーザー選択ドロップダウン -->
                                <select v-model="state.selectedUser">
                                    <option value="">All Users</option>
                                    <option v-for="user in props.users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>
                            <Link as="button" :href="route('ships.create')" class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規船登録</Link>
                           </div>
                          <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">船名</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">造船所</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">船番</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者</th>
                                </tr>
                                </thead>
                                <tbody>
                                 <tr  v-for="ship in displayedShips" :key="ship.id">
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-400" :href="route('ships.show', { ship:ship.id })"> {{ ship.id }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-400" :href="route('ships.show', { ship:ship.id })">{{ ship.name }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.yard }}</td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.ship_no }}</td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.userName  }}</td>
                                 </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!-- <Pagination :data="customers"/> -->
                        <!-- <Pagination :links="customers.links"></Pagination> -->
                        <!-- <Pagination :data="customers" :search="search"></Pagination> -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
