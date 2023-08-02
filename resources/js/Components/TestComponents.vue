<template>
    <div class="row justify-content-center">
        <ul>
        <li v-for="test in tests" :key="test.id">{{test.title}}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props:["tests"]
}
</script>