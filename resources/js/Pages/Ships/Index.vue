<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import { toRefs, reactive,ref, computed,onMounted,watch } from 'vue';
import UserSerch from '@/Components/UserSerch.vue';

// CSRFトークンをメタタグから取得
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// Axiosの設定
axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

const props = defineProps({
    users: Array,
    ships: Array,
});

const ship = reactive({
    ships       :props.ships,
    users       :props.users,
    userId      :props.currentUser,
});

const handleUserId = (currentUser) =>{
  ship.userId = currentUser
  selectItem(ship.userId)
  //  console.log("handleUserId:", index.userId)
}

const selectItem = async (userId) => {
  try {
    const response = await axios.get('/getship/shipfilter', {
      params: {
        userId: userId, 
      }
    });
    // console.log("selectCategoryId:", index.EndOrNo)

    ship.ships = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

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
            <div class="max-w-full mx-auto sm:px-2 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                      <section class="text-gray-600 body-font">
                        <div class="container px-5 py-8 mx-auto">
                           <FlashMessage /> 
                          <div class="flex flex-col pl-4 my-4 lg:w-2/3 w-full mx-auto">
                           <div class="mb-1 mt-1 flex-col md:flex-row">
                             <Link as="button" :href="route('ships.create')" class="flex ml-auto h-10 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded justify-end">新規船登録</Link>
                           </div>
                            <!-- 担当者検索コンボボックス　ここから -->
                           <div class="mb-2 flex-col md:flex-row">
                            <UserSerch :userId="ship?.userId" :users="props.users" @update:currentUser="handleUserId" class="justify-start opacity-100 z-10"/>
                           </div>
                           <!-- 担当者検索コンボボックス　ここまで -->
                           <!-- PC用のテーブル表示はここから -->
                           <div class="w-full lg:w-full mx-auto overflow-auto">
                            <table class="table-fixed w-full text-left whitespace-no-wrap hidden sm:table">
                                <thead>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">★</th>
                                    <th class="pr-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">船名</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">造船所</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">船番</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">担当者</th>
                                </thead>
                                <tbody>
                                 <tr  v-for="ship in ship.ships" :key="ship.id">
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-500" :href="route('ships.show', { ship:ship.id })"> {{ ship.id }} </Link></td>
                                    <td class="border-b-2 border-gray-200 pr-4 py-3">
                                        <Link class="text-blue-500" :href="route('ships.show', { ship:ship.id })">{{ ship.name }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.yard }}</td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.ship_no }}</td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                    <div v-for="user in ship.users">
                                        {{ user.name }}
                                    </div>
                                    </td>
        
                                 </tr>
                                </tbody>
                            </table>
                           </div>
                          <!-- PC用のテーブル表示はここまで -->
                         </div> 
                        </div>  <!-- Flashメッセージ・検索・テーブルのコンテナ -->
             
                    <!-- スマホ表示: 縦並び -->
                     <div class="container ml-8 px-4">
                       <div v-for="ship in ship.ships" :key="ship.id" class="block sm:hidden">
                        <div class="mb-4">
                            <strong>船名:</strong><br>
                            <span>
                                <Link class="text-blue-500" :href="route('ships.show', { ship:ship.id })"> {{ ship.id }} </Link>
                                &emsp;&emsp;
                                <Link class="text-blue-500 font-bold text-lg" :href="route('ships.show', { ship:ship.id })">{{ ship.name }} </Link>
                            </span>
                        </div>
                        <div class="mb-4 border-b-2 border-gray-200">
                            <strong>造船所&emsp;&emsp;船番&emsp;&emsp;&emsp;担当者:</strong><br>
                            <div class="flex flex-row">
                                <span class="block">{{ ship.yard }}&emsp;&emsp;&emsp;{{ ship.ship_no }}&emsp;&emsp;&emsp;</span>
                                <div class="flex flex-col">
                                    <div v-for="user in ship.users" :key="user.id" class="block">{{ user.name }}</div>
                                </div>
                            </div>
                        </div >
                        </div>
                     </div>
                     <!-- スマホ表示: 縦並び -->
      
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
<style scoped>
strong {
  font-weight: bold;
}
</style>

