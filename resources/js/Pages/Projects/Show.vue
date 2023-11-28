<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link,router } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { ref,onMounted } from 'vue';
//アコーディオン機能のインポート
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
//アコーディオン機能のCSS
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import FlashMessage from '@/Components/FlashMessage.vue';
import axios from 'axios';
import { nl2br } from '@/nl2br';
// CSRFトークンをメタタグから取得
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// Axiosの設定
axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};


const props = defineProps({
  project : Object,
  loginUser :  Object,
})

const deleteItem = id => {
  router.delete(route('projects.destroy',{ project:id }),{
        onBefore: () => confirm('本当に削除しますか？')
    })
}

const formatDate = (date) => {
   if (!date) return "";
  return moment(date).format('YYYY年MM月DD日');
};

const isPdf = (filename) => {
  const fileExtension = filename.split('.').pop().toLowerCase();
  return fileExtension === 'pdf';
};

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

const editOpen = () => {
  if (props.project.users.some(user => user.id === props.loginUser.id)) {
    router.get(route('projects.edit', { project:props.project.id }));
  } else {
    alert('編集は担当者のみ可能です');
  }  
}

const editTask = (id) => {
  if (props.project.users.some(user => user.id === props.loginUser.id)) {
    console.log('Task_id:', id);
    router.get(route('tasks.edit', { task:id }));
  } else {
    alert('編集は担当者のみ可能です');
  }  
}


onMounted(() =>{
   console.log('これ:',props.project.users);
   console.log('は:',props.loginUser);
})

</script>

<template>
    <Head title="プロジェクトの詳細(参照)" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">プロジェクトの詳細☆</h2>
            <Link :href="route('projects.index')" class="text-blue-600 text-sm italic underline underline-offset-1" >・・一覧に戻る</Link>
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
     
                                  <div class="flex flex-wrap sm:flex-row">
                                    <div class="p-2 ml-4">
                                      <label for="name" class="rounded  border border-indigo-300 px-1 leading-7 text-sm text-gray-600">●区分：</label>
                                      <div id="name" class="w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            {{ props.project.pro_categories.name }}
                                      </div>
                                    </div>
                                    
                                    <div class="p-2 ml-4">
                                      <label for="name" class="rounded  border border-indigo-300 px-1 leading-7 text-sm text-gray-600">◎担当：</label>
                                      <div id="name" class="w-48 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                       <div v-for="user in props.project.users" >
                                        <div >{{ user.name }}</div>
                                       </div>
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
                                                  <Link class="text-blue-600" :href="route('tasks.edit', { task:task.id })"> {{ task.id }} </Link></td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.edit', { task:task.id })">{{ task.name }} </Link></td>
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
                              </template>
                              </vue-collapsible-panel>
                            <vue-collapsible-panel :expanded="true">
                              <template #title> メモ一覧 </template> 
                              <!-- props.project.pro_descriptions -->
                              <template #content>
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden rounded border-2 p-2 mb-3 border-gray-200">
                                    <div v-for="message in props.project.pro_descriptions" :key="message.id" class="message">
                                      <div class="flex justify-between items-center mt-1">
                                        <div class="text-xs">{{ formatDate(message.created_at) }}</div>
                                      </div>
                                       <div class="text-xs border-b border-gray-200">{{ message.users?.name }}</div>
                                      <div  v-html="nl2br(message.memo)" class="border-b border-gray-400" ></div>
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
                               <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                      <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                          <tr>
                                            <!-- 「scope=”col”」の「col」は「column」の略で縦列のことです。 scope=”col” を指定したth要素が、その縦方向（下方向）のセルの見出し -->
                                            <th scope="col" class="whitespace-nowrap px-16 py-4">ファイル内容</th>
                                            <th scope="col" class="whitespace-nowrap px-6 py-4"></th>
                                            <th scope="col" class="whitespace-nowrap px-4 py-4">ファイル名</th>
                                            <th scope="col" class="whitespace-nowrap px-12 py-4">登録日<br>登録者</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr v-for="attachment in  props.project.pro_attachments" :key="attachment.id" class="border-b dark:border-neutral-500">
                                            <td><a class="ml-3 w-30 rounded">{{ attachment.title }}</a></td>
                                            <td class="flex justify-center flex-col"><img :src="attachment.icon" @click="downloadFile(attachment.id,'d')" alt="xls?" width="30" height="30" class="ml-3 mt-3 px-0">
                                            <button v-if="isPdf(attachment.originname)" class="mt-2 px-0 py-0 w-14 text-xs bg-blue-300  text-white font-semibold rounded-full hover:bg-indigo-400" @click="downloadFile(attachment.id,'p')">⊕view</button>
                                            </td>
                                            <td class="ml-3 w-1/3 rounded ">{{ attachment.originname }}</td>
                                            <td class="ml-3 w-1/5 rounded text-center">{{ formatDate(attachment.created_at) }}<br>{{ attachment.users.name }}</td>
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
                                  <button  @click="editOpen" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集する</button>
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
