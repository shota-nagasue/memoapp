<!-- laravel/resources/js/features/memos/components/MemoItem.vue -->
<script setup lang="ts">
import type { Memo } from "../apis/memoRepository";

const props = defineProps<{
    memo: Memo;
    deleting?: boolean;
}>();

const emit = defineEmits<{
    (e: "delete", id: number): void;
}>();
</script>

<template>
    <li class="memo-item">
        <div class="body">
            <div v-if="props.memo.title" class="title">{{ props.memo.title }}</div>
            <div class="content">{{ props.memo.content }}</div>
        </div>

        <button
            type="button"
            class="delete"
            :disabled="props.deleting"
            @click="emit('delete', props.memo.id)"
        >
            削除
        </button>
    </li>
</template>

<style scoped>
.memo-item {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.body {
    flex: 1;
}
.title {
    font-weight: 600;
    margin-bottom: 4px;
}
.content {
    white-space: pre-wrap;
}
.delete {
    padding: 6px 10px;
}
</style>
