<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import {onMounted, computed,reactive,defineProps,ref,watch } from 'vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
//Comboboxのインポート
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
import UserSerch from '@/Components/UserSerch.vue';

const props = defineProps({
    ships : Array,
    currentUser: {
        type: Object,
        default: null,
    },
    categories : Array,
    users :Array,
    errors: Object,
})

const form = reactive({         //内容をreactiveにform変数に収める
    selectedUser:       null,
    assignedUsersList:  [],
    name:               null, //Project名
    shipId:             null,
    pro_category_id:    null,
    start_date:         null,
    end_date:           null,
}) 

onMounted(() => {
            if (props.currentUser) {
                form.assignedUsersList.push(props.currentUser);
            }
        })

const formatDate = (date) => {
//   if (!date) return "N/A";
  return moment(date).format('YYYY年MM月DD日');
}

const handleUserId = (currentUser) =>{
  form.currentUser = currentUser
  // console.log("handleUserId:", index.userId)
}

const userIds = form.assignedUsersList.map(user => user.id);
const userSearch = ref();
const assignUser = () => {
    const selectedUserData = props.users.find(user => user.id === form.currentUser);
    if (selectedUserData) {
        form.assignedUsersList.push(selectedUserData);
            userSearch.value.boxClear();
    }
};

const unassignUser = (userId) => {
    form.assignedUsersList = form.assignedUsersList.filter(user => user.id !== userId);
};

//コンボボックス用の変数設定
let query = ref('')
let selectedShip = ref({id: null, name: ''})
//船検索ComboboxのinputBoxで船名検索
let filteredship = computed(() =>
  query.value === ''
    ? props.ships
    : props.ships.filter((vessel) =>
        vessel.name
          .toLowerCase()
          .replace(/\s+/g, '')
          .includes(query.value.toLowerCase().replace(/\s+/g, ''))
      )
)

const shipOptions = computed(() => {
  return [{ id: null, name: '' }, ...filteredship.value];
})

//船検索Comboboxのリストに造船所と船番を表示するため
const displayVesselData = (vessel) => {
  if (vessel.id === null) {
        return '選択なし';
    }
  return `${vessel.name} 　[ ${vessel.yard} ${vessel.ship_no} ]`;
}

//カテゴリー検索ComboboxのinputBoxでカテゴリー検索
let selectedCategory = ref({id: null, name: ''})

let filteredCategory = computed(() =>
  query.value === ''
    ? props.categories
    : props.categories.filter((proType) =>
      proType.name
          .toLowerCase()
          .replace(/\s+/g, '')
          .includes(query.value.toLowerCase().replace(/\s+/g, ''))
      )
)

const typeOptions = computed(() => {
  return [{ id: null, name: '' }, ...filteredCategory.value];
})

const storeproject = () => {
  form.shipId = selectedShip.value.id
  form.pro_category_id =  selectedCategory.value.id
  Inertia.post(route('projects.store'), form) 
  // console.log("selectUser:", form.assignedUsersList);
  // console.log("projectName:", form.name);
  // console.log("selectShip:", form.shipId);
  // console.log("selectCategory:", form.pro_category_id);
  // console.log("start_date:", formatDate(form.start_date));
  // console.log("end_date:", formatDate(form.end_date));
}

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
                    <!-- <form @submit.prevent="updateShip(form.id)" >   -->
                     <section class="text-gray-600 body-font relative">
                    
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                                
                                <div class="flex flex-col p-2 ml-4">
                                  <div id="pro_name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      <label> ◆　タスク名 </label>
                                        <div class="flex flex-wrap sm:flex-row sm:space-x-4">
                                          <input type="text" id="pro_name" name="pro_name" v-model="form.name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 ml-4 mr-6 leading-8 transition-colors duration-200 ease-in-out">                                    
                                        </div>
                                  </div>
                                  <div id="name" class="w-full lg:h-44 bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆　担当者
                                    <div class="flex flex-wrap sm:flex-row sm:space-x-0">
                                      <div class="h-32 w-44  overflow-auto">
                                        <div>
                                            <ul>
                                                <li v-for="user in form.assignedUsersList" :key="user.id">
                                                    {{ user.name }}
                                                    <button class="mx-4 px-1.5 py-0 text-xs bg-red-300  text-white font-semibold rounded-full hover:bg-red-400" @click="unassignUser(user.id)">削除</button>
                                                </li>
                                            </ul>
                                        </div>
                                      </div>
                                      <div class="flex flex-wrap sm:flex-row w-80">
                                        <button class="mr-4 mt-8 h-8 w-14 px-1.5 py-0 text-xs bg-blue-400  text-white font-semibold rounded hover:bg-blue-500" @click="assignUser">⇐追加</button>
                                        <UserSerch ref="userSearch" :userId="null" :users="props.users" @update:currentUser="handleUserId" class="mt-0 mb-40 w-40 z-10"/>
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
                                      <label for="shipSerch" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船の選択：</label>
                                      <!-- 船検索コンボボックス　ここから -->
                                      <Combobox v-model="selectedShip" id="shipSerch" name="shipSerch" class=" opacity-100 z-0">
                                            <div  class="relative mt-1" >
                                              <div
                                              class="relative w-full cursor-default  rounded bg-white text-left border-gray-300 focus:ring-2 sm:text-sm"
                                              >
                                                <ComboboxInput 
                                                  class="w-36 py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 rounded-lg bg-gray-100 focus:bg-white"
                                                  :displayValue="(vessel) => vessel.name"
                                                  @change="query = $event.target.value"
                                                />
                                                <ComboboxButton
                                                  class="absolute inset-y-0 right-0 flex items-center pr-2 "
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
                                                    v-for="vessel in shipOptions"
                                                    as="template"
                                                    :key="vessel.id"
                                                    :value="vessel"
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
                                        <!-- 船検索コンボボックス　ここまで -->
                                     </div>
                                     <div class="flex flex-col p-2 ml-4">
                                      <label for="typeSerch" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎プロジェクト区分：</label>
                                      <!-- Category検索コンボボックス　ここから -->
                                      <Combobox v-model="selectedCategory" id="typeSerch" name="typeSerch" class=" opacity-100 z-0">
                                            <div  class="relative mt-1" >
                                              <div
                                              class="relative w-full cursor-default  rounded bg-white text-left border-gray-300 focus:ring-2 sm:text-sm"
                                              >
                                                <ComboboxInput 
                                                  class="w-36 py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 rounded-lg bg-gray-100 focus:bg-white"
                                                  :displayValue="(proType) => proType.name"
                                                  @change="query = $event.target.value"
                                                />
                                                <ComboboxButton
                                                  class="absolute inset-y-0 right-0 flex items-center pr-2 "
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
                                                    v-if="filteredCategory.length === 0 && query !== ''"
                                                    class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                                  >
                                                    名前が見つかりません.
                                                  </div>

                                                  <ComboboxOption
                                                    v-for="proType in typeOptions"
                                                    as="template"
                                                    :key="proType.id"
                                                    :value="proType"
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
                                                        :class="{ 'font-medium': selectedCategory, 'font-normal': !selectedCategory }"
                                                      >
                                                        {{ proType.name  || '選択なし'  }}
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
                                        <!-- Category検索コンボボックス　ここまで -->
                                     </div>


                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="start" class="rounded  w-32 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎開始日（予定）：</label>
                                      <input type="date" id="start" name="start" v-model="form.start_date" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                 
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="end" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎終了予定日：</label>
                                      <input type="date" id="end" name="end" v-model="form.end_date" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    
                                  
                                  </div>
                                                
                        </div>
                      </div>
                    </div>
                        <div class="container px-5 py-2 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                <div class="p-0 w-full">
                                  <button  @click="storeproject" class="flex mx-auto text-white bg-indigo-500 border-0 mb-10 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>  
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

