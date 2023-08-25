<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { ref, reactive } from 'vue';
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import axios from 'axios';



const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};


const props = defineProps({
    ship : Object,
    navigationAreas : Array,
    operatSections : Array,
    users :Array,
})


const form = reactive({         //内容をreactiveにform変数に収める
    id:                 props.ship.id,
    selectedUser:       null,
    name:               props.ship.name,
    yard:               props.ship.yard,
    ship_no:            props.ship.ship_no,
    ex_name:            props.ship.ex_name,
    former_name:        props.ship.former_name,
    slectedOperatSection : props.ship.operat_section_id,
    selectedNavigationArea : props.ship.navigation_area_id,
    delivered:           props.ship.delivered,
    gross_tonn:           props.ship.gross_tonn,
    
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
    mold_draft:          props.ship.summaries.mold_draft,
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

}) 

const deleteItem = id => {
    Inertia.delete(route('ships.destroy',{ ship:id }),{
        onBefore: () => confirm('本当に削除しますか？')
    })
}

const formatDate = (date) => {
//   if (!date) return "N/A";
  return moment(date).format('YYYY年MM月DD日');
};

const updateShip = async (event) => {
  event.preventDefault();
try {
    await axios.put(`/ships/${props.ship.id}`, {
      navigation_area_id: selectedNavigationArea.value,
      // 他のフィールド
    });
    // 成功時の処理、例えばページのリダイレクトなど
  } catch (error) {
    // エラー時の処理
  }
};


const assignUser = () => {
    Inertia.post(route('ships.assignUser', { id: form.id }), { user_id: form.selectedUser })
        .then(() => {
            // 成功時の処理
        })
        .catch((error) => {
            console.error('Error assigning user', error);
            // エラー時の処理
        });
};

const unassignUser = (userId) => {
    Inertia.delete(route('ships.unassignUser', { id: form.id, user_id: userId })) // user_idをURLに含める
        .then(() => {
            // 成功時の処理
        })
        .catch((error) => {
            console.error(`Error unassigning user: ${error}`);
            // エラー時の処理
        });
};
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
                     <section class="text-gray-600 body-font relative">
                    
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="lg:w-2/3 md:w-2/3 mx-auto">
                            <div class="m-2">
                                
                                <div class="flex flex-col p-2 ml-4">
                                    <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      <label> ◆　船名・造船所・船番 </label>
                                          <div class="flex flex-wrap sm:flex-row sm:space-x-4">
                                          <input type="text" id="name" name="name" v-model="form.name" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 ml-4 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="text" id="yard" name="yard" v-model="form.yard" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="text" id="ship_no" name="ship_no" v-model="form.ship_no" class="w-30 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                           
                                        </div>
                                  </div>
                                  <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆　担当者
                                        <div class="flex flex-wrap sm:flex-row sm:space-x-4">
                                          <div class="h-20 w-40 overflow-auto">
                                            <div>
                                                <ul>
                                                    <li v-for="user in ship.users" :key="user.id">
                                                        {{ user.name }}
                                                        <button class="mx-4 px-1.5 py-0 text-xs bg-red-300  text-white font-semibold rounded-full hover:bg-red-400" @click="unassignUser(user.id)">削除</button>
                                                    </li>
                                                </ul>
                                           </div>
                                          </div>
                                          <div class="flex flex-wrap sm:flex-row h-20 w-80">
                                            <select class="rounded  border border-indigo-300 h-10 w-40" v-model="form.selectedUser">
                                              <option v-for="user in users" :value="user.id" :key="user.id">
                                                  {{ user.name }}
                                              </option>
                                            </select>
                                          <button class="mx-4 h-8 w-14 px-1.5 py-0 text-m bg-blue-400  text-white font-semibold rounded hover:bg-blue-500" @click="assignUser">追加</button>
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
                                      <input type="text" id="delivered" name="delivered" v-model="form.delivered" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                 
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="gross_tonn" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎総トン数：</label>
                                      <input type="text" id="gross_tonn" name="gross_tonn" v-model="form.gross_tonn" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="name" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎運航地域：</label>
                                      <div id="name" class=" w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" >
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
                                      <input type="text" id="official_number" name="official_number" v-model="form.official_number" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="signal_code" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎信号符号 :</label>
                                      <input type="text" id="signal_code" name="signal_code" v-model="form.signal_code" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="international_ton" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎国際トン数：</label>
                                      <input type="text" id="international_ton" name="international_ton" v-model="form.international_ton" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="passenger_capacity" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎旅客定員 :</label>
                                      <input type="text" id="passenger_capacity" name="passenger_capacity" v-model="form.passenger_capacity" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                    </div>
                                  
                                    <div class="flex flex-col p-2 ml-4">
                                      <label for="insurance_type" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎船舶保険（種類）：</label>
                                      <input type="text" id="insurance_type" name="insurance_type" v-model="form.insurance_type" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
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
                                    <input type="text" id="engine_kw" name="engine_kw" v-model="form.engine_kw" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="me_model" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎主機型式 :</label>
                                    <input type="text" id="me_model" name="me_model" v-model="form.me_model" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="me_sno" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎主機製造番号：</label>
                                    <input type="text" id="me_sno" name="me_sno" v-model="form.me_sno" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="pera_spec" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎ペラ仕様 :</label>
                                    <input type="text" id="pera_spec" name="pera_spec" v-model="form.pera_spec" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="pera_sno" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎ＺＰ製造番号：</label>
                                    <input type="text" id="pera_sno" name="pera_sno" v-model="form.pera_sno" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="aux_engine" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎発電機型式 :</label>
                                    <input type="text" id="aux_engine" name="aux_engine" v-model="form.aux_engine" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
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
                                    <input type="text" id="full_length" name="full_length" v-model="form.full_length" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="lpp" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎垂線間長：</label>
                                    <input type="text" id="lpp" name="lpp" v-model="form.lpp" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="breadth" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎型幅：</label>
                                    <input type="text" id="breadth" name="breadth" v-model="form.breadth" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="beam_depth" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎型深さ :</label>
                                    <input type="text" id="beam_depth" name="beam_depth" v-model="form.beam_depth" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="mold_draft" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎型深さ :</label>
                                    <input type="text" id="mold_draft" name="mold_draft" v-model="form.mold_draft" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="draft_mark_F" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎喫水ﾏｰｸ：F :</label>
                                    <input type="text" id="draft_mark_F" name="draft_mark_F" v-model="form.draft_mark_F" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="draft_mark_A" class="rounded  w-28 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎喫水ﾏｰｸ：A：</label>
                                    <input type="text" id="draft_mark_A" name="draft_mark_A" v-model="form.draft_mark_A" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="fm_bl" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎AF塗上：fm B.L :</label>
                                    <input type="text" id="fm_bl" name="fm_bl" v-model="form.fm_bl" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
                                  </div>
                                  <div class="flex flex-col p-2 ml-4">
                                    <label for="draft_m" class="rounded  w-30 leading-tight border border-indigo-300 text-justify text-sm text-gray-600">◎AF塗上：draft m：</label>
                                    <input type="text" id="draft_m" name="draft_m" v-model="form.draft_m" class="w-30  bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700  mt-1  leading-tight transition-colors duration-200 ease-in-out">                                    
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
                                            <td><input type="text"  v-model="form.speed50" class="text-right w-28 rounded"></td>
                                            <td><input type="text"  v-model="form.speed100" class="text-right w-28 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">ペラ回転数</td>
                                            <td><input type="text"  v-model="form.rpm_peller50" class="text-right w-28 rounded"></td>
                                            <td><input type="text"  v-model="form.rpm_peller100" class="text-right w-28 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">スリップ</td>
                                            <td><input type="text"  v-model="form.slip_rate50" class="text-right w-28 rounded"></td>
                                            <td ><input type="text"  v-model="form.slip_rate100" class="text-right w-28 rounded"></td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">後進引力（平均）</td>
                                            <td><input type="text"  v-model="form.tug_force50" class="text-right w-28 rounded"></td>
                                            <td><input type="text"  v-model="form.tug_force100" class="text-right w-28 rounded"></td>
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
                                            <th scope="col" class="px-12 py-4 text-center">所有者名</th>
                                            <th scope="col" class="px-12 py-4 text-center">割合（%）</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr v-for="shipOwner in props.ship.ship_owners" class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 text-center">{{ shipOwner.owner_name }}</td>
                                            <td class="whitespace-nowrap pr-36 py-4 text-right">{{ shipOwner.ratio }}</td>
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
                                <!-- <Link as="button" :href="route('ship.edit', { ship:ship.id })" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集する</Link> -->
                                </div>
                                <div class="p-0 w-full">
                                <button @click="deleteItem(ship.id)" class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">削除する</button>
                                </div>
                            </div></div></div>

                        </section>                
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
