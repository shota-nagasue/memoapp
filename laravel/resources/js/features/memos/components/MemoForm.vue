<script setup lang="ts">
import { ref } from "vue";

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

function onSubmit() {
    emit("submit", { title: title.value, content: content.value });
}

function clear() {
    title.value = "";
    content.value = "";
}

// 親が成功したら空にしたいので expose しておく（任意だけど便利）
defineExpose({ clear });
</script>

<template>
    <form @submit.prevent="onSubmit" class="memo-form">
        <input v-model="title" placeholder="タイトル" />

        <div v-if="props.validationErrors?.title?.length" class="error">
            <div v-for="(msg, i) in props.validationErrors.title" :key="i">{{ msg }}</div>
        </div>

        <textarea v-model="content" rows="3" placeholder="本文" />

        <div v-if="props.validationErrors?.content?.length" class="error">
            <div v-for="(msg, i) in props.validationErrors.content" :key="i">{{ msg }}</div>
        </div>

        <button type="submit" :disabled="props.loading">追加</button>
    </form>
</template>

<style scoped>
.memo-form { display: grid; gap: 8px; }
input, textarea {
    padding: 8px;
    border: 1px solid orange;   /* ← 枠線 */
    border-radius: 6px;       /* ← 角を少し丸く */
    font-size: 14px;}
.error { color: #d00; font-size: 0.9em; }
button { padding: 8px 12px; }
</style>
