<!-- laravel/resources/js/pages/index.vue -->
<script setup lang="ts">
import { onMounted, ref } from "vue";
import MemoForm from "../features/memos/components/MemoForm.vue";
import MemoList from "../features/memos/components/MemoList.vue";
import {
    memoRepository,
    type Memo,
    type ValidationErrors,
} from "../features/memos/apis/memoRepository";

// ===== state =====
const memos = ref<Memo[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);
const validationErrors = ref<ValidationErrors>({});

// ===== MemoForm expose 型 =====
type MemoFormExposed = {
    clear: () => void;
};

// MemoForm 参照
const formRef = ref<MemoFormExposed | null>(null);

// ===== 無限fetch防止用ガード =====
const didFetchOnce = ref(false);

// ===== API =====
async function fetchMemos() {
    loading.value = true;
    error.value = null;

    try {
        memos.value = await memoRepository.list();
    } catch (e: any) {
        error.value = e?.message ?? "fetch error";
    } finally {
        loading.value = false;
    }
}

async function handleCreate(payload: { title: string; content: string }) {
    error.value = null;
    validationErrors.value = {};

    try {
        const created = await memoRepository.create({
            title: payload.title || undefined,
            content: payload.content,
        });

        // 先頭に追加
        memos.value.unshift(created);

        // フォームをクリア
        (formRef.value as any)?.clear?.();

    } catch (e: any) {
        if (e?.status === 422) {
            validationErrors.value = e?.errors ?? {};
            return;
        }
        error.value = e?.message ?? "create error";
    }
}

async function handleDelete(id: number) {
    error.value = null;

    try {
        await memoRepository.delete(id);
        memos.value = memos.value.filter((m) => m.id !== id);
    } catch (e: any) {
        error.value = e?.message ?? "delete error";
    }
}

// ===== lifecycle =====
onMounted(() => {
    if (didFetchOnce.value) return;
    didFetchOnce.value = true;
    fetchMemos();
});
</script>

<template>
    <div class="page">
        <h1>メモ</h1>

        <p v-if="error" class="error">{{ error }}</p>

        <MemoForm
            ref="formRef"
            :loading="loading"
            :validationErrors="validationErrors"
            @submit="handleCreate"
        />

        <p v-if="loading" class="loading">読み込み中...</p>

        <MemoList :memos="memos" @delete="handleDelete" />
    </div>
</template>

<style scoped>
.page {
    max-width: 720px;
    margin: 0 auto;
    padding: 16px;
    display: grid;
    gap: 12px;
}

.error {
    color: #d00;
}

.loading {
    opacity: 0.7;
}
</style>
