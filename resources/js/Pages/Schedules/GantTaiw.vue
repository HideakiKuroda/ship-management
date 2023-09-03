<script setup>
import { ref, onMounted,computed, nextTick, onUnmounted } from 'vue';
import moment from 'moment'; //npm install moment でインストール要


// reactive data
const start_month = ref('2022-04');
const end_month = ref('2031-03');
const block_size = ref(15);
const block_number = ref('');
const calendars = ref([]);
const inner_width = ref(''); //追加
const inner_height = ref('');
const task_width = ref('');//追加
const task_height = ref('');
const taskElement = ref(null);
const today = ref(moment());
const start_date = ref(moment(start_month.value));
const calendar = ref(null); 
const position_id = ref(''); 
const dragging = ref(false); 
const pageX = ref('');
const element = ref(null);
const left = ref('');
const task_id = ref('');
const leftResizing = ref(false);
const rightResizing = ref(false);
const width = ref('');
const task = ref('');

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

const getWindowSize = () => {
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

const scrollDistance = computed(() => {
  let between_months = today.value.diff(start_date.value, 'months');
  return (between_months + 1) * block_size.value - calendarViewWidth.value / 2;
})

const categories = ref([
  {
    id: 1,
    name: 'おふゆ',
    collapsed: false,
  },
  {
    id: 2,
    name: 'そうや',
    collapsed: false,
  },
]);

const tasks = ref([
  {
    id: 1,
    category_id: 1,
    name: '中間検査',
    start_date: '2023-11',
    end_date: '2024-03',
    incharge_user: '北海曳船',
  },
  {
    id: 2,
    category_id: 1,
    name: '合入渠',
    start_date: '2023-10',
    end_date: '2024-02',
    incharge_user: '北海曳船',
  },
  {
    id: 3,
    category_id: 1,
    name: '定期検査',
    start_date: '2023-12',
    end_date: '2024-04',
    incharge_user: '北海曳船',
},
{
    id: 4,
    category_id: 1,
    name: '合入渠',
    start_date: '2023-08',
    end_date: '2023-11',
    incharge_user: '北海曳船',
},
{
    id: 5,
    category_id: 2,
    name: '定期検査',
    start_date: '2023-07',
    end_date: '2023-12',
    incharge_user: '石狩新港',
},
{
    id: 6,
    category_id: 2,
    name: '合入渠',
    start_date: '2023-06',
    end_date: '2023-10',
    incharge_user: '石狩新港',
    },
]);

const lists = computed(() => {
  let lists = [];
  categories.value.map(category => {
    lists.push({ cat: 'category', ...category });
    tasks.value.map(task => {
      if (task.category_id === category.id) {
        lists.push({ cat: 'task', ...task })
      }
    })
  });
  return lists;
})


const taskBars = computed(() => {
  let top = 10;
  let left;
  let between;
  let start;
  let style;
  return displayTasks.value.map(task => {
    style = {}
    if(task.cat === 'task'){
      let date_from = moment(task.start_date);
      let date_to = moment(task.end_date);
      between = date_to.diff(date_from, 'months');
      between++;
      start = date_from.diff(start_date.value, 'months');
      left = start * block_size.value;
      style = {
        top: `${top}px`,
        left: `${left}px`,
        width: `${block_size.value * between}px`,
      }
    }
    top = top + 40;
    return {
      style,
      task
    }
  })
})

const windowSizeCheck = (event) => {
  let height = lists.value.length - position_id.value
  if (event.deltaY > 0 && height * 40 > calendarViewHeight.value) {
    position_id.value++
  } else if (event.deltaY < 0 && position_id.value !== 0) {
    position_id.value--
  }
}

const displayTasks = computed(() => {
  let display_task_number = Math.floor(calendarViewHeight.value / 40);
  return lists.value.slice(position_id.value, position_id.value + display_task_number);
});

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

onMounted(() => {
  getCalendar();
  getWindowSize();
  window.addEventListener('resize', getWindowSize);
  window.addEventListener('wheel', windowSizeCheck);
  window.addEventListener('mousemove', mouseMove);
  window.addEventListener('mouseup', stopDrag);
  window.addEventListener('mousemove', mouseResize);

  console.log('mouseDownMove');
  
  nextTick().then(() => {
     calendar.value.scrollLeft = scrollDistance.value;
   });
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
  <div id="gantt-header" class="h-12 p-2 flex items-center">
    <h1 class="text-xl font-bold">スケジュール管理  {{ start_month }}  ～  {{ end_month }}</h1>
  </div>
  <div id="gantt-content" class="flex">
    <div id="gantt-task">
      <div id="gantt-task-title" class="flex items-center bg-green-600 text-white h-20" ref="taskElement">
        <div class="border-t border-r border-b flex items-center justify-center font-bold text-xs w-32 h-full">
        タスク
      </div>
      <div class="border-t border-r border-b flex items-center justify-center font-bold text-xs w-20 h-full">
          開始日
      </div>
      <div class="border-t border-r border-b flex items-center justify-center font-bold text-xs w-20 h-full">
          完了期限日
      </div>
      <div class="border-t border-r border-b flex items-center justify-center font-bold text-xs w-20 h-full">
          担当
       </div>
    </div>
    <div id="gantt-task-list" class="overflow-y-hidden" :style="`height:${calendarViewHeight}px`">
      <div v-for="(task,index) in displayTasks" :key="index" class="flex h-10 border-b" 
        @dragstart="dragTask" @dragoverr.prevent="dragTaskOver(task)" draggable="true">
        <template v-if="task.cat === 'category'">
          <div class="flex items-center font-bold w-full text-2xl pl-2">
            {{task.name}}
          </div>
        </template>
        <template v-else>
          <div class="border-r flex items-center font-bold w-32 text-sm pl-4">
            {{task.name}}
          </div>
          <div class="border-r flex items-center justify-center w-20 text-sm">
            {{task.start_date}}
          </div>
          <div class="border-r flex items-center justify-center w-20 text-sm">
            {{task.end_date}}
          </div>
          <div class="border-r flex items-center justify-center w-20 text-sm">
            {{task.incharge_user}}
          </div>
        </template>
      </div>
    </div>
    </div> <!-- id="gantt-task"  -->

    <div id="gantt-calendar" class="overflow-x-scroll  overflow-y-hidden border-l" 
          :style="`width:${calendarViewWidth}px`" ref="calendar">
      <div id="gantt-date" class="h-20">
        <div id="gantt-year-month" class="relative h-8">
          <div v-for="(calendar,index) in calendars" :key="index">
            <div class="bg-indigo-700 text-white border-b border-r border-t h-8 absolute font-bold text-sm 
            flex items-center justify-center" :style="`width:${calendar.calendar*block_size}px;left:${calendar.start_block_number*block_size}px`">
              {{calendar.date}}
            </div>
          </div>
        </div>
        <!-- ここから -->
        <div id="gantt-day" class="relative h-12">
          <div v-for="(calendar,index) in calendars" :key="index">
            <div v-for="(month,index) in calendar.months" :key="index">
              <div class="border-r border-b h-12 absolute flex items-center justify-center flex-col font-bold text-xs"
                :style="`width:${block_size}px;left:${month.block_number*block_size}px`">
                <span>{{ month.month }}</span> <!-- monthは0から始まるため1を加えます -->
              </div>
            </div>
          </div>
        </div>
        <!-- ここまで -->
        <div id="gantt-height" class="relative">
        <!-- //追加 -->
          <div v-for="(calendar,index) in calendars" :key="index">
            <div v-for="(month,index) in calendar.months" :key="index">
              <div class="border-r border-b absolute" 
              :class="{'bg-blue-100': month.month === 4, 'bg-red-100': month.month === 3}"
              :style="`width:${block_size}px;left:${month.block_number*block_size}px;height:${calendarViewHeight}px`">
              </div>
            </div>
          </div>
          <div id="gantt-bar-area" class="relative"
              :style="`width:${calendarViewWidth}px; height:${calendarViewHeight}px`">
            <div v-for="(bar,index) in taskBars" :key="index">
              <div :style="bar.style" class="rounded-lg absolute h-5 bg-yellow-200"
                v-if="bar.task.cat === 'task'"  @mousedown="mouseDownMove(bar.task, $event)">
                <div class="w-full h-full" style="pointer-events: none;"></div>
                <!-- //以下を追加　四角いボタン -->
                <div class="absolute w-2 h-2 bg-yellow-300 border border-black hover:bg-red-600 "
                    style="top:6px;left:-6px;cursor: pointer;" 
                    @mousedown.stop="mouseDownResize(bar.task,'left', $event)"></div>
                <div class="absolute w-2 h-2 bg-yellow-300 border border-black hover:bg-red-600 "
                     style="top:6px;right:-6px;cursor: pointer;" 
                     @mousedown.stop="mouseDownResize(bar.task,'right', $event)"></div>
              </div>
            </div>  
          </div>
        </div> <!--id="gantt-height"-->
      </div>  <!--id="gantt-date"-->
    </div>   <!-- id="gantt-calendar" -->
  </div>    <!-- id="gantt-content" カレンダーとタスクの両方 -->
  
</template>