<script setup>
import { computed } from 'vue';

const props = defineProps({
    errors: {
        type: Object,
        default: () => ({}), // デフォルト値として空のオブジェクトを設定
    },
})
const hasErrors = computed(() => Object.keys(props.errors).length > 0);
</script>

<template>
    <div v-if="hasErrors">
        <div class="font-medium text-red-600">入力内容を確認してください！！</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(error, key) in props.errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>