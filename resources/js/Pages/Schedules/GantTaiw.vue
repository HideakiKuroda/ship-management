<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted,computed, onUnmounted } from 'vue';
import moment from 'moment'; //npm install moment でインストール要
import { Head, Link,router } from '@inertiajs/vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'  ;
import UserSerch from '@/Components/UserSerch.vue';


const props = defineProps({
  users : Array,
  ships : Object,
  operatSections : Array,
  projects: Array,
  errors: Object,
  loginUser:  Object,
  hasRole:  Object,
})

// reactive data
const start_month = ref(moment().subtract(2, 'years'));
// const end_month = ref(lastDay);
const end_month = ref(moment().add(11, 'years'));
const block_size = ref(20);
// const block_number = ref(0);
const calendars = ref([]);
const inner_width = ref(''); //追加
const inner_height = ref('');
const task_width = ref('');//追加
const task_height = ref('');
const taskElement = ref(null);
const today = ref(moment());   // moment()はデフォルト: 現在時刻
const start_date = ref(moment(start_month.value));
const calendar = ref(null); 
const position_id = ref(0); 
const dragging = ref(false); 
const pageX = ref('');
const element = ref(null);
const left = ref('');
const task_id = ref('');
const leftResizing = ref(false);
const rightResizing = ref(false);
const width = ref('');
const task = ref('');
const user_id = ref();
const slectedOperatSection = ref();

const handleUserId = (currentUser) =>{
  user_id.value = currentUser
   console.log("handleUserId:", currentUser)
}

const findEarliest = (list) => {
  const dates = list.map(p => new Date(p.period2_end).getTime());
  const maxDate = new Date(Math.max(...dates));
  return maxDate;
};
// const lastDate =  new Date(moment(findEarliest(list2.value)));

const getMonths = (year, block_number) => {
  let months = [];
  let date = moment(`${year}-04-01`); // 年度開始を4月に設定
  for (let i = 0; i < 12; i++) {
    months.push({
      month: (date.month()) % 12 + 1, // 月の値を4月開始に調整
      block_number
    });
    date.add(1, 'month');
    block_number++;
  }
  return months;
};

const getCalendar = () => {
  let block_number = 0;
  let months;
  let start = moment(start_month.value);
  let end = moment(end_month.value);
  let between_years = end.year() - start.year() + 1; // 年度の差を計算
  for (let i = 0; i < between_years; i++) {
    months = getMonths(start.year(), block_number);
    calendars.value.push({
      date: start.format('YYYY年度'), // 年度表示に変更
      year: start.year(),
      start_block_number: block_number,
      calendar: months.length,
      months: months
    });
    start.add(1, 'year'); // 年度ごとに進める
    block_number = months[months.length - 1].block_number;
    block_number++;
  }
  return block_number;
}

const getWindowSize = () => {       //ref="taskElement" をタグ内に設定して表示の高さ幅を抽出
  inner_width.value = window.innerWidth;
  inner_height.value = window.innerHeight;
  if(taskElement.value) {
    task_width.value = taskElement.value.offsetWidth; 
    task_height.value = taskElement.value.offsetHeight;
  }
}

const calendarViewWidth = computed(()=>{
  return inner_width.value - task_width.value;
})

const calendarViewHeight = computed(()=>{
  return inner_height.value - task_height.value - 48 - 20;
})

//日数にblock_sizeをかけることでカレンダー領域の左端からの距離がわかります
// - calendarViewWidth.value / 2 で画面端から真ん中へ
const scrollDistance = computed(() => {
  let between_months = today.value.diff(start_date.value, 'months');
  return (between_months + 1) * block_size.value - calendarViewWidth.value / 2;
})

//calendar要素のscollLeftにscrollDistanceプロパティの値を設定
const todayPosition = () => {  //onMountedに設定し画面表示時に実行
  if (calendar.value) {
    calendar.value.scrollLeft = scrollDistance.value;
  }
};

const formatDate = (date) => {
   if (!date) return "";
  return moment(date).format('YYYY-MM-DD');
};

const threeMonths = ref(moment().add(3, 'months'))

const list1 = computed(() => {
  return props.ships
  .filter(ship => ship.schedules && ship.schedules.interim_dline1 != null) // null でない interim_dline1 を持つ船のみ処理
  .filter(ship => {
      // user_idがnullの場合は、このフィルタを適用しない
      if (user_id.value == null) {
        return true; // すべてのshipを含める
      }
    // user_idがnullでない場合は、特定のユーザーIDでフィルタリング
      return ship.users.some(user => user.id == user_id.value); //slectedOperatSection
    })
    .filter(ship => {
      //operat_section_id で絞込み
      if (slectedOperatSection.value == null) {
        return true; // すべてのshipを含める
      }
      return ship.operat_section_id == slectedOperatSection.value; //slectedOperatSection
    })
  .map(ship => {
    // 中間検査の日付を計算（過ぎた日付は除外し、近い日付を選択）
    const interimDeadline = [ship.schedules.interim_dline1, ship.schedules.interim_dline2]
      .map(d => moment(d))
      .filter(d => d.isAfter(threeMonths))
      .sort((a, b) => a.diff(today) - b.diff(today))[0];

    // 定期検査の日付を計算（過ぎた日付は除外し、近い日付を選択）
    const periodicDeadline = [ship.schedules.Periodic_dline1, ship.schedules.Periodic_dline2]
      .map(d => moment(d))
      .filter(d => d.isAfter(threeMonths))
      .sort((a, b) => a.diff(today) - b.diff(today))[0];

    return {
      id: ship.id,
      name: ship.name,   //船名
      nav: ship.navigation_areas.name, //航行区域
      del: ship.delivered,   //竣工
      spys: moment(today).diff(moment(ship.delivered), 'years'),  //船齢
      issue: ship.issueInspCert,   //定期完了
      interim: interimDeadline ? interimDeadline.format('YYYY-MM-DD') : 'N/A',   //中間検査
      period: periodicDeadline ? periodicDeadline.format('YYYY-MM-DD') : 'N/A', //定期検査
    };
  });
});

const list2 = computed(() => {
  return props.ships
    .filter(ship => ship.schedules && ship.schedules.interim_dline1 != null)
    .map(ship => {
      const schedule = ship.schedules;

    return {
      id: ship.id,
      interim1_start: schedule.interim_start1,
      interim1_end: schedule.interim_dline1,
      interim2_start: schedule.interim_start2,
      interim2_end: schedule.interim_dline2,
      period1_start: schedule.Periodic_start1,
      period1_end: schedule.Periodic_dline1,
      period2_start: schedule.Periodic_start2,
      period2_end: schedule.Periodic_dline2,
    };
  });
});

const list3 = computed(() => {
  // プロジェクトを ship_id に基づいてグループ化
  const projectsByShipId = new Map();

  props.projects.forEach(pro => {
    if (pro.start_date && pro.end_date) {
      const projectData = {
        id: pro.id,
        s_id: pro.ship_id,
        name: pro.name,
        category_id: pro.pro_category_id,
        start_date: pro.start_date,
        end_date: pro.end_date,
      };

      if (!projectsByShipId.has(pro.ship_id)) {
        projectsByShipId.set(pro.ship_id, [projectData]);
      } else {
        projectsByShipId.get(pro.ship_id).push(projectData);
      }
    }
  });

  return projectsByShipId;
});
 

const combinedData = computed(() => {
  return list1.value.map(ship => {
    const schedule = list2.value.find(s => s.id === ship.id) || {};
    const projects = list3.value.get(ship.id) || []; 
   

    return {
      // list1 からのデータ
      shipInfo: ship,
      // list2 からのスケジュール関連データ
      schedule,
      // list3 からのプロジェクト関連データ
      project: projects,
    };
  });
});

// 画面をスクロールしたときに各データのポジションを変更する
const displayTasks = computed(() => {
  let display_task_number = Math.floor(calendarViewHeight.value);
  return combinedData.value.slice(position_id.value, position_id.value + display_task_number);
});

const taskBars = computed(() => {
  let baseTop = 58; // 初期のトップ位置
  let rowHeight = 48; // 各行の高さ

  return displayTasks.value.map((data, index) => {    //スクロールに合わせた位置でタスクバー表示を変更dataにマップっされる
      const createStyle = (start, end, rowOffset) => {
      const date_from = moment(start);
      const date_to = moment(end);
      const between = date_to.diff(date_from, 'months') + 1;
      const left = date_from.diff(start_date.value, 'months') * block_size.value;
      const top = baseTop + (index * rowHeight) + (rowOffset * rowHeight);

      return {
        top: `${top}px`,
        left: `${left+180}px`,
        width: `${block_size.value * between}px`,
      };
    };
    // 1行目: interim バー 中間検査
    const interim1Style = data.schedule.interim1_start ? createStyle(data.schedule.interim1_start, data.schedule.interim1_end, 0) : null;
    const interim2Style = data.schedule.interim2_start ? createStyle(data.schedule.interim2_start, data.schedule.interim2_end, 0) : null;
    const interim1 = data.schedule.interim1_start;
    const interim2 = data.schedule.interim2_start;
    // 2行目: period バー　定期検査
    const period1Style = data.schedule.period1_start ? createStyle(data.schedule.period1_start, data.schedule.period1_end, 1) : null;
    const period2Style = data.schedule.period2_start ? createStyle(data.schedule.period2_start, data.schedule.period2_end, 1) : null;
    const period1 = data.schedule.period1_start;
    const period2 = data.schedule.period2_start;

    const createProjectStyle = (project) => {
      if (!project || !project.start_date || !project.end_date ) return null;
    // プロジェクトカテゴリーに応じてトップ位置を調整
    let rowOffset;
          switch (project.category_id) {
            case 1:
              rowOffset = 0.8; // period1Styleと同じ高さ
              break;
            case 2:
              rowOffset = -0.2; // interim1Styleと同じ高さ
              break;
            case 3:
            case 4:
              rowOffset = -1; // interim1Styleより16px上
              break;
            default:
              rowOffset = -1; // 既定値
          }
          const pstyle = createStyle(project.start_date, project.end_date, rowOffset);
          return {
            pstyle,
            id: project.id,
            s_id:project.s_id,
            name:project.name,
            category_id: project.category_id,
            start_date: project.start_date,
            end_date: project.end_date,
          }
        };

        // 各プロジェクトバーのスタイルを生成
        const projectStyles = data.project.map(project => createProjectStyle(project));
        // console.log(projectStyles);

    baseTop = baseTop + 96;
    return {
      shipInfo: data.shipInfo,
      interim1Style,
      interim2Style,
      period1Style,
      period2Style,
      interim1,
      interim2,
      period1,
      period2,
      projectStyles,
    };
  });
});

const getUserIds = (projectId) => {
  // 特定のプロジェクトを見つける
  const project = props.projects.find(p => p.id === projectId);
  // プロジェクトが見つかった場合、関連するユーザーのIDリストを返す
  if (project && project.users) {
    return project.users.map(user => user.id);
  }
  // 関連するユーザーがいないか、プロジェクトが見つからない場合は空の配列を返す
  return [];
};

const editOpen = (id) => {
  if (getUserIds(id).some(userId => userId === props.loginUser.id || props.hasRole == true)) {
    
    router.get(route('projects.edit', { project:id }));
  } else {
    alert('編集は担当者とシステム管理者のみ可能です');
  }  
}

const windowSizeCheck = (event) => {
  let height = list1.value.length - position_id.value
  if (event.deltaY > 0 && height * calendarViewHeight.value > calendarViewHeight.value) {
    position_id.value++
  } else if (event.deltaY < 0 && position_id.value !== 0) {
    position_id.value--
  }
}

const mouseDownMove = (task, event) => {
  if(event.button !== 0) return; // Ensure only the left mouse button initiates the drag.
  dragging.value = true;
  pageX.value = event.pageX;
  element.value = event.target;
  left.value = event.target.style.left;
  task_id.value = task.id;
}
const mouseMove = (event) => {
  if (dragging.value) {
    let diff = pageX.value - event.pageX;
    element.value.style.left = `${parseInt(left.value.replace('px', '')) - diff}px`;
  }
}

const stopDrag = (event) => {
  if (dragging.value) {
    let diff = pageX.value - event.pageX;
    let months = Math.ceil(diff / block_size.value);
    if (months !== 0) {
      console.log(months);
      let task = tasks.value.find(task => task.id === task_id.value);
      let start_date = moment(task.start_date).add(-months, 'months');
      let end_date = moment(task.end_date).add(-months, 'months');
      task['start_date'] = start_date.format('YYYY-MM');
      task['end_date'] = end_date.format('YYYY-MM');
    } else {
      element.value.style.left = `${left.value.replace('px', '')}px`;
    }
  }
  if (leftResizing.value) {
    let diff = pageX.value - event.pageX;
    let months = Math.ceil(diff / block_size.value);
    if (months !== 0) {
      let task = tasks.value.find(task => task.id === task_id.value);
      let start_date = moment(task.start_date).add(-months, 'months');
      let end_date = moment(task.end_date);
      if (end_date.diff(start_date, 'months') <= 0) {
        task['start_date'] = end_date.format('YYYY-MM');
      } else {
        task['start_date'] = start_date.format('YYYY-MM');
      }
    } else {
      element.value.style.width = width.value;
      element.value.style.left = `${left.value.replace('px', '')}px`;
    }
  }
  if (rightResizing.value) {
    let diff = pageX.value - event.pageX;
    let months = Math.ceil(diff / block_size.value);
    let task = tasks.value.find(task => task.id === task_id.value);
    let end_date, start_date;
    if (months === 1) {
      task.element.style.width = `${parseInt(task.width.replace('px', ''))}px`;
    } else if (months <= 2) {
      months--;
      end_date = moment(task.end_date).add(-months, 'months');
      task['end_date'] = end_date.format('YYYY-MM');
    } else {
      start_date = moment(task.start_date);
      end_date = moment(task.end_date).add(-months, 'months');
      if (end_date.diff(start_date, 'months') < 0) {
        task['end_date'] = start_date.format('YYYY-MM');
      } else {
        task['end_date'] = end_date.format('YYYY-MM');
      }
    }
  }
  dragging.value = false;
  leftResizing.value = false;
  rightResizing.value = false;
}

const mouseDownResize = (task, direction, event) => {
  if (direction === 'left') {
    leftResizing.value = true;
  } else {
    rightResizing.value = true;
  }
  pageX.value = event.pageX;
  width.value = event.target.parentElement.style.width;
  left.value = event.target.parentElement.style.left;
  element.value = event.target.parentElement;
  task_id.value = task.id;
}

const mouseResize = (event) => {
  if (leftResizing.value) {
    let diff = pageX.value - event.pageX;
    if (parseInt(width.value.toString().replace('px', '')) + diff > block_size.value) {
      element.value.style.width = `${parseInt(width.value.replace('px', '')) + diff}px`;
      element.value.style.left = `${parseInt(left.value.replace('px', '')) - diff}px`;
    }
  }
  if (rightResizing.value) {
    let diff = event.pageX - pageX.value ;
    if (parseInt(width.value.replace('px', '')) + diff > block_size.value) {
      element.value.style.width = `${parseInt(width.value.replace('px', '')) + diff}px`;
    }
  }
}

const dragTask = (dragTask) => {
  task.value = dragTask;
}

const handleBarClick = (bar) => {
  console.log("クリックされたバーのデータ:", bar);
}

const dragTaskOver = (overTask) => {
  let deleteIndex;
  let addIndex;
  if (task.value.cat !== 'category') {
    if (overTask.cat === 'category') {
      let updateTask = tasks.value.find(task => task.id === task.value.id);
      updateTask['category_id'] = overTask['id'];
    } else {
      if (overTask.id !== task.value.id) {
        tasks.value.map((task, index) => { if (task.id === task.value.id) deleteIndex = index; });
        tasks.value.map((task, index) => { if (task.id === overTask.id) addIndex = index; });
        tasks.value.splice(deleteIndex, 1);
        task.value['category_id'] = overTask['category_id'];
        tasks.value.splice(addIndex, 0, task.value);
      }
    }
  }
}

const  getBarColor = (categoryId) => {
  switch (categoryId) {
    case 1:
      return 'bg-red-400';
    case 2:
      return 'bg-yellow-500';
    case 3:
      return 'bg-blue-400';
    case 4:
      return 'bg-green-400';
    default:
      return 'bg-gray-400';
}}

onMounted(() => {
  getCalendar();
  getWindowSize();
  todayPosition();
  window.addEventListener('resize', getWindowSize);
  window.addEventListener('wheel', windowSizeCheck);
  window.addEventListener('mousemove', mouseMove);
  window.addEventListener('mouseup', stopDrag);
  window.addEventListener('mousemove', mouseResize);

//   taskBars.value.forEach((task, index) => {
//   console.log(`projectStyles for task ${index}:`, task.projectStyles);
// });


  // console.log('検査最終',moment(findEarliest(list2.value)).format('YYYY-MM-DD'));
  // console.log('ドックデータ',combinedData.value)
  // console.log('運航地域',props.ships.operat_sections)
  // nextTick().then(() => {
  //    calendar.value.scrollLeft = scrollDistance.value;
  //  });
})

onUnmounted(() => {
  window.removeEventListener('resize', getWindowSize);
  window.removeEventListener('wheel', windowSizeCheck);
  window.removeEventListener('mousemove', mouseMove);
  window.removeEventListener('mouseup', stopDrag);
  window.removeEventListener('mousemove', mouseResize);
});

defineExpose({ windowSizeCheck, displayTasks})


</script>


<template>
 <Head title="ドックスケジュール" />

<AuthenticatedLayout>
    <template #header >
      <div class="flex flex-row">
      <div class="font-semibold text-xl text-gray-800 leading-tight">ドックスケジュール  {{ start_month.format('YYYY年MM月') }}  ～  {{ end_month.format('YYYY年MM月') }}</div>
      <!-- 担当者検索コンボボックス　ここから -->
      <div class=" flex flex-row">
        <UserSerch :userId="user_id" :users="props.users" @update:currentUser="handleUserId" class="justify-start opacity-100 z-10"/>
      </div>
    
      <!-- 担当者検索コンボボックス　ここまで -->
      <!-- 運航地域の絞込み　ここから -->
      <div class="flex flex-col">
        <label for="section" class="ml-4 w-28 leading-tight  text-justify text-sm text-gray-600">運航地域</label>
        <div id="section" class=" w-48  text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" >
        <!-- ユーザー選択ドロップダウン -->
        <select class="rounded-lg  border border-indigo-300 h-10 w-40" v-model="slectedOperatSection">
          <option  :value="null">※ 全地域 ※</option>
          <option v-for="operatSection in props.operatSections" :key="operatSection.id" :value="operatSection.id">
            {{ operatSection.section || '全地域'  }}
            </option>
        </select>
      </div>  
      </div>
    </div>
      <!-- 運航地域の絞込み　ここまで -->
    </template>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <BreezeValidationErrors :errors="errors" />  
                <!-- <form @submit.prevent="updateShip(form.id)" >   -->
                 <section class="text-gray-600 body-font relative">


   <div id="gantt-content" class="flex">
    <!-- ガントチャートのタスク領域 -->
    <div id="gantt-task">  
      <div id="gantt-task-title" class="flex items-center bg-indigo-400 text-white h-16" ref="taskElement">
        <div class="border-t border-r border-b flex items-center justify-center font-bold text-xs w-60 h-full">
        船名&emsp;＆&emsp;基本情報
      </div>
          </div>
    <!-- ガントチャートのタスク領域 -->
    <div id="gantt-task-list" class="overflow-y-hidden" :style="`height:${calendarViewHeight}px`">
      <div v-for="(item,index) in displayTasks" :key="index" class="flex flex-col h-36 border-b"> 
      <!-- <div v-for="(task,index) in list1" :style="taskBars.value && taskBars.value[index] ? { top: `${taskBars.value[index].top}px` } : {}" class="flex flex-col  h-32 border-b"> -->
  <!-- タスクの情報を表示するコード -->
        <!-- @dragstart="dragTask" @dragoverr.prevent="dragTaskOver(task)" draggable="true"> -->
          <div class="flex items-center font-bold w-full text-xl pl-2">
            {{item.shipInfo.name}}
          </div>
          <div class="border-r flex font-bold  text-sm pl-4">
            竣工:{{formatDate(item.shipInfo.del)}}<br>航行区域:{{item.shipInfo.nav}}&emsp;&emsp;船齢：{{ item.shipInfo.spys }}年
          </div>
          <div class="border-r flex  text-sm  pl-4">
            定期検査完了：{{formatDate(item.shipInfo.issue)}}
          </div>
          <div class="border-r flex   text-sm pl-4">
            中間検査期限：{{formatDate(item.shipInfo.interim)}}
          </div>
          <div class="border-r flex   text-sm pl-4">
            定期検査期限：{{formatDate(item.shipInfo.period)}}
          </div>
        </div>
    </div>
    </div> <!-- id="gantt-task"  -->

    <!-- ガントチャートのカレンダー領域 -->
    <div id="gantt-calendar" class="overflow-x-scroll  overflow-y-hidden border-l" 
          :style="`width:${calendarViewWidth}px`" 
           ref="calendar">
      <div id="gantt-date" class="h-16">
        <!-- ここから　カレンダーの年数 -->
        <div id="gantt-year-month" class="relative h-8">
          <div v-for="(calendar,index) in calendars" :key="index">
            <div class="bg-indigo-700 text-white border-b border-r border-t h-8 absolute font-bold text-sm 
            flex items-center justify-center" 
            :style="`width:${calendar.calendar*block_size}px;left:${calendar.start_block_number*block_size}px`">
              {{calendar.date}}
            </div>
          </div>
        </div>
        <!-- ここから　カレンダーの月数 -->
        <div id="gantt-day" class="relative h-8">
          <div v-for="(calendar,index) in calendars" :key="index">
            <div v-for="(month,index) in calendar.months" :key="index">
              <div class="border-r border-b h-8 absolute flex items-center justify-center flex-col font-bold text-xs"
                :class="{'bg-blue-100': month.month === 4, 'bg-red-100': month.month === 3,
                'bg-red-600 text-white': calendar.year===  today.year() && month.month === today.month() + 1}"
                :style="`width:${block_size}px;left:${month.block_number*block_size}px`">
                <span>{{ month.month }}</span> <!-- monthは0から始まるため1を加えます -->
              </div>
            </div>
          </div>
        </div>
        <!-- ここまで -->
        <div id="gantt-height" class="relative">
        <!-- //カレンダーの縦線 -->
          <div v-for="(calendar,index) in calendars" :key="index">
            <div v-for="(month,index) in calendar.months" :key="index">
              <div class="border-r border-b absolute" 
              :class="{'bg-blue-100': month.month === 4, 'bg-red-100': month.month === 3}"
              :style="`width:${block_size}px;left:${month.block_number*block_size}px;height:${calendarViewHeight}px`">
              </div>
            </div>
          </div>

          <div id="gantt-bar-area" class="relative overflow-y-hiddn" :style="`width:${calendarViewWidth}px;height:${calendarViewHeight}px`">
          <div v-for="(bar, index) in taskBars" :key="index" class="flex flex-col h-36">
            <!-- 空の行（先頭行） -->
            <div class="absolute h-5" :style="{ top: `${bar.top}px` }"></div>
            <!-- Interim と Period のバー -->
            <div :style="bar.interim1Style" class="rounded-lg absolute h-5 bg-yellow-200 text-center text-xs" v-if="bar.interim1Style" @dblclick="handleBarClick(bar)">
              {{ bar.shipInfo.name}}&emsp;&emsp;中間①&emsp;{{formatDate(bar.interim1)}}～
            </div>
            <div :style="bar.interim2Style" class="rounded-lg absolute h-5 bg-yellow-200 text-center text-xs" v-if="bar.interim2Style" @dblclick="handleBarClick(bar)">
              {{ bar.shipInfo.name}}&emsp;&emsp;中間②&emsp;{{formatDate(bar.interim2)}}～
            </div>
            <div :style="bar.period1Style" class="rounded-lg absolute h-5 bg-red-200 text-center text-xs" v-if="bar.period1Style" @dblclick="handleBarClick(bar)">
              {{ bar.shipInfo.name}}&emsp;&emsp;定期①&emsp;{{formatDate(bar.period1)}}～
            </div>
            <div :style="bar.period2Style" class="rounded-lg absolute h-5 bg-red-200 text-center text-xs" v-if="bar.period2Style" @dblclick="handleBarClick(bar)">
              {{ bar.shipInfo.name}}&emsp;&emsp;定期②&emsp;{{formatDate(bar.period2)}}～
            </div>
            <!-- プロジェクトバー（複数） -->
            <div v-for="(projectStyle, index) in bar.projectStyles" :key="index" :style="projectStyle.pstyle" 
            :class="['rounded-lg absolute h-5 text-center text-xs',getBarColor(projectStyle.category_id)]" v-if="bar.projectStyles"  @dblclick="editOpen(projectStyle.id)">
              <div v-if="projectStyle.category_id == 1" >{{ projectStyle.name }}</div>
              <div v-else-if="projectStyle.category_id == 2" >{{ projectStyle.name }}</div>
              <div v-else-if="projectStyle.category_id == 3" >{{ projectStyle.name }}</div>
              <div v-else-if="projectStyle.category_id == 4">{{ projectStyle.name }}</div>
            </div>
            <div :style="`width:${calendarViewWidth*3}px`" class="h-full border-b "></div>
          </div>
        </div>
        </div> <!--id="gantt-height"-->
      </div>  <!--id="gantt-date"-->
    </div>   <!-- id="gantt-calendar" -->
  </div>    <!-- id="gantt-content" カレンダーとタスクの両方 -->

</section> 
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
  
</template>