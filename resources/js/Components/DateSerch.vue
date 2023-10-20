<template>
  <div class="w-full max-w-md m-2 px-5 py-3 sm:px-0 bg-indigo-50 rounded-xl">
    <TabGroup @change="handleTabChange">
      <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-2 m-2">
        <Tab
          v-for="(category,tabIndex) in Object.keys(categories)"
          as="template"
          :key="category"
          v-slot="{ selected }"
          v-model="slectedTab"
         >
          <button
            :class="[
              'w-full rounded-lg py-1.5 text-sm font-medium leading-5 text-blue-700',
              'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
              selected
                ? 'bg-white shadow'
                : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
            ]"
           
          >
            {{ category }}
          </button>
        </Tab>
      </TabList>
    </TabGroup>
<div class="md:ml-20 ml-10">
    <label v-if="newTabIndex == 0" class="text-justify text-sm font-medium text-indigo-700 underline">未完了の案件を作成日で絞込み</label>
    <label v-else-if="newTabIndex == 1" class="text-justify text-sm font-medium text-indigo-700 underline">完了済み案件を完了日で絞込み</label>
    <label v-else="newTabIndex == 2" class="text-justify text-sm font-medium text-indigo-700 underline">全案件を作成日で絞込み</label>

</div>   
    <div class="flex flex-col md:flex-row p-0 ml-4">
       <!-- 日付入力　ここから -->
            <div class="flex flex-col p-0 md:ml-8 ml-3">
              <label for="crtDate" class="rounded  w-32 leading-tight  text-justify text-sm text-gray-600">日付 ➡</label>
              <input type="date" id="crtDate" name="crtDate" v-model="serchDate" class="w-40 h-9 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
            </div> 
       <!-- 日付範囲選択　ここから -->
            <div class="flex flex-col p-0 md:ml-4 ml-0">
              <label for="crtDate" class="rounded ml-3 w-32 leading-tight  text-justify text-sm text-gray-600">期間</label>
              <div>       
                <Listbox v-model="selectedPeriod">
                  <ListboxButton
                    class="relative w-40 h-10 ml-3 cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm"
                  >
                    <span class="block truncate">{{ selectedPeriod.date }}</span>
                    <span
                      class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                    >
                      <ChevronUpDownIcon
                        class="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                      />
                    </span>
                  </ListboxButton>
          
                    <ListboxOptions

                      class="absolute mt-1 max-h-60 w-40 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    >
                      <ListboxOption
                        v-slot="{ active, selected }"
                        v-for="(search) in term"
                        :key="search.date"
                        :value="search"
                        as="template"
                       
                      >
                        <li
                          :class="[
                            active ? 'bg-amber-100 text-amber-900' : 'text-gray-900',
                            'relative cursor-default select-none py-2 pl-10 pr-4',
                          ]"
                          
                        >
                          <span
                            :class="[
                              selected ? 'font-medium' : 'font-normal',
                              'block truncate',
                            ]"
                            >{{ search.date }}</span
                          >
                          <span
                            v-if="selected"
                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                          >
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                          </span>
                        </li>
                      </ListboxOption>
                    </ListboxOptions>
                </Listbox>
              </div>
            </div> 
        <!-- 日付範囲選択　ここまで -->
    </div>
  </div>
</template>

<script setup>
import { ref,defineEmits,watch } from 'vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
  } from '@headlessui/vue'

  
  const term = [
    { date: '30日間' },
    { date: '7日間' },
    { date: '15日間' },
    { date: '90日間' },
    { date: '120日間' },
  ]
const categories = ref({
  未完了案件: [
    {
      id: 0,
      title: '未完了の案件を作成日で絞込み',
      },
  ],
  完了済案件: [
    {
      id: 1,
      title: '完了案件を完了日で絞込み',
      },
  ],
  全ての案件: [
    {
      id: 2,
      title: '全案件を作成日で絞込み',
      },
  ],
})
const slectedTab = ref(0)
const serchDate = ref(''); 
const selectedPeriod = ref(term[0])
const newTabIndex = ref(0)
const termdays = ref(7)

const emit = defineEmits([
  'update:categoryId',
  'update:serchDate',
  'update:termD',
])  
const handleTabChange = (tabIndex) => {
  emit('update:categoryId', tabIndex); // 親にデータを送信
  newTabIndex.value = tabIndex;
}
watch(serchDate, (newDate) => {
  emit('update:serchDate', newDate); // 親にデータを送信
});
watch(selectedPeriod, (newPeriod) => {
  const index = term.findIndex(p => p.date === newPeriod.date);
  if (index !== -1) {
    if (index == 0){ termdays.value = 30}
    if (index == 1){ termdays.value = 7}
    if (index == 2){ termdays.value = 15}
    if (index == 3){ termdays.value = 90}
    if (index == 4){ termdays.value = 120}
    emit('update:termD', termdays.value); // 親にデータを送信
  }
});

</script>
