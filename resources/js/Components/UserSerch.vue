<script setup>


/*

const props = defineProps({
    users: Array,    --> ドロップダウンリストに表示するユーザーリスト
});

const XXXX = reactive({
    users       :props.users,
    userId      :props.currentUser,
});

const handleUserId = (currentUser) =>{
  XXXX.userId = currentUser
  selectItem(XXXX.userId)    ==>> ユーザーを選んだ時のメソッド
}

 <UserSerch :userId="XXXX.userId" :users="props.users" @update:currentUser="handleUserId"/>

 */


import { ref, computed,watch } from 'vue';
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
    userId:  Number,
})

const emit = defineEmits([
  'update:currentUser',

])

//コンボボックス用の変数設定
const user = props.users

const curntUIdx = computed(() => {
  if(user){
  return user.findIndex(ur => ur?.id === props.userId)
  }
})
let selectedUser = ref({id: null, name: ''});
if (curntUIdx.value !== -1 && curntUIdx.value !== null) {
  selectedUser.value = {id: user[curntUIdx.value].id, name: user[curntUIdx.value].name};
}

const boxClear = () =>{
  selectedUser.value = ref({id: null, name: ''});
}

defineExpose({
  boxClear,
});

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
const options = computed(() => {
  return [{ id: null, name: '' }, ...filteredUser.value];
});

//担当者索Comboboxでリストから選んだ時の動作
watch(selectedUser, (newValue, oldValue) => {
  if (newValue && newValue !== oldValue) {
    emit('update:currentUser', newValue.id);
    // console.log('id:',newValue.id)
  }
})


</script>

<template>
<!-- 担当者検索コンボボックス　ここから -->
<div class="flex flex-col md:flex-row mt-0">
    <div class="flex flex-row ">
        <Combobox v-model="selectedUser" id="urSerch" name="urSerch" class=" opacity-100 z-10">
            <div  class="relative ml-4" > <ComboboxLabel class=" text-sm ">担当者選択:</ComboboxLabel>
                <div
                class="relative w-full cursor-default  rounded bg-white text-left border-gray-300 focus:ring-2 sm:text-sm"
                >
                <ComboboxInput
                    class="w-36 py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 rounded-lg bg-gray-100 focus:bg-white"
                    :displayValue="(person) => person.name"
                    @change="query = $event.target.value"


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
                    class="absolute mt-1 max-h-60 w-40 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                >
                    <div
                    v-if="filteredUser.length === 0 && query !== ''"
                    class="relative cursor-default select-none py-2 px-4 text-gray-700"
                    >
                    名前が見つかりません.
                    </div>

                    <ComboboxOption
                    v-for="person in options"
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
                        {{ person.name  || '・・・'  }}
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
