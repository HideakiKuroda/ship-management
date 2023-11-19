<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { ref,onMounted,reactive, computed,onUnmounted } from 'vue';
//アコーディオン機能のインポート
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
//アコーディオン機能のCSS
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import FlashMessage from '@/Components/FlashMessage.vue';
import axios from 'axios';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import UserSerch from '@/Components/UserSerch.vue';
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
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { nl2br } from '@/nl2br';

// CSRFトークンを取得
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// AxiosのデフォルトヘッダにCSRFトークンをセット
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};

const newMessage = ref('');

const assignMassage = () => {
  if (newMessage.value.trim() === '') return;
  form.assignedMassagesList.push({
    project_id:props.project.id,
    created_at: new Date().toISOString(),
    users: { id: form.loginUser.id, name:form.loginUser.name }, // ここは現在ログインしているユーザーの名前を想定しています。
    // user_id:form.loginUser.id,
    // name:form.loginUser.name,
    memo: newMessage.value.trim(),
  });
  newMessage.value = ''; 
};

const unassignMassage = (id, userId) => {
    if (userId !== form.loginUser.id) {
        // ユーザーIDがログインユーザーのIDと一致しない場合は削除を許可しない
        alert('本人以外は削除できる権限がありません。');
        return;
    }
    
    // ユーザーIDがログインユーザーのIDと一致する場合は削除を実行
    form.assignedMassagesList = form.assignedMassagesList.filter(message => message.id !== id);
    form.deletedMessageIds.push(id); // 削除されたメッセージのIDを保存
};


const props = defineProps({
  project : Object,
  users : Array,
  ships : Array,
  categories : Array,
  loginUser :  Object,
  errors: Object,
})

const form = reactive({
  id:              props.project.id,
  ship_id:         props.project.ships.id,
  assignedUsersList: [...props.project.users],
  assignedMassagesList: [...props.project.pro_descriptions || []],
  name:            props.project.name,
  pro_category_id: props.project.pro_categories.id,
  start_date:      props.project.start_date,
  end_date:        props.project.end_date,
  completion:      props.project.completion,
  date_of_issue:   props.project.date_of_issue,
  tasks:           [...props.project.tasks],
  attachments:     [...props.project.pro_attachments],
  currentUser:     null,
  loginUser:       props.loginUser,
  deletedMessageIds: [],
  })


const deleteItem = id => {
    Inertia.delete(route('projects.destroy',{ project:id }),{
        onBefore: () => confirm('本当に削除しますか？')
    })
}

const formatDate = (date) => {
   if (!date) return "";
  return moment(date).format('YYYY年MM月DD日');
};

const updateProject = id => {
   
  form.pro_category_id =  selectedCategory.value.id
  freeListener();
  Inertia.put(route('projects.update',{ project:id }), form,{ 
        onBefore: () => confirm('変更を更新します。OKでしょうか？')
    })
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


///ファイル添付のスクリプト
const fileInput1 = ref(null);
const fileInput2 = ref(null);
const uploading = ref(false);
const uploadComplete = ref(false);
const uploadPercentage = ref(0);

const handleFileChange = (inputName) => {
  let files;
  
  if (inputName === 'file_upload1') {
    files = fileInput1.value.files;
  } else if (inputName === 'file_upload2') {
    files = fileInput2.value.files;
  }  
  // ファイルが選択されていなければ何もしない
  if (!files || files.length === 0) {
    return;
  }

  // 確認ダイアログを表示
  if (window.confirm('ファイルをアップロードしますか？')) {
    const formData = new FormData();
    
    for (let i = 0; i < files.length; i++) {
      formData.append('files[]', files[i]);
    }

    // ここでInertia.jsを使ってアップロード
    axios.post(route('project.upload', { id: form.id }), formData)
    .then(response => {
      if (response.data.status === 'success') {
        alert(response.data.message);
        // 必要に応じてリアクティブなデータの更新や、新しいファイルの表示を行う
      // 受け取った新しいファイルの情報をform.attachments配列に追加
      form.attachments.push(...response.data.uploadedFiles);
      // console.log('attach:',props.project.users);
      } else {
        alert(response.data.message);
      }
    })
    .catch(error => {
      alert('何らかのエラーが発生しました。');
    })
    .finally(() => {
      uploading.value = false;
    });
  }
};

const dropFiles = (event) => {
  uploading.value = true;
  uploadComplete.value = false;

  if (window.confirm('ファイルをアップロードしますか？')) {
    const formData = new FormData();
    const droppedFiles = event.dataTransfer.files;
    Array.from(droppedFiles).forEach((file) => formData.append('files[]', file));

    axios.post(route('project.upload', { id: form.id }), formData)
    .then(response => {
      if (response.data.status === 'success') {
        alert(response.data.message);
        // 必要に応じてリアクティブなデータの更新や、新しいファイルの表示を行う
      // 受け取った新しいファイルの情報をform.attachments配列に追加
      form.attachments.push(...response.data.uploadedFiles);
      console.log('attach:',props.project.users);
      } else {
        alert(response.data.message);
      }
    })
    .catch(error => {
      alert('何らかのエラーが発生しました。');
    })
    .finally(() => {
      uploading.value = false;
    });
  }
};
const isPdf = (filename) => {
  const fileExtension = filename.split('.').pop().toLowerCase();
  return fileExtension === 'pdf';
};


const deleteFile = (attachmentId) => {
  if (window.confirm('ファイルを削除しますか')) {
    axios.delete(route('project.deleteFile', { id: form.id }), { data: { attachmentId: attachmentId } })
    .then(response => {
      if (response.data.status === 'success') {
        // 通知の表示や、必要に応じてリアクティブなデータの更新を行う
        alert(response.data.message);
        // attachments 配列から削除したファイルを削除
        const index = form.attachments.findIndex(attachment => attachment.id === attachmentId);
        if (index !== -1) {
          form.attachments.splice(index, 1);
        }        
      } else {
        alert(response.data.message);
      }
    })
    .catch(error => {
      alert('何らかのエラーが発生しました。');
    });
}};

//カテゴリー検索ComboboxのinputBoxでカテゴリー検索
let query = ref('')
const categorie = props.categories
const curntCidx = computed(() => {
  return props.categories.findIndex(cr => cr?.id === form.pro_category_id)
})
let selectedCategory = ref({id:categorie[curntCidx.value].id,name:categorie[curntCidx.value].name} || {id: null, name: ''})

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

const downloadFile = async (attachmentId,dp) => {
    try {
      // まずファイル名を取得
      const filenameResponse = await axios.get(route('project.getFileName', { id: props.project.id }), {
            params: { attachmentId: attachmentId }
        });

        const filename = filenameResponse.data.filename; // ファイル名を取得
        console.log('Filename:', filename);
        const fileExtension = filename.split('.').pop().toLowerCase(); // ファイルの拡張子を取得
        console.log('File Extension:', fileExtension);

        // ファイルをダウンロード
        const response = await axios.get(route('project.downloadFile', { id: props.project.id }), {
            params: { attachmentId: attachmentId },
            responseType: 'blob'
        });

        if (dp === 'p') {
            // PDFの場合、新しいタブでプレビューを開く
            const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
            window.open(url, '_blank');
        } else {
            // それ以外の場合、ファイルをダウンロード
            const url = window.URL.createObjectURL(new Blob([response.data]));
            // console.log('filename:',filename);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', filename); // ファイルの拡張子は適切に設定してください
            document.body.appendChild(link);
            link.click();
            link.remove();
            }
    } catch (error) {
        console.error("エラーが発生しました：", error);
        // エラーメッセージの表示などのエラーハンドリング
    }
};

///変更を保存せずに移動するときなどにアラートを出す
const originalData = {};
const isChanged = computed(() => {
  return JSON.stringify(originalData) !== JSON.stringify(form);
});

const confirmSave = (event) => {
  if (isChanged.value) {
    event.returnValue = "編集中のものは保存されませんが、よろしいですか？";
    event.preventDefault();
  }
};

let moveConfirm;
const freeListener = () => {
  window.removeEventListener("beforeunload", confirmSave);
  if (moveConfirm) {
    moveConfirm();
  }
}

onUnmounted(() => {
  freeListener();
});

onMounted(() => {
  originalData.value = JSON.stringify(form);
  window.addEventListener("beforeunload", confirmSave);
  moveConfirm = Inertia.on('before', (event) => {
      return confirm("編集中のものがある場合、保存されませんがよろしいですか？");
  });
});


</script>

<template>
    <Head title="プロジェクトの詳細（編集）" />
     <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">プロジェクトの詳細（編集）</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                     <section class="text-gray-600 body-font relative">
                    
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <FlashMessage  />
                             <div class="m-2">
                                
                                <div class="p-2">
                                    <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <span>◆</span><span class="pl-5">Project No.: {{ props.project.id }}</span><span class="pl-5" v-if="props.project.ships.id!==null">Ship:【 {{ props.project.ships.name }} 】</span><br>
                                        <label>Subject:</label> <input type="text" id="name" name="name" v-model="form.name" class="pl-2 w-full rounded" >
                                    </div>
                                </div>

                            </div></div></div>
  
                        
                        <div class="container px-5 py-0 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">

                              <div>
                               <vue-collapsible-panel-group>
                               <vue-collapsible-panel class="z-10">
                                <template #title class="w-full rounded  border border-indigo-300 px-1"> 基本情報 </template>
                                <template #content> 
                                 
                                  <div class="flex flex-wrap sm:flex-row">
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
                                    <div class="p-1 ml-4">
                                      <label for="start_date" class="rounded  border border-indigo-300 mx-0 h-5 leading-7 text-sm text-gray-600">◎開始日（予定）：</label>
                                      <div id="start_date" class="text-base outline-none text-gray-700 leading-8 transition-colors duration-200 ease-in-out">
                                      <input type="date" id="start_date" name="start_date" v-model="form.start_date" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div></div>
                                   
                                    <div class="p-1 ml-4">
                                      <label for="end_date" class="rounded  border border-indigo-300 mx-0 h-5 leading-7 text-sm text-gray-600">◎終了日（予定）：</label>
                                      <div id="end_date" class="text-base outline-none text-gray-700 leading-8 transition-colors duration-200 ease-in-out">
                                      <input type="date" id="end_date" name="end_date" v-model="form.end_date" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div></div>
                                    
                                    <div class="p-1 ml-4">
                                      <label for="completion" class="rounded  border border-indigo-300 mx-0 h-5 leading-7 text-sm text-gray-600">◎完了日：</label>
                                      <div id="completion" class="text-base outline-none text-gray-700 leading-8 transition-colors duration-200 ease-in-out">
                                      <input type="date" id="completion" name="completion" v-model="form.completion" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div></div>
                                    

                                    <div class="p-2 ml-4"  v-if="form.pro_category_id === 1">
                                      <label for="date_of_issue" class="rounded  border border-indigo-300 mx-0 h-5 leading-7 text-sm text-gray-600">◎証書発行日：</label>
                                      <div id="date_of_issue" class="text-base outline-none text-gray-700 leading-8 transition-colors duration-200 ease-in-out">
                                      <input type="date" id="date_of_issue" name="date_of_issue" v-model="form.date_of_issue" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                    </div>
                                 </div>
                
                                </template>
                              </vue-collapsible-panel>
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

                              <vue-collapsible-panel :expanded="true" class="z-0">
                              <template #title > タスク一覧 </template>
                              <template #content> 
                                <div class="flex flex-col">
                                  <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                      <div class="overflow-hidden">
                                        <table class="min-w-full text-left text-sm font-light">
                                          <thead>
                                            <tr>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タスク名</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                              <div class="flex flex-col md:flex-row justify-between md:pr-16">
                                              <div>開始日</div>
                                              <div>期限</div>
                                              <div>完了</div>
                                              </div>  
                                              </th>
                                             </tr>
                                            </thead>
                                            <tbody>
                                            <tr  v-for="task in props.project.tasks" :key="task.id" >
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.show', { task:task.id })"> {{ task.id }} </Link></td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.show', { task:task.id })">{{ task.name }} </Link></td>
                                             <div class="flex flex-col md:flex-row justify-between">
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.start_date) }}</td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.deadline) }}</td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.completion)  }}</td>
                                            </div>
                                            </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <div class="flex justify-end">
                              <Link as="button" :href="route('tasks.create', { project_id:form.id })" class="ml-32 mt-6 h-10 text-white bg-indigo-500 border-0 py-2 px-2 focus:outline-none hover:bg-indigo-600 rounded">新規タスク作成</Link>
                              </div>
                              </template>
                              </vue-collapsible-panel>
                            <vue-collapsible-panel :expanded="true">
                           <template #title> メモ一覧 </template>
                            <template #content>
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden rounded border-2 p-2 mb-3 border-gray-200">
                                    <div v-for="message in form.assignedMassagesList" :key="message.id" class="message">
                                      <div class="flex justify-between items-center mt-1">
                                        <div class="text-xs">{{ formatDate(message.created_at) }}</div>
                                        <div class="flex justify-end">
                                          <button class="mx-4 px-1.5 py-0 text-xs bg-red-300  text-white font-semibold rounded-full hover:bg-red-400" @click="unassignMassage(message.id,message.users.id)">削除</button>
                                        </div>
                                      </div>
                                       <div class="text-xs border-b border-gray-200">{{ message.users?.name }}</div>
                                      <div  v-html="nl2br(message.memo)" class="border-b border-gray-400" ></div>
                                    </div>
                                  </div>
                                      <div class="flex flex-col">
                                        <textarea class="rounded border-b-2 border-gray-200 p-1 w-full h-32 text-justify" v-model="newMessage" placeholder="メモを入力" ></textarea>
                                        <div class="flex justify-end">
                                        <button class="mr-4 mt-2 h-8 w-20 px-0 py-0 text-xs bg-blue-400  text-white font-semibold rounded hover:bg-blue-500 " @click="assignMassage">追加</button>
                                      </div>
                                    </div>
                                    </div>
                                  
                                </div>
                              </div>
                            </template>
                            </vue-collapsible-panel>
                   
                            <vue-collapsible-panel :expanded="true">
                              <template #title> 書類添付 </template>
                            <template #content> 
                              <div  class="content">
                                <label class="visible md:invisible text-blue-600 underline">
                                  ファイル選択
                                  <input class="hidden" type="file" name="file_upload1" multiple ref="fileInput1"  @change="handleFileChange('file_upload1')"  />
                                </label>
                                <div v-if="uploading">
                                  アップロード中... {{ uploadPercentage }}%
                                </div>
                                <div v-if="uploadComplete">
                                  アップロードが完了しました。
                                </div>                       
                              <div>
                                <div class="max-w-xl">
                                  <label @dragover.prevent @drop.prevent="dropFiles" 
                                      class=" invisible md:visible flex justify-center w-full h-0 md:h-32  px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none">
                                      <span class="flex items-center space-x-2">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                                              stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                          </svg>
                                          <span class="font-medium text-gray-600">
                                            ドラッグ＆ドロップでファイルをアップロード or 
                                              <span class="text-blue-600 underline">ファイル選択</span>
                                          </span>
                                      </span>
                                      <input type="file" name="file_upload2" class="hidden" multiple ref="fileInput2"  @change="handleFileChange('file_upload2')">
                                  </label>
                              </div>
                                <!-- Progress & Message -->
                                <div v-if="uploading" class="md:block">
                                  アップロード中... {{ uploadPercentage }}%
                                </div>
                                <div v-if="uploadComplete" class="md:block">
                                  アップロードが完了しました。
                                </div>
                              </div>
                            </div>
                                                    
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                      <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b  font-medium dark:border-neutral-500">
                                          <tr>
                                            <th scope="col" class="whitespace-nowrap px-16 py-4 ">ファイル内容</th>
                                            <th scope="col" class="whitespace-nowrap px-6 py-4 "></th>
                                            <th scope="col" class="whitespace-nowrap px-4 py-4 ">ファイル名</th>
                                            <th scope="col" class="whitespace-nowrap px-12 py-4 ">登録日<br>登録者</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr v-for="attachment in form.attachments" :key="attachment.id" class="border-b dark:border-neutral-500">
                                            <td><input type="text"  v-model="attachment.title" class="ml-3 w-30 rounded"></td>
                                            <td class="flex justify-center flex-col"><img :src="attachment.icon" @click="downloadFile(attachment.id,'d')" alt="xls?" width="30" height="30" class="ml-3 mt-3 px-0">
                                            <button v-if="isPdf(attachment.originname)" class="mt-2 px-0 py-0 w-14 text-xs bg-blue-300  text-white font-semibold rounded-full hover:bg-indigo-400" @click="downloadFile(attachment.id,'p')">⊕view</button>
                                            </td>
                                            <td class="ml-3 w-1/3 rounded ">{{ attachment.originname }}</td>
                                            <td class="ml-3 w-1/5 rounded text-center">{{ formatDate(attachment.created_at) }}<br>{{ attachment.users.name }}</td>
                                            <td><button  class="w-10 h-6 text-xs bg-red-300  text-white font-semibold rounded hover:bg-red-400"  @click="deleteFile(attachment.id)">削除</button></td>
                                       </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </template>  
                            </vue-collapsible-panel>  

                            </vue-collapsible-panel-group>
                        </div>                        
                        </div>
                      </div>
                    </div>
                        <div class="container px-5 py-2 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                <div class="p-0 w-full">
                                  <button @click="updateProject(form.id)" class="flex mx-auto text-white bg-indigo-500 border-0 mb-10 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>  
                                </div>
                                <div class="p-0 w-full">
                                <!-- <button @click="deleteItem(project.id)" class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">削除する</button> -->
                                </div>
                            </div></div></div>

                        </section>                
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


