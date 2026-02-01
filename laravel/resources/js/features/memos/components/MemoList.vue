<script setup lang="ts">
import MemoItem from "./MemoItem.vue";
import type { Memo } from "../apis/memoRepository";

const props = defineProps<{
    memos: Memo[];
}>();

const emit = defineEmits<{
    (e: "delete", id: number): void;
}>();
</script>

<template>
    <ul class="memo-list">
        <MemoItem
            v-for="m in props.memos"
            :key="m.id"
            :memo="m"
            @delete="emit('delete', $event)"
        />
    </ul>
</template>

<style scoped>
.memo-list {
    list-style: none;
    padding: 0;
    display: grid;
    gap: 10px;
}
</style>
