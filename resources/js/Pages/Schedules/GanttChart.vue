<template>
    <g-gantt-chart
      chart-start="2023-04-01 00:00"
      chart-end="2028-03-30 23:59"
      precision="month"
      width="250%"
      bar-start="myBeginDate"
      bar-end="myEndDate"
    >
      <g-gantt-row label="My row 1" :bars="row1BarList" />
      <g-gantt-row label="My row 2" :bars="row2BarList" />
    </g-gantt-chart>
  </template>
  
  <script setup>
  import { ref } from "vue"
  
  const row1BarList = ref([
    {
      myBeginDate: "2023-07-13 00:00",
      myEndDate: "2024-07-23 23:59",
      ganttBarConfig: {
        // each bar must have a nested ganttBarConfig object ...
        id: "unique-id-1", // ... and a unique "id" property
        label: "Lorem ipsum dolor"
      }
    }
  ])
  const row2BarList = ref([
    {
      myBeginDate: "2023-07-13 00:00",
      myEndDate: "2024-07-18 00:00",
      ganttBarConfig: {
        id: "another-unique-id-2",
        hasHandles: true,
        label: "Hey, look at me",
        style: {
          // arbitrary CSS styling for your bar
          background: "#e09b69",
          borderRadius: "20px",
          color: "black"
        }
      }
    }
  ])
  </script>