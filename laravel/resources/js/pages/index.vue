<script setup lang="ts">
import { onMounted, ref } from "vue";
import SimpleLayout from "../layouts/SimpleLayout.vue"; // これを追加

import MemoForm from "../features/memos/components/MemoForm.vue";
import MemoList from "../features/memos/components/MemoList.vue";
import {
    memoRepository,
    type Memo,
    type ValidationErrors,
} from "../features/memos/apis/memoRepository";

// 以下ロジックはそのまま
const memos = ref<Memo[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);
const validationErrors = ref<ValidationErrors>({});

type MemoFormExposed = { clear: () => void };
const formRef = ref<MemoFormExposed | null>(null);

const didFetchOnce = ref(false);

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
        memos.value.unshift((created as any).data);
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

onMounted(() => {
    if (didFetchOnce.value) return;
    didFetchOnce.value = true;
    fetchMemos();
});
</script>

<template>
    <SimpleLayout>
        <div class="space-y-4">

            <p v-if="error" class="text-red-600">{{ error }}</p>

            <MemoForm
                ref="formRef"
                :loading="loading"
                :validationErrors="validationErrors"
                @submit="handleCreate"
            />

            <p v-if="loading" class="opacity-70">読み込み中...</p>

            <MemoList :memos="memos" @delete="handleDelete" />
        </div>
    </SimpleLayout>
</template>
