<script setup lang="ts">
import type { Memo } from "../apis/memoRepository";
import TrashSvg from "../../../components/svgs/TrashSvg.vue";

const props = defineProps<{
    memo: Memo;
    deleting?: boolean;
}>();

const emit = defineEmits<{
    (e: "delete", id: number): void;
}>();
</script>

<template>
    <li
        class="bg-white rounded-lg shadow-sm border border-gray-100 p-5
           hover:shadow-md transition-all duration-200 group"
    >
        <div class="flex justify-between items-start gap-4">
            <div class="flex-1 min-w-0">
                <div v-if="props.memo.title" class="text-sm font-semibold text-gray-800 mb-1">
                    {{ props.memo.title }}
                </div>

                <div class="text-gray-800 leading-relaxed whitespace-pre-wrap break-words">
                    {{ props.memo.content }}
                </div>
            </div>

            <button
                type="button"
                class=" group-hover:opacity-100 transition-opacity
               inline-flex items-center justify-center rounded-full px-3 py-1.5 text-xs font-medium
               text-gray-600 hover:text-red-600 hover:bg-red-50
               disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="props.deleting"
                :aria-label="`メモ「${(props.memo.title ?? props.memo.content).slice(0, 20)}...」を削除`"
                @click="emit('delete', props.memo.id)"
            >
                <TrashSvg />
            </button>
        </div>
    </li>
</template>
