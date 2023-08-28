<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { reactive } from 'vue';

const props = defineProps({
    navigationAreas : Array,
    operatSections : Array,
    users :Array,
    errors: Object,
});

const form = reactive({         //内容をreactiveにform変数に収める
    selectedUser:       null,
    assignedUsersList:  [],
    name:               null,
    yard:               null,
    ship_no:            null,
    ex_name:            null,
    former_name:        null,
    slectedOperatSection : null,
    selectedNavigationArea : null,
    delivered:           null,
    gross_tonn:           null,
}) ;

const storeShip = () => {
    Inertia.post(route('ships.store'), form) 
};

const formatDate = (date) => {
//   if (!date) return "N/A";
  return moment(date).format('YYYY年MM月DD日');
};

const userIds = form.assignedUsersList.map(user => user.id);

const assignUser = () => {
    const selectedUserData = props.users.find(user => user.id === form.selectedUser);
    if (selectedUserData) {
        form.assignedUsersList.push(selectedUserData);
    }
};

const unassignUser = (userId) => {
    form.assignedUsersList = form.assignedUsersList.filter(user => user.id !== userId);
};


</script>

<template>
    <Head title="船舶の登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">船舶の登録</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <!-- <form @submit.prevent="updateShip(form.id)" >   -->
                     <section class="text-gray-600 body-font relative">
                    
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                                
                                <div class="flex flex-col p-2 ml-4">
                                  <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      <label> ◆　船名・造船所・船番 </label>
                                        <div class="flex flex-wrap sm:flex-row sm:space-x-4">
                                          <input type="text" id="name" name="name" v-model="form.name" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 ml-4 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="text" id="yard" name="yard" v-model="form.yard" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="number" id="ship_no" name="ship_no" v-model="form.ship_no" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                           
                                        </div>
                                  </div>
                                  <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆　担当者
                                    <div class="flex flex-wrap sm:flex-row sm:space-x-0">
                                        <div class="h-20 w-40 overflow-auto">
                                          <div>
                                              <ul>
                                                  <li v-for="user in form.assignedUsersList" :key="user.id">
                                                      {{ user.name }}
                                                      <button class="mx-4 px-1.5 py-0 text-xs bg-red-300  text-white font-semibold rounded-full hover:bg-red-400" @click="unassignUser(user.id)">削除</button>
                                                  </li>
                                              </ul>
                                          </div>
                                        </div>
                                        <div class="flex flex-wrap sm:flex-row h-20 w-80">
                                          <button class="mr-4 h-8 w-14 px-1.5 py-0 text-xs bg-blue-400  text-white font-semibold rounded hover:bg-blue-500" @click="assignUser">⇐追加</button>
                                          <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.selectedUser">
                                            <option v-for="user in users" :value="user.id" :key="user.id">
                                                {{ user.name }}
                                            </option>
                                          </select>
                                        </div>
                                    </div> 
                                  </div>

                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="container px-5 py-0 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                             
     
                                  <div class="flex flex-wrap sm:flex-row">
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="ship.ex_name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船名（前）：</label>
                                      <input type="text" id="ship.ex_name" name="ship.ex_name" v-model="form.ex_name" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>

                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="former_name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船名（元）：</label>
                                      <input type="text" id="former_name" name="former_name" v-model="form.former_name" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="delivered" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎竣工：</label>
                                      <input type="date" id="delivered" name="delivered" v-model="form.delivered" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                 
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="gross_tonn" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎総トン数：</label>
                                      <input type="tel" id="gross_tonn" name="gross_tonn" v-model="form.gross_tonn" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="section" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎運航地域：</label>
                                      <div id="section" class=" w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" >
                                      <!-- ユーザー選択ドロップダウン -->
                                      <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.slectedOperatSection">
                                        <option v-for="operatSection in operatSections" :key="operatSection.id" :value="operatSection.id">
                                          {{ operatSection.section }}
                                         </option>
                                      </select>
                                    </div>  
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎航行区域 :</label>
                                      <div id="name" class="w-48   text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      <!-- ユーザー選択ドロップダウン -->
                                      <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.selectedNavigationArea">
                                        <option v-for="navigationArea in navigationAreas" :key="navigationArea.id" :value="navigationArea.id">
                                          {{ navigationArea.name }} 
                                        </option>
                                      </select>    
                                      </div>
                                    </div>
                                  
                                  </div>
                                                
                        </div>
                      </div>
                    </div>
                        <div class="container px-5 py-2 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                <div class="p-0 w-full">
                                  <button  @click="storeShip" class="flex mx-auto text-white bg-indigo-500 border-0 mb-10 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>  
                                </div>
                                <div class="p-0 w-full">
                                </div>
                            </div>
                          </div>
                        </div>

                        </section> 
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

