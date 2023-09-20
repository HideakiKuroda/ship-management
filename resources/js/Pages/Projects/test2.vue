<template>
  <!-- 日付範囲選択　ここから -->
                <Listbox v-model="selectedPeriod">
                  <ListboxButton
                    class="relative w-40 cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm"
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
                        v-for="(search, periodIndex) in term"
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
  <!-- 日付範囲選択　ここまで -->
</template>
  
  <script setup>
  import { ref } from 'vue'
  import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
  } from '@headlessui/vue'
  import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
  
  const term = [
    { date: '30日間' },
    { date: '7日間' },
    { date: '15日間' },
    { date: '90日間' },
    { date: '120日間' },
  ]
  const selectedPeriod = ref(term[0])
  </script>
  