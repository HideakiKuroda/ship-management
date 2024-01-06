<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import moment from 'moment';
import { ref,onMounted,reactive, computed,onUnmounted  } from 'vue';
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import axios from 'axios';
import FlashMessage from '@/Components/FlashMessage.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
// CSRFトークンを取得
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// AxiosのデフォルトヘッダにCSRFトークンをセット
axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};

const props = defineProps({
    ship : Object,
    navigationAreas : Array,
    operatSections : Array,
    users :Array,
    errors: Object,
 })

const form = reactive({         //内容をreactiveにform変数に収める
    id:                 props.ship.id,
    selectedUser:       null,
    // assignedUsers:      props.ship.users.map(user => user.id),
    assignedUsersList: [...props.ship.users],
    name:               props.ship.name,
    yard:               props.ship.yard,
    ship_no:            props.ship.ship_no,
    ex_name:            props.ship.ex_name,
    former_name:        props.ship.former_name,
    slectedOperatSection : props.ship.operat_section_id,
    selectedNavigationArea : props.ship.navigation_area_id,
    delivered:          props.ship.delivered,
    gross_tonn:         props.ship.gross_tonn,
    issueInspCert:      props.ship.issueInspCert,
    expiry_date:        props.ship.expiry_date,
    
    official_number:    props.ship.summaries.official_number,
    signal_code:        props.ship.summaries.signal_code,
    international_ton:  props.ship.summary2s.international_ton,
    passenger_capacity: props.ship.summary2s.passenger_capacity,
    insurance_type:     props.ship.summary2s.insurance_type,
    // :機関仕様その他,
    engine_kw:          props.ship.summaries.engine_kw,
    me_model:           props.ship.summaries.me_model,
    me_sno:             props.ship.summaries.me_sno,
    pera_spec:          props.ship.summaries.pera_spec,
    pera_sno:           props.ship.summaries.pera_sno,
    aux_engine:         props.ship.summaries.aux_engine,
    // :船体寸法・喫水・設備等,
    full_length:        props.ship.summaries.full_length,
    lpp:                props.ship.summaries.lpp,
    breadth:            props.ship.summaries.breadth,
    beam_depth:         props.ship.summaries.beam_depth,
    mold_draft:         props.ship.summaries.mold_draft,
    draft_mark_F:       props.ship.summaries.draft_mark_F,
    draft_mark_A:       props.ship.summaries.draft_mark_A,
    fm_bl:              props.ship.summaries.fm_bl,
    draft_m:            props.ship.summaries.draft_m,
    layer_2or3:         props.ship.summaries.layer_2or3,
    winch_tension:      props.ship.summaries.winch_tension,
    stern_towboat:      props.ship.summaries.stern_towboat,
    dk_machine_pp:      props.ship.summaries.dk_machine_pp,
    exhaust:            props.ship.summaries.exhaust,
    intake:             props.ship.summaries.intake,
    harbor_gen:         props.ship.summaries.harbor_gen,
    fire_extinguish:    props.ship.summaries.fire_extinguish,
    // :試運転記録等,
    speed50:            props.ship.summary2s.speed50,
    speed100:           props.ship.summary2s.speed100,
    rpm_peller50:       props.ship.summary2s.rpm_peller50,
    rpm_peller100:      props.ship.summary2s.rpm_peller100,
    slip_rate50:        props.ship.summary2s.slip_rate50,
    slip_rate100:       props.ship.summary2s.slip_rate100,
    tug_force50:        props.ship.summary2s.tug_force50,
    tug_force100:       props.ship.summary2s.tug_force100,
    // :船舶関係者,
    operator:           props.ship.concerneds.operator,
    borrower:           props.ship.concerneds.borrower,
    manager:            props.ship.concerneds.manager,
    crew_arrange:       props.ship.concerneds.crew_arrange,
    owners:             [...props.ship.ship_owners],
    attachments:        [...props.ship.ship_attachments],

}) 


const deleteItem = id => {
  router.delete(route('ships.destroy', { ship: id }), {
  onBefore: () => {
    if (confirm('本当に削除しますか？')) {
      freeListener();
      return true;
    } else {
      return false;
    }}
})};

const formatDate = (date) => {
//   if (!date) return "N/A";
  return moment(date).format('YYYY年MM月DD日');
};
//
const updateShip = id => {
  router.put(route('ships.update',{ ship:id }), form,{ 
    onBefore: () => {
    if (confirm('変更を更新します！。OKでしょうか？')) {
      freeListener();
      return true;
    } else {
      return false;
    }}
    })
  }

  const upSchedule = id => {
    if (form.selectedNavigationArea !== null && form.expiry_date !== null ){
    router.put(route('ship.inspectionDates',{ ship:id }), form,{ 
  onBefore: () => {
    if (confirm('ドックスケジュールを更新します！')) {
      freeListener();
      return true;
    } else {
      return false;
    }}
    })
  } else {
    if (confirm('航行区域または定期検査期限の入力がありません')){}
  }
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

const addOwner = () => {
  form.owners.push({ owner_name: '', ratio: 0 })
}

const removeOwner = (index) => {
  form.owners.splice(index, 1)
}

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
    axios.post(route('ship.upload', { id: form.id }), formData)
    .then(response => {
      if (response.data.status === 'success') {
        alert(response.data.message);
        // 必要に応じてリアクティブなデータの更新や、新しいファイルの表示を行う
      // 受け取った新しいファイルの情報をform.attachments配列に追加
      form.attachments.push(...response.data.uploadedFiles);
      // console.log('attach:',props.ship.users);
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

    axios.post(route('ship.upload', { id: form.id }), formData)
    .then(response => {
      if (response.data.status === 'success') {
        alert(response.data.message);
        // 必要に応じてリアクティブなデータの更新や、新しいファイルの表示を行う
      // 受け取った新しいファイルの情報をform.attachments配列に追加
      form.attachments.push(...response.data.uploadedFiles);
      // console.log('attach:',props.ship.users);
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

const downloadFile = async (attachmentId,dp) => {
    try {
      // まずファイル名を取得
      const filenameResponse = await axios.get(route('ship.getFileName', { id: props.ship.id }), {
            params: { attachmentId: attachmentId }
        });

        const filename = filenameResponse.data.filename; // ファイル名を取得
        console.log('Filename:', filename);
        const fileExtension = filename.split('.').pop().toLowerCase(); // ファイルの拡張子を取得
        console.log('File Extension:', fileExtension);

        // ファイルをダウンロード
        const response = await axios.get(route('ship.downloadFile', { id: props.ship.id }), {
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

const onListener = () => {
  window.addEventListener("beforeunload", confirmSave);
  moveConfirm = router.on('before', (event) => {
      return confirm("編集中のものがある場合、保存されませんがよろしいですか？");
  });
}

onUnmounted(() => {
  freeListener();
});

onMounted(() => {
  originalData.value = JSON.stringify(form);
  onListener();
});


// const deleteFile = (attachmentId) => {
//   if (window.confirm('ファイルを削除しますか')) {
//     router.delete(route('ship.deleteFile', { id: form.id }), { data: { attachmentId: attachmentId } });
// }};

const deleteFile = (attachmentId) => {
  if (window.confirm('ファイルを削除しますか')) {
    axios.delete(route('ship.deleteFile', { id: form.id }), { data: { attachmentId: attachmentId } })
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

</script>
<template>
    <Head title="船舶の編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">船舶の詳細編集</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <BreezeValidationErrors :errors="errors" />    
                    <!-- <form @submit.prevent="updateShip(form.id)" >   -->
                     <section class="text-gray-600 body-font relative">
                    
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <FlashMessage  />
                            <div class="m-2">
                                
                                <div class="flex flex-col p-2 ml-4">
                                    <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      <label> ◆　船名・造船所・船番 </label>
                                          <div class="flex flex-wrap sm:flex-row sm:space-x-4">
                                          <input type="text" id="name" name="name" v-model="form.name" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 ml-4 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="text" id="yard" name="yard" v-model="form.yard" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="number" id="ship_no" name="ship_no" v-model="form.ship_no" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                           
                                        </div>
                                  </div>
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
                                              <option v-for="user in users" :value="user.id" :key="user.id">
                                                  {{ user.name }}
                                              </option>
                                            </select>
                                        </div>
                                    </div> 
                                  </div>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="container px-5 py-0 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                             
                             <vue-collapsible-panel-group>
                               <vue-collapsible-panel>
                                <template #title class="w-full rounded  border border-indigo-300 px-1"> 基本情報 </template>
                                <template #content> 
     
                                  <div class="flex flex-wrap sm:flex-row">
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="ship.ex_name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船名（前）：</label>
                                      <input type="text" id="ship.ex_name" name="ship.ex_name" v-model="form.ex_name" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>

                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="former_name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船名（元）：</label>
                                      <input type="text" id="former_name" name="former_name" v-model="form.former_name" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="delivered" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎竣工：</label>
                                      <input type="date" id="delivered" name="delivered" v-model="form.delivered" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                 
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="gross_tonn" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎総トン数：</label>
                                      <input type="tel" id="gross_tonn" name="gross_tonn" v-model="form.gross_tonn" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="section" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎運航地域：</label>
                                      <div id="section" class=" w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" >
                                      <!-- ユーザー選択ドロップダウン -->
                                      <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.slectedOperatSection">
                                        <option v-for="operatSection in operatSections" :key="operatSection.id" :value="operatSection.id">
                                          {{ operatSection.section }}
                                         </option>
                                      </select>
                                    </div>  
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎航行区域 :</label>
                                      <div id="name" class="w-48   text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      <!-- ユーザー選択ドロップダウン -->
                                      <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.selectedNavigationArea">
                                        <option v-for="navigationArea in navigationAreas" :key="navigationArea.id" :value="navigationArea.id">
                                          {{ navigationArea.name }} 
                                        </option>
                                      </select>    
                                      </div>
                                    </div>
                                
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="official_number" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船舶記号：</label>
                                      <input type="tel" id="official_number" name="official_number" v-model="form.official_number" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="signal_code" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎信号符号 :</label>
                                      <input type="tel" id="signal_code" name="signal_code" v-model="form.signal_code" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="international_ton" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎国際トン数：</label>
                                      <input type="tel" id="international_ton" name="international_ton" v-model="form.international_ton" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="passenger_capacity" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎旅客定員 :</label>
                                      <input type="text" id="passenger_capacity" name="passenger_capacity" v-model="form.passenger_capacity" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="insurance_type" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船舶保険（種類）：</label>
                                      <input type="tel" id="insurance_type" name="insurance_type" v-model="form.insurance_type" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div> 

                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="issueInspCert" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎定期検査完了日：</label>
                                      <input type="date" id="issueInspCert" name="issueInspCert" v-model="form.issueInspCert" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div> 

                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="expiry_date" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎定期検査期限：</label>
                                      <input type="date" id="expiry_date" name="expiry_date" v-model="form.expiry_date" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div> 

                                    <div class="flex flex-col p-2 ml-4">
                                     <button @click="upSchedule(props.ship.id)" class="flex mx-auto text-white bg-indigo-400 border-0 mt-5 py-2 px-2 focus:outline-none hover:bg-indigo-500 rounded ">ドックスケジュール</button>  
                                   </div>

                                   <div class="flex flex-col p-2 ml-4">
                                    <label v-if="props.ship.schedules?.Periodic_dline1 == null"
                                    class="text-justify text-sm font-medium text-red-500 underline mt-7">ドックスケジュール未入力</label>
                                     <label v-else-if="props.ship.expiry_date == props.ship.schedules?.Periodic_dline1"
                                    class="text-justify text-sm font-medium text-indigo-600 underline mt-7">ドックスケジュール更新済み</label>
                                    <label v-else-if="props.ship.expiry_date !== props.ship.schedules?.Periodic_dline1"
                                    class="text-justify text-sm font-medium text-red-400 underline mt-7">ドックスケジュール未更新</label>
                                   </div>

                                  </div>
                                </template>
                              </vue-collapsible-panel>
                            
                            <vue-collapsible-panel  :expanded="false">
                            <template #title> 機関仕様その他 </template>
                            <template #content> 
                              <div class="flex flex-wrap sm:flex-row">
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="engine_kw" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎馬力：</label>
                                    <input type="tel" id="engine_kw" name="engine_kw" v-model="form.engine_kw" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="me_model" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎主機型式 :</label>
                                    <input type="tel" id="me_model" name="me_model" v-model="form.me_model" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="me_sno" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎主機製造番号：</label>
                                    <input type="tel" id="me_sno" name="me_sno" v-model="form.me_sno" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="pera_spec" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎ペラ仕様 :</label>
                                    <input type="tel" id="pera_spec" name="pera_spec" v-model="form.pera_spec" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="pera_sno" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎ＺＰ製造番号：</label>
                                    <input type="tel" id="pera_sno" name="pera_sno" v-model="form.pera_sno" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="aux_engine" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎発電機型式 :</label>
                                    <input type="tel" id="aux_engine" name="aux_engine" v-model="form.aux_engine" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                </div>

                            </template>
                            </vue-collapsible-panel>

                            <vue-collapsible-panel  :expanded="false">
                            <template #title> 船体寸法・喫水・設備等 </template>
                            <template #content>
                              <div class="flex flex-wrap sm:flex-row">
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="full_length" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎全長 :</label>
                                    <input type="number"  step="0.1" id="full_length" name="full_length" v-model="form.full_length" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="lpp" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎垂線間長：</label>
                                    <input type="number"  step="0.1" id="lpp" name="lpp" v-model="form.lpp" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="breadth" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎型幅：</label>
                                    <input type="number"  step="0.1" id="breadth" name="breadth" v-model="form.breadth" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="beam_depth" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎型深さ :</label>
                                    <input type="number"  step="0.1" id="beam_depth" name="beam_depth" v-model="form.beam_depth" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="mold_draft" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎型深さ :</label>
                                    <input type="number"  step="0.1" id="mold_draft" name="mold_draft" v-model="form.mold_draft" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="draft_mark_F" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎喫水ﾏｰｸ：F :</label>
                                    <input type="tel" id="draft_mark_F" name="draft_mark_F" v-model="form.draft_mark_F" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="draft_mark_A" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎喫水ﾏｰｸ：A：</label>
                                    <input type="tel" id="draft_mark_A" name="draft_mark_A" v-model="form.draft_mark_A" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="fm_bl" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎AF塗上：fm B.L :</label>
                                    <input type="number"  step="0.1" id="fm_bl" name="fm_bl" v-model="form.fm_bl" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="draft_m" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎AF塗上：draft m：</label>
                                    <input type="tel" id="draft_m" name="draft_m" v-model="form.draft_m" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="layer_2or3" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎2.5層・3層 :</label>
                                    <input type="text" id="layer_2or3" name="layer_2or3" v-model="form.layer_2or3" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="winch_tension" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎ﾃﾝｼｮﾝｳｲﾝﾁ：</label>
                                    <input type="text" id="winch_tension" name="winch_tension" v-model="form.winch_tension" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="stern_towboat" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船尾曳航設備 :</label>
                                    <input type="text" id="stern_towboat" name="stern_towboat" v-model="form.stern_towboat" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="dk_machine_pp" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎甲板機ポンプ設置場所：</label>
                                    <input type="text" id="dk_machine_pp" name="dk_machine_pp" v-model="form.dk_machine_pp" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="exhaust" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎機関排気方式 :</label>
                                    <input type="text" id="exhaust" name="exhaust" v-model="form.exhaust" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="intake" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎機関室吸気方式：</label>
                                    <input type="text" id="intake" name="intake" v-model="form.intake" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="harbor_gen" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎停泊機設置場所 :</label>
                                    <input type="text" id="harbor_gen" name="harbor_gen" v-model="form.harbor_gen" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="fire_extinguish" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎消火設備：</label>
                                    <input type="text" id="fire_extinguish" name="fire_extinguish" v-model="form.fire_extinguish" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                </div> 

                            </template>
                            </vue-collapsible-panel>

                            <vue-collapsible-panel :expanded="false">
                            <template #title> 試運転記録等 </template>
                            <template #content> 
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                      <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                          <tr>
                                            <th scope="col" class="px-12 py-4">#</th>
                                            <th scope="col" class="px-12 py-4">出力50%</th>
                                            <th scope="col" class="px-12 py-4">出力100%</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">速力</td>
                                            <td><input type="number" step="0.01" v-model="form.speed50" class="text-right w-28 rounded"></td>
                                            <td><input type="number" step="0.01"  v-model="form.speed100" class="text-right w-28 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">ペラ回転数</td>
                                            <td><input type="number" step="1"  v-model="form.rpm_peller50" class="text-right w-28 rounded"></td>
                                            <td><input type="number"  step="1" v-model="form.rpm_peller100" class="text-right w-28 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">スリップ</td>
                                            <td><input type="number" step="0.01"  v-model="form.slip_rate50" class="text-right w-28 rounded"></td>
                                            <td ><input type="number" step="0.01"  v-model="form.slip_rate100" class="text-right w-28 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">後進引力（平均）</td>
                                            <td><input type="number" step="0.1"  v-model="form.tug_force50" class="text-right w-28 rounded"></td>
                                            <td><input type="number" step="0.1"  v-model="form.tug_force100" class="text-right w-28 rounded"></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </template>
                            </vue-collapsible-panel>
                            <vue-collapsible-panel :expanded="false">
                            <template #title> 船舶関係者 </template>
                            <template #content> 
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                      <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                          <tr>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">運航者</td>
                                            <td><input type="text"  v-model="form.operator" class="text-right w-30 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">船舶借入人</td>
                                            <td><input type="text"  v-model="form.borrower" class="text-right w-30 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">船舶管理人</td>
                                            <td><input type="text"  v-model="form.manager" class="text-right w-30 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">乗組員手配</td>
                                            <td><input type="text"  v-model="form.crew_arrange" class="text-right w-30 rounded"></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </template> 
                            </vue-collapsible-panel>
                   
                            <vue-collapsible-panel :expanded="false">
                            <template #title> 船舶所有者 </template>
                            <template #content> 
                              <div class="flex flex-col">
                                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                                  <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                    <div class="overflow-hidden">
                                      <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                          <tr>
                                            <th scope="col" class="px-20 py-4 ">所有者名</th>
                                            <th scope="col" class="px-12 py-4 ">割合（%）</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr v-for="(owner, index) in form.owners" class="border-b dark:border-neutral-500">
                                            <td><input type="text"  v-model="owner.owner_name" class="ml-20 w-60 rounded text-center"></td>
                                            <td><input type="number"  v-model.number="owner.ratio"  class="text-right ml-8 w-28 rounded"></td>
                                            <td><button  class="w-10 h-6 text-xs bg-red-300  text-white font-semibold rounded hover:bg-red-400"  @click="removeOwner(index)">削除</button></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <div class=" object-right">
                                      <button  class="mx-4 w-10 h-6 text-xs bg-blue-300  text-white font-semibold rounded hover:bg-blue-400" @click="addOwner">追加</button>
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
                        <div class="container px-5 py-2 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                <div class="p-0 w-full">
                                  <button @click="updateShip(form.id)" class="flex mx-auto text-white bg-indigo-500 border-0 mb-10 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>  
                                </div>
                                <div class="p-0 w-full">
                                </div>
                            </div></div></div>

                        </section> 
                      <!-- </form>                -->
                      <button @click="deleteItem(form.id)" class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">削除する</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Style for the drop zone */
.drop-zone {
  border: 2px dashed #cccccc;
  padding: 20px;
  text-align: center;
  cursor: pointer;
}


</style>