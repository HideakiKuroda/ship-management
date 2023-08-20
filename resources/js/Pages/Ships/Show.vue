<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import moment from 'moment';
import { ref } from 'vue';
import { VueCollapsiblePanelGroup, VueCollapsiblePanel,} from '@dafcoe/vue-collapsible-panel';
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";

const components = {
  VueCollapsiblePanelGroup,
  VueCollapsiblePanel,
};


const props = defineProps({
    ship : Object,
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
    <Head title="商品登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">船舶の詳細</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                     <section class="text-gray-600 body-font relative">
                    
                        <div class="container px-5 pt-8 mx-auto">
                          <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            <div class="m-2">
                                
                                <div class="p-2">
                                    <div id="name" class="w-72  bg-blue-50 rounded border focus:bg-white focus:ring-2 text-base outline-none text-black py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        ◆　{{ ship.name }}　『{{ ship.yard }}　No. {{ ship.ship_no }}』
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
                                    {{ props.ship.navigation_areas.name }}区域
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
                            </template>
                            </vue-collapsible-panel>
                            
                            <vue-collapsible-panel>
                            <template #title> 機関仕様その他 </template>
                            <template #content> 
                             <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎馬力：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.engine_kw }}　PS
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
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎発電機 :</label>
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
                                <div class="md:flex">
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎発電機原動機 :</label>
                                    <div id="name" class="w-48   text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    {{ props.ship.summaries.aux_engine }}
                                    </div>
                                  </div>
                                  <div class="p-2">
                                    <label for="name" class=" rounded  border border-indigo-300 px-1  leading-7 text-sm text-gray-600">◎停発設置場所：</label>
                                    <div id="name" class="w-48  text-base outline-none text-gray-700 py-0 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        {{ props.ship.summaries.harbor_gen }}
                                    </div>
                                  </div>
                                </div>
    
   

                            </template>
                            </vue-collapsible-panel>

                            <vue-collapsible-panel>
                            <template #title> 船体構造その他 </template>
                            <template #content>
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

                            </template>
                            </vue-collapsible-panel>

                            </vue-collapsible-panel-group>
                        </div>                        

                            </div></div></div>
                


                        






              
                                
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
