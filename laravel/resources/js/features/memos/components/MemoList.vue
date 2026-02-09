<script setup lang="ts">
import MemoItem from "./MemoItem.vue";
import type { Memo } from "../apis/memoRepository";

const props = defineProps<{ memos: Memo[] }>();

const emit = defineEmits<{
    (e: "delete", id: number): void;
}>();
</script>

<template>
    <p class="text-lg">保存したメモ</p>
    <ul class="space-y-3">
        <MemoItem
            v-for="m in props.memos"
            :key="m.id"
            :memo="m"
            @delete="emit('delete', $event)"
        />
    </ul>
</template>
