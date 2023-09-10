<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
// import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive,computed,ref,onMounted } from 'vue';
import moment from 'moment';
import axios from 'axios';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'



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
  } catch (error) {
    console.error('Error:', error);
  }
};

const pagination = ref(props.projects);

const changePage = async (page) => {
  try {
    const response = await axios.get('/projects/indexfilter', {
      userId: index.userId, 
      shipId: index.shipId, 
      page: page 
    });
    pagination.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};
// const selectItem = async (userId, shipId, $uOrS) => {
//   if ($uOrS == 1) {
//     index.userId = userId;
//   } else if ($uOrS == 2) {
//     index.shipId = shipId;
//   }

//   // Inertiaのvisitメソッドを使用して非同期リクエストを行う
//   Inertia.visit(route('project.indexfilter', {userId: index.userId, shipId: index.shipId}), {
//     method: 'post', // リクエストメソッドを指定
//     data: {
//       userId: index.userId,
//       shipId: index.shipId
//     },
//     onSuccess: (page) => {
//       console.log(page.props);
//       index.projects = page.props.projects; // 仮定しているレスポンス構造
//     },
//     onError: (errors) => {
//       console.error('Error:', errors);
//     }
//   });
// };

const selectedUserName = computed(() => {
    if (index.userId) {
      const user = props.users.find(user => user.id === index.userId);
      return user ? user.name : '担当者で絞込み';
    } else {
      return '担当者で絞込み';
    }
  });

  const selectedShipName = computed(() => {
    if (index.shipId) {
      const ship = props.ships.find(ship => ship.id === index.shipId);
      return ship ? ship.name+'['+ship.yard+'.'+ship.ship_no+']' : '船名で絞込み';
    } else {
      return '船名で絞込み';
    }
  });
  onMounted(() => {
    console.log(selectedUserName.value)
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
                            
                             <!-- 担当者の検索（ドロップダウンリスト）                                -->
                            <Menu as="div" class="mb-5 relative inline-block text-left">
                                <div>
                                <MenuButton class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    {{ selectedUserName }}
                                    <ChevronDownIcon class="-mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
                                </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                <MenuItems class="absolute  z-10 mt-2 w-32 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            style="max-height: 300px; overflow-y: auto;">
                                    <MenuItem v-slot="{ active }">
                                        <a @click="selectItem(null,shipId,1)" href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">全員表示</a>
                                    </MenuItem>
                                    <div v-for="user in index.users"  :key="user.id" class="py-1">
                                    <MenuItem v-slot="{ active }">
                                        <a @click="selectItem(user.id,shipId,1)" href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{ user.name }}</a>
                                    </MenuItem>
                                      </div>
                                </MenuItems>
                                </transition>
                            </Menu>
                            
                             <!-- 船の検索（ドロップダウンリスト）     -->
                             <Menu as="div" class="ml-5 mb-5 relative inline-block text-left">
                                <div>
                                <MenuButton class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    {{ selectedShipName }}
                                    <ChevronDownIcon class="-mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
                                </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                <MenuItems class="absolute z-10 mt-2 w-44 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            style="max-height: 300px; overflow-y: auto;">
                                    <MenuItem v-slot="{ active }">
                                        <a @click="selectItem(userId,null,2)" href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">全船表示</a>
                                    </MenuItem>
                                    <div v-for="ship in index.ships"  :key="ship.id" class="py-1">
                                    <MenuItem v-slot="{ active }">
                                        <a @click="selectItem(userId,ship.id,2)" href="#" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{ ship.name }}[{{ ship.yard }}-{{ ship.ship_no }}]</a>
                                    </MenuItem>
                                      </div>
                                </MenuItems>
                                </transition>
                            </Menu>

                            
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
                        <!-- <Pagination :data="customers"/> -->
                            <nav aria-label="Page navigation example">
                                <ul class="inline-flex -space-x-px text-base h-10">
                                    <li>
                                        <a href="#" :disabled="pagination.current_page === 1" @click.prevent="changePage(pagination.current_page - 1)" class="flex items-center justify-center px-4 h-10 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">前</a>
                                    </li>
                                    <!-- pagination.last_pageを使用 -->
                                    <li v-for="page in pagination.last_page" :key="page">
                                        <a href="#" @click.prevent="changePage(page)" :class="{'text-blue-600 bg-blue-50': pagination.current_page === page}" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page }}</a>
                                    </li>
                                    <li>
                                        <a href="#" :disabled="pagination.current_page === pagination.last_page" @click.prevent="changePage(pagination.current_page + 1)" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">次</a>
                                    </li>
                                </ul>
                            </nav>
                        <!-- <Pagination :data="customers" :search="search"></Pagination> -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
