<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import { toRefs, reactive,ref, computed,onMounted,watch } from 'vue';
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

const props = defineProps({
    users: Array,
    ships: Array,
});

const ship = reactive({
    ships       :props.ships,
    users       :props.users,
    userId      :props.currentUser,
});

const user = props.users
let selectedUser = ref({id: null, name: ''});
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
    selectItem(newValue.id) 
  }
})

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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                        <div class="container px-5 py-8 mx-auto">
                           <FlashMessage /> 
                           <div class="flex pl-4 my-4 lg:w-2/3 w-full mx-auto">
                                <!-- ユーザー選択ドロップダウン -->
                            <div>
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
                            </div>
                            </div>

                            </div>
                            <Link as="button" :href="route('ships.create')" class="flex ml-auto h-10 text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規船登録</Link>
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
                                 <tr  v-for="ship in ship.ships" :key="ship.id">
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-400" :href="route('ships.show', { ship:ship.id })"> {{ ship.id }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                        <Link class="text-blue-400" :href="route('ships.show', { ship:ship.id })">{{ ship.name }} </Link></td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.yard }}</td>
                                    <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.ship_no }}</td>
                                    <!-- <td class="border-b-2 border-gray-200 px-4 py-3">{{ ship.users.name  }}</td> -->
                                    <!-- 担当者（ユーザー）列 -->
                                    <td class="border-b-2 border-gray-200 px-4 py-3">
                                    <div v-for="user in ship.users">
                                        {{ user.name }}
                                    </div>
                                    </td>
        
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
