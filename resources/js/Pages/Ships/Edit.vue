<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { ref, reactive } from 'vue';
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};


const props = defineProps({
    ship : Object,
})

const form = reactive({         //内容をreactiveにform変数に収める
    id:          props.ship.id,
    name:        props.ship.name,
    yard:        props.ship.yard,
    ship_no:     props.ship.ship_no,
    ex_name:     props.ship.ex_name,
    former_name: props.ship.former_name,

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
                                
                                <div class="p-2">
                                    <div id="name" class="w-full  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆　船名・造船所・船番
                                          <div class="md:flex">
                                          <input type="text" id="name" name="name" v-model="form.name" class="w-1/3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="text" id="yard" name="yard" v-model="form.yard" class="w-1/3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                                    
                                          <input type="text" id="ship_no" name="ship_no" v-model="form.ship_no" class="w-1/3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">                           
                                        </div>
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
     
                                <div class="md:flex">
                                <div v-if="ship.ex_name" class="p-2">
                                <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">●船名（前）：</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ ship.ex_name }}
                                    </div>
                                    </div>

                                    <div v-if="ship.former_name" class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1 leading-7 text-sm text-gray-600">◎船名（元）：</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ ship.former_name }}
                                    </div>
                                    </div>
                                </div>
                                <div class="md:flex">
                                <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎竣工：</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ formatDate(ship.deliverd) }}
                                    </div>
                                    </div>
                                    <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎総トン数：</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ ship.gross_tonn }}㌧
                                    </div>
                                    </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎運航地域：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.operat_sections.section }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎航行区域 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                      {{ props.ship.navigation_areas.name }}<a v-if="props.ship.navigation_areas.name"> 区域</a>
                                  </div>
                                </div>
                              </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎船舶記号：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.official_number }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎信号符号 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.signal_code }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎国際トン数：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summary2s.international_ton }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎旅客定員 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summary2s.passenger_capacity }}
                                    </div>
                                  </div>
                                </div>
                                <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎船舶保険（種類）：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summary2s.insurance_type }}
                                    </div>
                                  </div>
                            </template>
                            </vue-collapsible-panel>
                            
                            <vue-collapsible-panel  :expanded="false">
                            <template #title> 機関仕様その他 </template>
                            <template #content> 
                             <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎馬力：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.engine_kw }}<a v-if="props.ship.summaries.engine_kw"> PS</a>
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎主機型式 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.me_model }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎主機製造番号：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.me_sno }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎ペラ仕様 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.pera_spec }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎ＺＰ製造番号：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.pera_sno }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎発電機型式 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.aux_engine }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎停泊用発電機：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.harbor_gen }}
                                    </div>
                                  </div>
                                </div>

                            </template>
                            </vue-collapsible-panel>

                            <vue-collapsible-panel  :expanded="false">
                            <template #title> 船体寸法・喫水・設備等 </template>
                            <template #content>
                              <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎全長 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.full_length }}<a v-if="props.ship.summaries.full_length"> ｍ</a>
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎垂線間長：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.lpp }}<a v-if="props.ship.summaries.lpp"> ｍ</a>
                                    </div>
                                  </div>
                                </div>
                            <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎型幅：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.breadth }}<a v-if="props.ship.summaries.breadth"> ｍ</a>
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎型深さ :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.beam_depth }}<a v-if="props.ship.summaries.beam_depth"> ｍ</a>
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎型喫水：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.mold_draft }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎喫水ﾏｰｸ：F :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.draft_mark_F }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎喫水ﾏｰｸ：A：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.draft_mark_A }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎AF塗上：fm B.L :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.fm_bl }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎AF塗上：draft m：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.draft_m }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎2.5層・3層 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.layer_2or3 }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎ﾃﾝｼｮﾝｳｲﾝﾁ：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.winch_tension }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎船尾曳航設備 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.stern_towboat }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎甲板機ポンプ設置場所：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.dk_machine_pp }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎機関排気方式 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.exhaust }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎機関室吸気方式：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.intake }}
                                    </div>
                                  </div>
                                </div>
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎停泊機設置場所 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.harbor_gen }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎消火設備：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.fire_extinguish }}
                                    </div>
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
                                            <td class="whitespace-nowrap pr-28 py-4 text-right">{{ props.ship.summary2s.speed50 }}</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right">{{ props.ship.summary2s.speed100 }}</td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">ペラ回転数</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right"> {{ props.ship.summary2s.rpm_peller50 }}</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right"> {{ props.ship.summary2s.rpm_peller100 }}</td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">スリップ</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right">{{ props.ship.summary2s.slip_rate50 }}</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right" >{{ props.ship.summary2s.slip_rate100 }}</td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">行進引力（平均）</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right"> {{ props.ship.summary2s.tug_force50 }}</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right"> {{ props.ship.summary2s.tug_force100 }}</td>
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
                                            <td class="whitespace-nowrap pr-28 py-4 text-right">{{ props.ship.concerneds.operator }}</td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">船舶借入人</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right"> {{ props.ship.concerneds.borrower }}</td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">船舶管理人</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right">{{ props.ship.concerneds.manager }}</td>
                                          </tr>
                                          <tr class="border-b dark:border-neutral-500">
                                            <td class="whitespace-nowrap px-12 py-4 font-medium">乗組員手配</td>
                                            <td class="whitespace-nowrap pr-28 py-4 text-right"> {{ props.ship.concerneds.crew_arrange }}</td>
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
