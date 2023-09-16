<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive,computed,ref,onMounted,watch } from 'vue';
import moment from 'moment';
import axios from 'axios';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import Paginator from 'primevue/paginator';


const props = defineProps({
    ships: Array,
    users: Array,
    projects: Object,
    currentUser:  Number,
});

const formatDate = (date) => {
   if (!date) return "未入力";
  return moment(date).format('YYYY年MM月DD日');
};

const index = reactive({
    ships       :props.ships,//ship全体
    users       :props.users,//user全体
    projects    :props.projects,
    userId      :props.currentUser,
    shipId      : null,
    EndOrNo     : null,
    crtDate     : null,
    crtAddDate  : null,
    endDate     : null,
    endAddDate  : null,
     });
   
const selectItem = async (userId, shipId, $uOrS, page = 1) => {
  if ($uOrS == 1){
    index.userId = userId;
  } else if($uOrS == 2) {
    index.shipId = shipId;
  }
  try {
    const response = await axios.post('/projects/indexfilter', { 
      userId: index.userId, 
      shipId: index.shipId, 
      page: page 
    });
    index.projects = response.data;
    pagination.value = index.projects;
  } catch (error) {
    console.error('Error:', error);
  }
};

const pagination = ref(props.projects);
const first = ref(pagination.current_page);

const changePage = async (page) => {
  try {
    const response = await axios.get('/getindex/indexfilter', {
      params: {
        userId: index.userId, 
        shipId: index.shipId, 
        page: page 
      }
    });
    index.projects = response.data;
    pagination.value = index.projects;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

import {
  Combobox,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
  TransitionRoot,
  ComboboxLabel
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const ship = props.ships
const user = props.users

let selectedShip = ref({id: null, name: ''})
let selectedUser = ref(props.currentUser || {id: null, name: ''});
let query = ref('')

let filteredUser = computed(() =>
  query.value === ''
    ? user
    : user.filter((person) =>
        person.name
          .toLowerCase()
          .replace(/\s+/g, '')
          .includes(query.value.toLowerCase().replace(/\s+/g, ''))
      )
)
watch(selectedUser, (newValue, oldValue) => {
  if (newValue && newValue !== oldValue) {
    selectItem(newValue.id, index.shipId, 1) // shipId は適切な値に置き換える必要があります
  }
})

let filteredship = computed(() =>
  query.value === ''
    ? ship
    : ship.filter((vessel) =>
        vessel.name
          .toLowerCase()
          .replace(/\s+/g, '')
          .includes(query.value.toLowerCase().replace(/\s+/g, ''))
      )
)
watch(selectedShip, (newValue, oldValue) => {
  if (newValue && newValue !== oldValue) {
    selectItem(index.userId, newValue.id, 2) 
  }
})

const displayVesselData = (vessel) => {
  if (vessel.id === null) {
        return '全船';
    }
  return `${vessel.name} 　[ ${vessel.yard} ${vessel.ship_no} ]`;
}

onMounted(() => {
    console.log(selectedUser.value)
    console.log(props.projects.data)
})



</script>

<template>
    <Head title="プロジェクト一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                プロジェクト一覧
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                        <div class="container px-5 py-8 mx-auto">
                            <FlashMessage />
                            <div class="flex flex-wrap sm:flex-row pl-4 my-4 lg:w-2/3 w-full mx-auto">
                            
                            <!-- 担当者検索コンボボックス　ここから -->
                            <div class="flex flex-col  mt-2">
                            <Combobox v-model="selectedUser" id="shipSerch" name="shipSerch" >
                                <div  class="relative ml-4" > <ComboboxLabel class=" text-sm ">担当者選択:</ComboboxLabel>
                                  <div
                                  class="relative w-full cursor-default  rounded bg-white text-left border-gray-300 focus:ring-2 sm:text-sm"
                                  >
                                    <ComboboxInput 
                                      class="w-36 py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 rounded-lg bg-gray-100 focus:bg-white"
                                      :displayValue="(person) => person.name"
                                      @change="query = $event.target.value"
                                      :value="selectedUser.name" 
                                                                            
                                    />
                                    <ComboboxButton
                                      class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                      <ChevronUpDownIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                      />
                                    </ComboboxButton>
                                  </div>
                                  <TransitionRoot
                                    leave="transition ease-in duration-100"
                                    leaveFrom="opacity-100"
                                    leaveTo="opacity-0"
                                    @after-leave="query = ''"
                                     >
                                    <ComboboxOptions
                                      class="absolute mt-1 max-h-60 w-50 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                      <div
                                        v-if="filteredUser.length === 0 && query !== ''"
                                        class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                      >
                                        名前が見つかりません.
                                      </div>
                                      
                                      <ComboboxOption
                                        v-for="person in [{ id: null, name: '' }, ...filteredUser]"
                                        as="template"
                                        :key="person.id"
                                        :value="person"
                                        v-slot="{ selected, active }"
                                                                                                                                                              
                                      >
                                        <li
                                          class="relative cursor-default select-none py-2 pl-10 pr-4"
                                          :class="{
                                            'bg-teal-600 text-white': active,
                                            'text-gray-900': !active,
                                          }" 
                                        >
                                          <span
                                            class="block truncate"
                                            :class="{ 'font-medium': selectedUser, 'font-normal': !selectedUser }"
                                          >
                                            {{ person.name  || '全員'  }}
                                          </span>
                                          <span
                                            v-if="selected" 
                                            class="absolute inset-y-0 left-0 flex items-center pl-3"
                                            :class="{ 'text-white': active, 'text-teal-600': !active }"
                                             
                                            >
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                          </span>
                                        </li>
                                      </ComboboxOption>
                                    
                                    </ComboboxOptions>
                                  </TransitionRoot>
                                </div>
                              </Combobox>
                            </div>
                            <!-- 担当者検索コンボボックス　ここまで -->

                            <!-- 船検索コンボボックス　ここから -->
                            <div class="flex flex-col  mt-2">
                            <Combobox v-model="selectedShip" id="shipSerch" name="shipSerch">
                                <div  class="relative ml-4" ><ComboboxLabel class=" text-sm ">船の選択:</ComboboxLabel>
                                  <div
                                  class="relative w-full cursor-default  rounded bg-white text-left border-gray-300 focus:ring-2 sm:text-sm"
                                  >
                                    <ComboboxInput 
                                      class="w-36 py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 rounded-lg bg-gray-100 focus:bg-white"
                                      :displayValue="(vessel) => vessel.name"
                                      @change="query = $event.target.value"
                                    />
                                    <ComboboxButton
                                      class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                      <ChevronUpDownIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                      />
                                    </ComboboxButton>
                                  </div>
                                  <TransitionRoot
                                    leave="transition ease-in duration-100"
                                    leaveFrom="opacity-100"
                                    leaveTo="opacity-0"
                                    @after-leave="query = ''"
                                  >
                                    <ComboboxOptions
                                      class="absolute mt-1 max-h-60 w-50 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                      <div
                                        v-if="filteredship.length === 0 && query !== ''"
                                        class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                      >
                                        名前が見つかりません.
                                      </div>

                                      <ComboboxOption
                                        v-for="vessel in [{ id: null, name: '' }, ...filteredship]"
                                        as="template"
                                        :key="vessel.id"
                                        :value="vessel"
                                        v-slot="{ selected, active }" 
                                        @click="selectItem(userId,vessel.id,2)"
                                      >
                                        <li
                                          class="relative cursor-default select-none py-2 pl-10 pr-4"
                                          :class="{
                                            'bg-teal-600 text-white': active,
                                            'text-gray-900': !active,
                                          }"
                                        >
                                          <span
                                            class="block truncate"
                                            :class="{ 'font-medium': selectedShip, 'font-normal': !selectedShip }"
                                          >
                                            {{ displayVesselData(vessel)  || '全船'  }}
                                          </span>
                                          <span
                                            v-if="selected"
                                            class="absolute inset-y-0 left-0 flex items-center pl-3"
                                            :class="{ 'text-white': active, 'text-teal-600': !active }"
                                          >
                                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                          </span>
                                        </li>
                                      </ComboboxOption>
                                    </ComboboxOptions>
                                  </TransitionRoot>
                                </div>
                              </Combobox>
                            </div>
                            <!-- 船検索コンボボックス　ここまで -->
                            <div class="flex flex-col p-2 ml-4 ">
                              <label for="crtDate" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">日付範囲指定</label>
                              <input type="date" id="crtDate" name="crtDate" v-model="crtDate" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                            </div>
                            
                             
                            
                            <Link as="button" :href="route('projects.create')" class="flex ml-auto h-10 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規プロジェクト作成</Link>
                           </div>
                          <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">船名</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">プロジェクト</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">作成日</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">完了日</th>
                                </tr>
                                </thead>
                                <tbody>
                                 <tr  v-for="project in index.projects.data" :key="project.id" >
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-600" :href="route('projects.show', { project:project.id })"> {{ project.id }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-600" :href="route('projects.show', { project:project.id })">{{ project.ships.name }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-600" :href="route('projects.show', { project:project.id })">{{ project.name }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(project.created_at) }}</td>
                                    
                                    <!-- 担当者（ユーザー）列 -->
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                    <div v-for="user in project.users">
                                        {{ user.name }}
                                    </div>
                                    </td>
                                    <!-- <td v-for="user in project.users"
                                    class="border-b-2 border-gray-200 px-4 py-3">{{ user.name  }}</td> -->
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(project.completion)  }}</td>
                                 </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!-- <Pagination  -->
                          <div class="w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <div class="card">
                            <Paginator 
                            v-model:first="first"
                            :rows="20"
                            :totalRecords="pagination.total" 
                            @page="changePage($event.page+1)"></Paginator>
                        </div>
                        <div class="text-center text-base font-mono">
                        件数: {{ pagination.total }}
                        　ページ数: {{ pagination.last_page }}
                      </div>
                      </div>
                        <!-- <Pagination </Pagination> -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
