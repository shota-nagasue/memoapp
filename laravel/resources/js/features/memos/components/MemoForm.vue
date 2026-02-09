<script setup lang="ts">
import { ref, computed } from "vue";


import SvgTextHeading from "../../../components/texts/SVGTextHeading.vue";
import PlusSvg from "../../../components/svgs/PlusSvg.vue";


type ValidationErrors = Record<string, string[]>;

const props = defineProps<{
    loading?: boolean;
    validationErrors?: ValidationErrors;
}>();

const emit = defineEmits<{
    (e: "submit", payload: { title: string; content: string }): void;
}>();

const title = ref("");
const content = ref("");

const canSubmit = computed(() => title.value.trim().length > 0 && content.value.trim().length > 0);

function onSubmit() {
    // 空は送らない（サンプルの early return）
    if (!canSubmit.value || props.loading) return;
    emit("submit", { title: title.value, content: content.value });
}

function handleKeydown(e: KeyboardEvent) {
    // Enter単体で保存 / Shift+Enterで改行
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        onSubmit();
    }
}

function clear() {
    title.value = "";
    content.value = "";
}

defineExpose({ clear });
</script>

<template>
    <!-- BaseCard(prominent) を埋め込み -->
    <div class="bg-white rounded-xl shadow-lg border border-primary-100 p-6 mb-8">

        <SvgTextHeading :icon="PlusSvg" text="新しいメモ" class="mb-4" />

        <form @submit.prevent="onSubmit" class="space-y-4">
            <div class="space-y-1">
                <label class="text-sm font-medium">タイトル</label>
                <input
                    v-model="title"
                    placeholder="タイトル"
                    class="w-full rounded-lg border border-orange-400 focus:border-transparent bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-200"
                />

                <div v-if="props.validationErrors?.title?.length" class="text-sm text-red-600">
                    <div v-for="(msg, i) in props.validationErrors.title" :key="i">
                        {{ msg }}
                    </div>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium">本文</label>

                <textarea
                    v-model="content"
                    rows="4"
                    aria-describedby="memo-content-description"
                    aria-label="新しいメモの本文"
                    placeholder="本文&#10;（Shift+Enterで改行）"
                    @keydown="handleKeydown"
                    class="w-full rounded-lg border border-orange-400 focus:border-transparent bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-200"
                />

                <div id="memo-content-description" class="sr-only">
                    本文を入力してから保存してください。Enterキーで保存、Shift+Enterで改行できます。
                </div>

                <div v-if="props.validationErrors?.content?.length" class="mt-2 text-sm text-red-600">
                    <div v-for="(msg, i) in props.validationErrors.content" :key="i">
                        {{ msg }}
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="submit"
                    :disabled="props.loading || !canSubmit"
                    class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-medium text-white shadow-sm
                 disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="(props.loading || !canSubmit) ? 'bg-gray-400' : 'bg-primary-500 hover:bg-primary-600'"
                    :aria-label="canSubmit ? 'メモを保存' : 'メモを保存できません（本文を入力してください）'"
                    aria-describedby="memo-content-description"
                >
          <span class="inline-flex items-center ">
            <PlusSvg color="white" />
            {{ props.loading ? "保存中..." : "メモを保存（Enter）" }}
          </span>
                </button>
            </div>
        </form>
    </div>
</template>
