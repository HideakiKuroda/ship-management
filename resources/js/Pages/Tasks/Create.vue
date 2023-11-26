<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import {onMounted, computed,reactive,ref,watch } from 'vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { initFlowbite } from 'flowbite'
import { VSwatches } from 'vue3-swatches'
import 'vue3-swatches/dist/style.css'

const props = defineProps({
    project : Object,
    errors: Object,
});

const form = reactive({         //内容をreactiveにform変数に収める
    proName:            props.project.name,
    projectId:          props.project.id,
    assignedUsersList:  [...props.project.users],
    ship:               props.project.ship,
    name:               null, //Task名
    start_date:         null,
    end_date:           null,
    deadline:           null,
    priorityName:       null,
    priorityColor:      null,
    priority:           null,
    color:              '#1CA085',
}); 

const formatDate = (date) => {
   if (!date) return "";
  return moment(date).format('YYYY年MM月DD日');
};

const storetask = () => {
  Inertia.post(route('tasks.store'), form) 
};

const handleChange = (event) => {
  form.priority = event.target.value;
    if(event.target.value == 1){ 
      form.priorityName = '優先度:① ※緊急度―大 ※重要度-大';
      form.priorityColor = 'bg-rose-100';
    }
    else if(event.target.value == 2){
       form.priorityName = '優先度:② ※緊急度―大 ※重要度-小';
       form.priorityColor = 'bg-yellow-100'
      }
    else if(event.target.value == 3){
       form.priorityName = '優先度:③ ※緊急度―小 ※重要度-大';
       form.priorityColor = 'bg-green-100'
      }
    else if(event.target.value == 4){
       form.priorityName = '優先度:④ ※緊急度―小 ※重要度-小';
       form.priorityColor = 'bg-blue-100';
      }
    else if(event.target.value == 5){
       form.priorityName = '優先度：指定しない';
       form.priorityColor = 'bg-gray-50';
      };
};

watch(form.color, (newValue, oldValue) => {
  console.log('colorCode:', oldValue);
  console.log('colorCode:', newValue);
});

onMounted(() => {
    initFlowbite();
})

</script>

<template>
    <Head title="新規タスクの登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">新規タスクの登録</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <BreezeValidationErrors :errors="errors" />  
                     <section class="text-gray-600 body-font relative">
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="md:flex lg:w-2/3 md:w-2/3 mx-auto">
                                   <div id="name" class="flex flex-wrap flex-auto md:w-1/2  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <span class="flex flex-nowrap md:pl-5">◆&nbsp;&nbsp;Project No.: {{ props.project.id }}</span><span class="md:pl-5" v-if="props.project.ships.id!==null">Ship:【 {{ props.project.ships.name }} 】</span><br>
                                        <span class="md:pl-8">Subject: {{ props.project.name }}</span>
                                   </div>
                                  <div id="name" class="flex-auto md:w-1/2  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆&nbsp;&nbsp;担当者
                                    <div class="flex flex-wrap sm:flex-row sm:space-x-0">
                                      <div class=" overflow-auto">
                                        <div>
                                            <ul>
                                                <li v-for="user in form.assignedUsersList" :key="user.id">
                                                    {{ user.name }}
                                                </li>
                                            </ul>
                                        </div>
                                      </div>
                                    </div> 
                                  </div> 
                          </div>
                        </div>

                        <div class="container px-5 py-0 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                                  <div class="flex flex-wrap sm:flex-col">
                                    <label>Task(内容):</label> 
                                    <div class="flex flex-row p-2"> 
                                      <input type="text" id="name" name="name" v-model="form.name" class="pl-2 w-full rounded" >
                                      <VSwatches v-model="form.color"/>  
                                    </div>
                                  <div class="flex flex-col sm:flex-row p-2 ml-4"> 
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="start" class="rounded  w-32 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎開始日（予定）：</label>
                                      <input type="date" id="start" name="start" v-model="form.start_date" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="end" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎終了予定日：</label>
                                      <input type="date" id="end" name="end" v-model="form.end_date" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="end" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎期限：</label>
                                      <input type="date" id="end" name="end" v-model="form.deadline" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  </div>
                                  </div>
                                  <div :class="['font-medium rounded-lg text-sm px-5 py-2.5 mb-1', form.priorityColor]"
                                  >{{ form.priorityName }}</div>

                                  
                                  <button id="dropdownRadioHelperButton" data-dropdown-toggle="dropdownRadioHelper"  
                                  :class="['border border-gray-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  ml-6 text-center inline-flex items-center', form.priorityColor]"
                                  type="button"> 優先度を設定<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                  </svg>
                                  </button>

                                  <!-- Dropdown menu -->
                                  <div id="dropdownRadioHelper" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-60 dark:bg-gray-700 dark:divide-gray-600">
                                      <ul  class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioHelperButton">
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-rose-200 bg-rose-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-1" name="helper-radio" type="radio" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-1" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度①</div>
                                                  <p id="helper-radio-text-1" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度-大 ※重要度-大</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-yellow-200 bg-yellow-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-2" name="helper-radio" type="radio" value="2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-2" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度②</div>
                                                  <p id="helper-radio-text-2" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度-大 ※重要度-小</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-green-200 bg-green-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-3" name="helper-radio" type="radio" value="3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-3" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度③</div>
                                                  <p id="helper-radio-text-3" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度-小 ※重要度-大</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-blue-200 bg-blue-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-4" name="helper-radio" type="radio" value="4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-4" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度④</div>
                                                  <p id="helper-radio-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度―小 ※重要度-小</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-gray-100 bg-gray-50">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-5" name="helper-radio" type="radio" value="5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-5" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>その他</div>
                                                  <p id="helper-radio-text-5" class="text-xs font-normal text-gray-500 dark:text-gray-300">特に優先度を指定しない</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                      </ul>
                                  </div>
                            </div>
                      </div>
                    </div>
                        <div class="container px-5 py-2 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                <div class="p-0 w-full">
                                  <button  @click="storetask" class="flex mx-auto text-white bg-indigo-500 border-0 mb-10 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>  
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

