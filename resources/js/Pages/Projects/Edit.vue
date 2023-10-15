<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { ref,onMounted,reactive } from 'vue';
//アコーディオン機能のインポート
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
//アコーディオン機能のCSS
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import FlashMessage from '@/Components/FlashMessage.vue';
import axios from 'axios';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};


const props = defineProps({
  project : Object,
  users : Array,
  ships : Array,
  errors: Object,
})

const form = reactive({
  id:              props.project.id,
  ship_id:         props.project.ships.id,
  assignedUsersList: [...props.project.users],
  name:            props.project.name,
  pro_category_id: props.project.pro_categories.id,
  start_date:      props.project.start_date,
  end_date:        props.project.end_date,
  completion:      props.project.completion,
  date_of_issue:   props.project.date_of_issue,
  tasks:           [...props.project.tasks],
  attachments:     [...props.project.pro_attachments],
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
  Inertia.put(route('projects.update',{ project:id }), form,{ 
        onBefore: () => confirm('変更を更新します。OKでしょうか？')
    })
  }

const userIds = form.assignedUsersList.map(user => user.id);

const assignUser = () => {
    const selectedUserData = props.users.find(user => user.id === form.selectedUser);
    if (selectedUserData) {
        form.assignedUsersList.push(selectedUserData);
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
    Inertia.post(route('project.upload', { id: form.id }), formData, {
      // オプション：アップロードの進行状況が必要な場合はこちらを使用
      onUploadProgress: (progressEvent) => {
        uploadPercentage.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
      }
    });

    // アップロード完了のフラグをセット（Inertia.jsのPromiseが解決したら設定）
    uploadComplete.value = true;
  } else {
    // ユーザーがキャンセルした場合の処理（オプション）
  }
};

const dropFiles = (event) => {
  uploading.value = true;
  uploadComplete.value = false;

  if (window.confirm('ファイルをアップロードしますか？')) {
    const formData = new FormData();

  const droppedFiles = event.dataTransfer.files;
  Array.from(droppedFiles).forEach((file) => formData.append('files[]', file));

  Inertia.post(route('project.upload', { id: form.id }), formData, {
    onBefore: () => {
      // 何か処理
    },
    onSuccess: () => {
      uploadComplete.value = true;
    },
    onError: () => {
      // エラーハンドリング
    },
    onFinish: () => {
      uploading.value = false;
    },
  });
} else {
    // ユーザーがキャンセルした場合の処理（オプション）
}};


const downloadFile = async (attachmentId) => {
    try {
      // まずファイル名を取得
      const filenameResponse = await axios.get(route('project.getFileName', { id: props.project.id }), {
            params: { attachmentId: attachmentId }
        });

        const filename = filenameResponse.data.filename; // ファイル名を取得

        // ファイルをダウンロード
        const response = await axios.get(route('project.downloadFile', { id: props.project.id }), {
            params: { attachmentId: attachmentId },
            responseType: 'blob'
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        // console.log('filename:',filename);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', filename); // ファイルの拡張子は適切に設定してください
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error("エラーが発生しました：", error);
        // エラーメッセージの表示などのエラーハンドリング
    }
};


const deleteFile = (attachmentId) => {
  if (window.confirm('ファイルを削除しますか')) {
    Inertia.delete(route('project.deleteFile', { id: form.id }), { data: { attachmentId: attachmentId } });
}};

onMounted(() =>{
  // console.log('id:',props.project.users);
})

</script>

<template>
    <Head title="プロジェクトの詳細" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">プロジェクトの詳細</h2>
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
                                        <span class="pl-8">Subject: {{ props.project.name }}</span>
                                    </div>
                                </div>

                            </div></div></div>
  
                        
                        <div class="container px-5 py-0 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">

                              <div>
                               <vue-collapsible-panel-group>
                               <vue-collapsible-panel>
                                <template #title class="w-full rounded  border border-indigo-300 px-1"> 基本情報 </template>
                                <template #content> 
                                  <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆　担当者
                                        <div class="flex flex-wrap sm:flex-row sm:space-x-0">
                                          <div class="h-20 w-40 overflow-auto">
                                            <div>
                                                <ul>
                                                    <li v-for="user in form.assignedUsersList" :key="user.id">
                                                        {{ user.name }}
                                                        <button class="mx-4 px-1.5 py-0 text-xs bg-red-300  text-white font-semibold rounded-full hover:bg-red-400" @click="unassignUser(user.id)">削除</button>
                                                    </li>
                                                </ul>
                                           </div>
                                          </div>
                                          <div class="flex flex-wrap sm:flex-row h-20 w-80">
                                            <button class="mr-4 h-8 w-14 px-1.5 py-0 text-xs bg-blue-400  text-white font-semibold rounded hover:bg-blue-500" @click="assignUser">⇐追加</button>
                                            <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.selectedUser">
                                              <option v-for="user in props.users" :value="user.id" :key="user.id">
                                                  {{ user.name }}
                                              </option>
                                            </select>
                                        </div>
                                    </div> 
                                  </div> 

                                  <div class="flex flex-wrap sm:flex-row">
                                    <div class="p-2 ml-4">
                                      <label for="name" class="rounded  border border-indigo-300 px-1 leading-7 text-sm text-gray-600">●区分：</label>
                                      <div id="name" class="w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            {{ props.project.pro_categories.name }}
                                      </div>
                                    </div>
                                    
                                    <div class="p-2 ml-4">
                                      <label for="name" class="rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎開始日（予定）：</label>
                                      <div id="name" class="w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            {{ formatDate(props.project.start_date) }}
                                      </div>
                                    </div>
                                    <div class="p-2 ml-4">
                                      <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎終了日（予定）：</label>
                                      <div id="name" class="w-48   text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            {{ formatDate(props.project.end_date)}}
                                      </div>
                                    </div>
                                  
                                    <div class="p-2 ml-4">
                                      <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎完了日：</label>
                                      <div id="name" class="w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            {{ formatDate(props.project.completion) }}
                                      </div>
                                    </div>
                                    <div class="p-2 ml-4"  v-if="props.project.pro_category_id === 1">
                                      <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎証書発行日 :</label>
                                      <div id="name" class="w-48   text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                          {{ formatDate(props.project.date_of_issue) }}
                                      </div>
                                    </div>
                                
                                 </div>
                                </template>
                              </vue-collapsible-panel>

                              <vue-collapsible-panel :expanded="true">
                              <template #title> タスク一覧 </template>
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
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">期限</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">登録者</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">完了日</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr  v-for="task in props.project.tasks" :key="tasks.id" >
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.show', { task:task.id })"> {{ task.id }} </Link></td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.show', { task:task.id })">{{ task.name }} </Link></td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.start_date) }}</td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.deadline) }}</td>
                                              
                                              <!-- 担当者（ユーザー）列 -->
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                              <div v-for="user in task.users">
                                                  {{ user.name }}
                                              </div>
                                              </td>
                                              <!-- <td v-for="user in project.users"
                                              class="border-b-2 border-gray-200 px-4 py-3">{{ user.name  }}</td> -->
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.completion)  }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </template>
                              </vue-collapsible-panel>
                            <vue-collapsible-panel :expanded="true">
                              <template #title> メモ一覧 </template>
                            <template #content> 
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                      <table class="min-w-full text-left text-sm font-light">
                                        <thead>
                                          <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メモ</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">日付</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">登録者</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <tr  v-for="description in props.project.pro_descriptions" :key="pro_descriptions.id" >
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(description.created_at) }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ description.users.name }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ description.memo }}</td>
                                          </tr>
                                          </tbody>
                                      </table>
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
                                            <td><img :src="attachment.icon" @click="downloadFile(attachment.id)" alt="xls?" width="30" height="30" ></td>
                                            <td class="w-1/3 rounded ">{{ attachment.originname }}</td>
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
