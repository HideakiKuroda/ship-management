
<template>
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

</template>
  
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


  </script>
  