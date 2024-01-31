<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link,router } from '@inertiajs/vue3';
import moment from 'moment';
import { ref,onMounted,reactive ,computed,onUnmounted,watch } from 'vue';
//アコーディオン機能のインポート
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
//アコーディオン機能のCSS
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import FlashMessage from '@/Components/FlashMessage.vue';
import axios from 'axios';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { nl2br } from '@/nl2br';
import { initFlowbite } from 'flowbite'  //優先度設定に使用
import { VSwatches } from 'vue3-swatches'
import 'vue3-swatches/dist/style.css'

// CSRFトークンを取得
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// AxiosのデフォルトヘッダにCSRFトークンをセット
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};

const props = defineProps({
    task  : Object,
    loginUser :  Object,
    project :  Object,
    errors: Object,
});

const form = reactive({         //内容をreactiveにform変数に収める
    id:                 props.task.id,
    proName:            props.project.name,
    projectId:          props.task.projects.id,
    assignedUsersList:  [...props.project.users],
    ship:               props.project.ships,
    assignedMassagesList: [...props.task.task_descriptions || []],
    subtasks:           [...props.task.subtasks],
    attachments:        [...props.task.task_attachments],
    loginUser:          props.loginUser,
    deletedMessageIds:  [],
    name:               props.task.name,
    end_date:           props.task.end_date,
    deadline:           props.task.deadline,
    priorityName:       null,
    priorityColor:      null,
    priority:           props.task.priority,
    color:              props.task.color_id,
});

const cssCol = ref('')
const color_id =  ref('')

const colToCss = (col) => {
if(col ==''){cssCol.value = ''}
else if(col =='#1FBC9C'){cssCol.value = 'bg-emerald-300'}
else if(col =='#1CA085'){cssCol.value = 'bg-emerald-500 text-slate-100'}
else if(col =='#2ECC70'){cssCol.value = 'bg-green-300'}
else if(col =='#27AF60'){cssCol.value = 'bg-green-500 text-slate-100'}

else if(col =='#3398DB'){cssCol.value = 'bg-sky-400'}
else if(col =='#2980B9'){cssCol.value = 'bg-sky-500 text-slate-100'}
else if(col =='#A463BF'){cssCol.value = 'bg-purple-500 text-slate-100'}
else if(col =='#8E43AD'){cssCol.value = 'bg-purple-600 text-slate-100'}

else if(col =='#3D556E'){cssCol.value = 'bg-cyan-800 text-slate-100'}
else if(col =='#222F3D'){cssCol.value = 'bg-cyan-900 text-slate-100'}
else if(col =='#F2C511'){cssCol.value = 'bg-yellow-300'}
else if(col =='#F39C19'){cssCol.value = 'bg-amber-400'}

else if(col =='#E84B3C'){cssCol.value = 'bg-red-400'}
else if(col =='#C0382B'){cssCol.value = 'bg-red-600 text-slate-100'}
else if(col =='#DDE6E8'){cssCol.value = 'bg-slate-100'}
else if(col =='#BDC3C8'){cssCol.value = 'bg-slate-200'};
}

watch(color_id,(newColor) =>{
  colToCss(newColor)
})

const formatDate = (date) => {
   if (!date) return "";
  return moment(date).format('YYYY年MM月DD日');
};

const handleChange = (event) => {
  form.priority = event.target.value;
  onMntHandleChange();
};

const onMntHandleChange = () => {
    if(form.priority == 1){
      form.priorityName = '優先度:① ※緊急度―大 ※重要度-大';
      form.priorityColor = 'bg-rose-100';
    }
    else if(form.priority == 2){
       form.priorityName = '優先度:② ※緊急度―大 ※重要度-小';
       form.priorityColor = 'bg-yellow-100'
      }
    else if(form.priority == 3){
       form.priorityName = '優先度:③ ※緊急度―小 ※重要度-大';
       form.priorityColor = 'bg-green-100'
      }
    else if(form.priority == 4){
       form.priorityName = '優先度:④ ※緊急度―小 ※重要度-小';
       form.priorityColor = 'bg-blue-100';
      }
    else if(form.priority == 5){
       form.priorityName = '優先度：指定しない';
       form.priorityColor = 'bg-gray-50';
      };
};

// onMounted(() => {
//     initFlowbite();  最下部に移動記載
// })

const updateTask = id =>{
  freeListener();
  form.color = color_id.value ;
  router.put(route('tasks.update',{ task:id }), form,{
        onBefore: () => confirm('変更を更新します。OKでしょうか？')
    })
}

const newMessage = ref('');

const assignMassage = () => {
  if (newMessage.value.trim() === '') return;
  form.assignedMassagesList.push({
    task_id:props.task.id,
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

//ファイル添付＆削除
///ファイル添付のスクリプト
const fileInput1 = ref(null);
const fileInput2 = ref(null);
const uploading = ref(false);
const uploadComplete = ref(false);
const uploadPercentage = ref(0);

const handleFileChange = (inputName) => {
  let files;
  // console.log('upload:','1');
  if (inputName === 'file_upload1') {
    files = fileInput1.value.files;
  } else if (inputName === 'file_upload2') {
    files = fileInput2.value.files;
  }
  // ファイルが選択されていなければ何もしない
  if (!files || files.length === 0) {
    return;
  }
  // console.log('upload:','2');
  // 確認ダイアログを表示
  if (window.confirm('ファイルをアップロードしますか？')) {
    const formData = new FormData();
    // console.log('upload:','3');
    for (let i = 0; i < files.length; i++) {
      formData.append('files[]', files[i]);
    }
    // console.log('upload:','4');
    // ここでInertia.jsを使ってアップロード
     axios.post(route('task.upload', { id: form.id }), formData)
    .then(response => {
      if (response.data.status === 'success') {
        alert(response.data.message);
        // 必要に応じてリアクティブなデータの更新や、新しいファイルの表示を行う
      // 受け取った新しいファイルの情報をform.attachments配列に追加
      form.attachments.push(...response.data.uploadedFiles);
      // console.log('attach:',props.task.users);
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

    axios.post(route('task.upload', { id: form.id }), formData)
    .then(response => {
      if (response.data.status === 'success') {
        alert(response.data.message);
        // 必要に応じてリアクティブなデータの更新や、新しいファイルの表示を行う
      // 受け取った新しいファイルの情報をform.attachments配列に追加
      form.attachments.push(...response.data.uploadedFiles);
      console.log('attach:',props.task.users);
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
    axios.delete(route('task.deleteFile', { id: form.id }), { data: { attachmentId: attachmentId } })
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
  color_id.value = form.color;
  colToCss(form.color);
  onMntHandleChange();
  initFlowbite();
  originalData.value = JSON.stringify(form);
  window.addEventListener("beforeunload", confirmSave);
  moveConfirm = router.on('before', (event) => {
      return confirm("編集中のものがある場合、保存されませんがよろしいですか？");
  });
});


</script>

<template>
    <Head title="タスクの詳細・編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスクの詳細・編集</h2>
            <Link v-if="props.task.parent_id == null" class="text-blue-600 text-sm italic underline underline-offset-1" :href="route('projects.edit', { project:form.projectId })">・・プロジェクトに戻る </Link>
            <Link v-else class="text-blue-600 text-sm italic underline underline-offset-1" :href="route('tasks.edit', { task:props.task.parent_id })">・・親のタスクに戻る </Link>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <BreezeValidationErrors :errors="errors" />
                     <section class="text-gray-600 body-font relative">
                        <div class="container px-5 pt-8 mx-auto">
                          <FlashMessage  />
                          <div class="md:flex lg:w-2/3 md:w-2/3 mx-auto">
                            <div  :class="{
                              'bg-blue-50': props.task.parent_id == null,
                              'bg-gray-100': props.task.parent_id != null
                              }"
                              id="name" class="flex flex-wrap flex-auto md:w-1/2 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="flex flex-nowrap md:pl-5">◆&nbsp;&nbsp;Project No.: {{ props.project.id }}</span>
                                <span class=" md:pl-5" v-if="props.project.ships.id!==null">Ship:【 {{ props.project.ships.name }} 】</span>
                                <span class=" md:pl-8">Subject: {{ props.project.name }}</span>
                              </div>
                              <div  :class="{
                                'bg-blue-50': props.task.parent_id == null,
                                'bg-gray-100': props.task.parent_id != null
                              }"
                            id="name" class="flex-auto md:w-1/2 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                  ◆&nbsp;&nbsp;担当者
                              <div class="flex flex-wrap sm:flex-row sm:space-x-0">
                                <div class=" overflow-auto">
                                  <div>
                                      <ul>
                                          <li v-for="user in form.assignedUsersList" :key="user.id">
                                              {{ user.name }}
                                          </li>
                                      </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="container px-5 py-0 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                                  <div class="flex flex-wrap sm:flex-col">
                                    <label v-if="props.task.parent_id == null" class="text-rose-600">Task No.{{ props.task.id }}&nbsp;&nbsp;作成日:{{formatDate(task.created_at) }}<br>(内容) :</label>
                                    <label v-else class="text-indigo-600">Sub Task No.&nbsp;{{ props.task.id }}&nbsp;&nbsp;作成日:{{formatDate(task.created_at) }}&nbsp;&nbsp;（親）Task No.{{ props.task.parent_id }}<br>(内容) :</label>
                                    <div class="flex flex-row p-2">
                                      <input type="text" id="name" name="name" v-model="form.name" :class="['pl-2 w-full rounded',cssCol ]" >
                                      <VSwatches v-model="color_id" />
                                    </div>
                                  <div class="flex flex-col sm:flex-row p-2 ml-4">
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="end" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎終了予定日：</label>
                                      <input type="date" id="end" name="end" v-model="form.end_date" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">
                                    </div>

                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="end" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎期限：</label>
                                      <input type="date" id="end" name="end" v-model="form.deadline" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">
                                    </div>
                                  </div>
                                  </div>
                                  <div :class="['font-medium rounded-lg text-sm px-5 py-2.5 mb-1', form.priorityColor]"
                                  >{{ form.priorityName }}</div>


                                  <button id="dropdownRadioHelperButton" data-dropdown-toggle="dropdownRadioHelper"
                                  :class="['border border-gray-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5  ml-6 text-center inline-flex items-center', form.priorityColor]"
                                  type="button"> 優先度を設定<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                  </svg>
                                  </button>

                                  <!-- Dropdown menu -->
                                  <div id="dropdownRadioHelper" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-60 dark:bg-gray-700 dark:divide-gray-600">
                                      <ul  class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioHelperButton">
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-rose-200 bg-rose-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-1" name="helper-radio" type="radio" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-1" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度①</div>
                                                  <p id="helper-radio-text-1" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度-大 ※重要度-大</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-yellow-200 bg-yellow-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-2" name="helper-radio" type="radio" value="2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-2" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度②</div>
                                                  <p id="helper-radio-text-2" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度-大 ※重要度-小</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-green-200 bg-green-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-3" name="helper-radio" type="radio" value="3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-3" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度③</div>
                                                  <p id="helper-radio-text-3" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度-小 ※重要度-大</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-blue-200 bg-blue-100">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-4" name="helper-radio" type="radio" value="4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-4" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>優先度④</div>
                                                  <p id="helper-radio-text-4" class="text-xs font-normal text-gray-500 dark:text-gray-300">※緊急度―小 ※重要度-小</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                        <li>
                                          <div class="flex p-2 rounded hover:bg-gray-100 bg-gray-50">
                                            <div class="flex items-center h-5">
                                                <input id="helper-radio-5" name="helper-radio" type="radio" value="5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300
                                                 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2
                                                 dark:bg-gray-600 dark:border-gray-500" @change="handleChange">
                                            </div>
                                            <div class="ms-2 text-sm">
                                                <label for="helper-radio-5" class="font-medium text-gray-900 dark:text-gray-300">
                                                  <div>その他</div>
                                                  <p id="helper-radio-text-5" class="text-xs font-normal text-gray-500 dark:text-gray-300">特に優先度を指定しない</p>
                                                </label>
                                            </div>
                                          </div>
                                        </li>
                                      </ul>
                                  </div>
                                   <!-- End Dropdown menu -->
                            </div>
                          </div>
                        </div>
                <div class="container px-5 py-0 mx-auto">
                  <div class="lg:w-2/3 md:w-2/3 mx-auto">
                    <div class="m-2">
                      <vue-collapsible-panel-group>
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
                                          <button class="mx-4 px-1.5 py-0 text-xs bg-red-300  text-white font-semibold rounded-full hover:bg-red-400" @click="unassignMassage(message.id,message.user_id)">削除</button>
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

                                <div class="content">
                                <div>
                                    <label @dragover.prevent @drop.prevent="dropFiles"
                                    class="flex flex-col justify-center items-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                        <span class="text-gray-600 mt-2">
                                            ドラッグ＆ドロップでファイルをアップロード
                                        </span>
                                        <label class="text-blue-600 underline cursor-pointer">ファイル選択
                                    <input type="file" name="file_upload2" class="hidden" multiple ref="fileInput2" @change="handleFileChange('file_upload2')">
                                    </label>
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
                            <div v-if="props.task.parent_id == null">
                            <vue-collapsible-panel :expanded="true" class="z-0">
                              <template #title > サブタスク一覧 </template>
                              <template #content>
                                <div class="flex flex-col overflow-hidden">
                                        <table class="min-w-full text-left text-sm font-light whitespace-no-wrap hidden sm:table">
                                          <thead>
                                            <tr>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">タスク名</th>
                                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                              <div class="flex flex-col md:flex-row justify-between md:pr-16">
                                              <div>作成日</div>
                                              <div>期限</div>
                                              <div>完了日</div>
                                              </div>
                                              </th>
                                             </tr>
                                            </thead>
                                            <tbody>
                                            <tr  v-for="task in props.task.subtasks" :key="task.id" >
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.edit', { task:task.id })"> {{ task.id }} </Link></td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                                  <Link class="text-blue-600" :href="route('tasks.edit', { task:task.id })">{{ task.name }} </Link></td>
                                             <div class="flex flex-col md:flex-row justify-between">
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.created_at) }}</td>
                                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.deadline) }}</td>
                                              <td v-if="task.completion !== null"
                                              class="border-b-2 border-gray-200 px-4 py-3">{{ formatDate(task.completion) }}</td>
                                              <td v-else
                                              class="border-b-2 border-gray-200 px-4 py-3">未完了</td>
                                            </div>
                                           </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                      <!-- スマホ用のリストここから  -->
                                      <div class="container ml-2 px-4">
                                      <div v-for="task in props.task.subtasks" :key="task.id" class="block sm:hidden">
                                       <div class="mb-4">
                                          <strong>ID:&emsp;&emsp; タスク名:</strong><br>
                                            <span>
                                              <Link class="text-blue-600" :href="route('tasks.edit', { task:task.id })">{{ task.id }}</Link>
                                                &emsp;&emsp;
                                              <Link class="text-blue-600" :href="route('tasks.edit', { task:task.id })">{{ task.name }} </Link>
                                            </span>
                                        </div>
                                        <div class="mb-4">
                                        <strong>作成:&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;期限:</strong><br>
                                        <span class="block">{{ formatDate(task.created_at) }}&emsp;&emsp;&emsp;
                                          {{ formatDate(task.deadline) }}</span>
                                        </div>
                                        <div class="mb-4 border-b-2 border-gray-200">
                                        <strong>完了:</strong><br>
                                        <span class="block">{{ formatDate(task.completion) }}</span>
                                        </div>


                                      </div>
                                      </div>
                              <div class="flex justify-end">
                              <Link as="button" :href="route('task.subCreate', { id:form.id })" class="ml-32 mt-6 h-10 text-white bg-indigo-500 border-0 py-2 px-2 focus:outline-none hover:bg-indigo-600 rounded">サブタスク作成</Link>
                              </div>
                              </template>
                              </vue-collapsible-panel>
                              </div>
                            </vue-collapsible-panel-group>
                        </div>
                      </div>
                    </div>
                        <div class="container px-5 py-2 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                <div class="p-0 w-full">
                                  <button  @click="updateTask(form.id)" class="flex mx-auto text-white bg-indigo-500 border-0 mb-10 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>
                                </div>
                                <div class="p-0 w-full">
                                </div>
                            </div>
                          </div>
                        </div>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
