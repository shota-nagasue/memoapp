<script setup lang="ts">
import { onMounted, ref } from "vue";

type Memo = {
    id: number;
    title: string;
    content: string;
};

const memos = ref<Memo[]>([]);

const title = ref("");
const content = ref("");
const loading = ref(false);
const error = ref<string | null>(null);

async function fetchMemos() {
    loading.value = true;
    error.value = null;

    try {
        const res = await fetch("/api/memos");
        if (!res.ok) {
            throw new Error(`GET /api/memos failed: ${res.status}`);
        }
        memos.value = await res.json(); // ← ここ重要
    } catch (e: any) {
        error.value = e?.message ?? "fetch error";
    } finally {
        loading.value = false;
    }
}

async function createMemo() {
    if (!title.value.trim() || !content.value.trim()) return;

    loading.value = true;
    error.value = null;

    try {
        const res = await fetch("/api/memos", {
            method: "POST",
            headers: { "Content-Type": "application/json" }, // ← header → headers
            body: JSON.stringify({
                title: title.value,
                content: content.value,
            }),
        });

        if (!res.ok) {
            throw new Error(`POST /api/memos failed: ${res.status}`);
        }

        const created: Memo = await res.json();

        const last = memos.value[memos.value.length - 1];
        const nextId = (last?.id ?? 0) + 1;

        memos.value.push({
            id: nextId,
            title: created.title,
            content: created.content,
        });

        title.value = "";
        content.value = "";
    } catch (e: any) {
        error.value = e?.message ?? "create error";
    } finally {
        loading.value = false;
    }
}

async function deleteMemo(id: number) {
    loading.value = true;
    error.value = null;
    try{
        const res = await fetch(`/api/memos/${id}`,{method:"DELETE"});
        if (!res.ok) throw new Error(`DELETE /api/memos/${id} failed: ${res.status}`)

        memos.value = memos.value.filter((m)=>m.id !== id);
    }catch(e: any){
        error.value = e?.message ?? "delete error";
    }finally{
        loading.value=false;

    }
    memos.value = memos.value.filter((m) => m.id !== id);
}

onMounted(fetchMemos);
</script>


<template>
    <div class="mx-auto max-w-2xl px-4 py-6">
        <header class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight">メモ</h1>
            <p class="mt-1 text-sm text-gray-500">GET/POST/DELETEの疎通確認が目的。</p>

        </header>
        <div v-if="error" class="mb-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ error }}
        </div>

        <form @submit.prevent="createMemo" class="space-y-3">
            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">タイトル</label>
                <input
                    v-model="title"
                    placeholder="例）場面２のA班の解釈"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm outline-none focus:border-gray-500"
                />
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-700">本文</label>
                <textarea
                    v-model="content"
                    placeholder="例）登場人物の行動理由について、3つの視点から議論をまとめた。～"
                    rows="4"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm outline-none focus:border-gray-500"

                />
            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800"
            >
                追加（API）
            </button>
        </form>

            <section class="mt-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-700">一覧</h2>
                    <button
                        type="button"
                        @click="fetchMemos"
                        :disabled="loading"
                        class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                    >
                        再取得(GET)
                    </button>
                </div>

                <p v-if="loading" class="mt-3 text-sm text-gray-500">読み込み中</p>

                <ul class="mt-3 space-y-3">
                    <li
                    v-for="memo in memos as Memo[]"
                    :key="memo.id"
                    class="rounded-lg border border-gray-200 bg-white p-4"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="truncate text-base font-semibold text-gray-900">
                                    {{ memo.title }}
                                </p>
                                <p class="mt-1 shitespace-pre-wrap text-sm text-gray-700">
                                    {{ memo.content }}
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="deleteMemo(memo.id)"
                                :disabled = "loading"
                                class="shrink-0 rounded-md border border-red-200 bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-100"
                            >
                                削除(API)
                            </button>
                        </div>


                    </li>

                </ul>

                <p v-if="!loading && memos.length ===0" class="mt-4 text-sm text-gray-500">
                    まだメモがありません（GETの戻りが [] なので正常）
                </p>

            </section>

    </div>
</template>
