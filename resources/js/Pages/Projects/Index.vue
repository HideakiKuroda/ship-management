<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive,computed,ref,onMounted,watch } from 'vue';
import moment from 'moment';
import axios from 'axios';
import Paginator from 'primevue/paginator';
// import Micromodal from '@/Components/Micromodal.vue';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import DateSerch from '@/Components/DateSerch.vue';

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
    EndOrNo     : 0,
    crtDate     : null,
    crtAddDate  : null,
    endDate     : null,
    endAddDate  : null,
     });

const safeParseInt = (value) => {
    const result = parseInt(value, 10); // 常に基数10を指定してください
    return isNaN(result) ? 0 : result; // NaNの場合、デフォルトの値を返す（この例では0）
}

const addDaysToDate = (dateString, daysToAdd) => {
  if (!dateString) {
        console.error("Invalid date string:", dateString);
        return null;
  }
    // 日付の文字列を Date オブジェクトに変換
    let date = new Date(dateString);

    // 日数を追加
    // console.log("daysToAdd:", daysToAdd);
    if (daysToAdd !== null && daysToAdd !== 0){
    date.setDate(date.getDate() + daysToAdd);
    }
    else {date.setDate(date.getDate() + 30);
    }
          
    // 日付を 'yyyy-mm-dd' 形式の文字列に変換して返す
    let month = '' + (date.getMonth() + 1),
        day = '' + date.getDate(),
        year = date.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
} 

//船と担当者の絞込み     
const selectItem = async (userId, shipId,$uOrS, page = 1) => {
  if ($uOrS == 1){          //1で担当者検索　2で船の検索
    index.userId = userId;
  } else if($uOrS == 2) {
    index.shipId = shipId;
  } else if($uOrS == 3) {}
  const daysToAdd = safeParseInt(index.crtAddDate);
  // console.log("Parsed daysToAdd:", daysToAdd);
  let newDate = null;
  if (index.crtDate && index.EndOrNo !== 1){
    newDate = addDaysToDate(index.crtDate, daysToAdd);
    }else if(index.crtDate == null){
      newDate = null;
    }
  let newendDate = null; 
  if (index.endDate && index.EndOrNo == 1){
    newendDate = addDaysToDate(index.endDate, daysToAdd);
    }else if(index.endDate == null){
    newendDate = null;
    }
  // console.log("newDate:", index.crtDate, newDate,index.endDate, newendDate);    
  try {
    const response = await axios.post('/projects/indexfilter', { 
      userId: index.userId, 
      shipId: index.shipId,
      EndOrNo: index.EndOrNo,
      
      crtDate: index.crtDate,
      endDate: index.endDate,
      crtAddDate:newDate, 
      endAddDate :newendDate,
      page: page 
    });
    // console.log("dateSerch:", index.crtDate,newDate)
    index.projects = response.data;
    pagination.value = index.projects;
  } catch (error) {
    console.error('Error:', error);
  }
};
//DateSerch.vueの変更によるメソッド

const handleCategoryId = (categoryId) =>{
  index.EndOrNo = categoryId
  selectItem(index.userId, index.shipId, 3)
  // console.log("handleCategoryId:", index.EndOrNo)
}

const handleSerchDate = (serchDate) => {
  if (index.EndOrNo !== 1){
    index.crtDate = serchDate
    index.endDate = null
  }
  else{
    index.endDate = serchDate
    index.crtDate = null
  }
  selectItem(index.userId, index.shipId, 3)
  // console.log("handleSerchDate:", index.crtDate,index.endDate)
}

const handleTermD = (termD) => {
  index.crtAddDate = termD
  if (index.crtDate !== null || index.endDate !== null){
  selectItem(index.userId, index.shipId, 3)}
  // console.log("handleTermD:", index.crtAddDate)
}

///ここから変更要


//ページネイションの設定
const pagination = ref(props.projects);
const first = ref(pagination.current_page);

const changePage = async (page) => {
  try {
    const response = await axios.get('/getindex/indexfilter', {
      params: {
        userId: index.userId, 
        shipId: index.shipId,
        EndOrNo: index.EndOrNo,
        // crtDate: index.crtDate + index.crtAddDate,
        // endDate: index.endDate,
        // crtAddDate:index.crtAddDate, 
        // endAddDate :index.endAddDate +index.endDate,
        page: page 
      }
    });
    // console.log("selectCategoryId:", index.EndOrNo)

    index.projects = response.data;
    pagination.value = index.projects;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

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

//コンボボックス用の変数設定
const ship = props.ships
const user = props.users
const curntUIdx = computed(() => {
  return user.findIndex(ur => ur?.id === index.userId)
})
let selectedShip = ref({id: null, name: ''})
let selectedUser = ref({id:user[curntUIdx.value].id,name:user[curntUIdx.value].name} || {id: null, name: ''});
let query = ref('')

//担当者索ComboboxのinputBox内での名前検索
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

//担当者索Comboboxでリストから選んだ時の動作
watch(selectedUser, (newValue, oldValue) => {
  if (newValue && newValue !== oldValue) {
    selectItem(newValue.id, index.shipId, 1) // shipId は適切な値に置き換える必要があります
  }
})

//船検索ComboboxのinputBoxで船名検索
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

//船検索Comboboxでリストから選んだ時の動作
watch(selectedShip, (newValue, oldValue) => {
  if (newValue && newValue !== oldValue) {
    selectItem(index.userId, newValue.id,2) 
  }
})

//船検索Comboboxのリストに造船所と船番を表示するため
const displayVesselData = (vessel) => {
  if (vessel.id === null) {
        return '全船';
    }
  return `${vessel.name} 　[ ${vessel.yard} ${vessel.ship_no} ]`;
}

onMounted(() => {
  // console.log('props.users:', props.users);
  //   console.log(props.projects.data)
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
                            <div class="flex flex-col md:flex-row mt-2">
                              <div class="flex flex-row ">
                            <Combobox v-model="selectedUser" class=" opacity-100 z-10">
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
                                      class="absolute inset-y-0 right-0 flex items-center pr-2 opacity-100 z-10"
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
                            <!-- 担当者検索コンボボックス　ここまで -->

                            <!-- 船検索コンボボックス　ここから -->
                            <Combobox v-model="selectedShip" id="shipSerch" name="shipSerch" class=" opacity-100 z-10">
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
                                        v-for="vessel in [{ id: null, name: '' }, ...filteredship]"
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
                            <div class="ml-32 mt-3 w-2/3">
                            <Link as="button" :href="route('projects.create')" class=" h-10 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規プロジェクト作成</Link>
                          </div>
                        </div>


                            <!-- 日付の検索　ここから -->
                            <!-- categoryId,serchDate,termD -->
                            <DateSerch 
                            @update:categoryId="handleCategoryId"
                            @update:serchDate="handleSerchDate"
                            @update:termD="handleTermD"
                            class="z-0"></DateSerch>

                            <!-- 日付の検索　ここまで -->
                             
                            
                           </div>
                          <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap ">
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
