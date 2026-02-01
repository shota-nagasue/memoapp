// laravel/resources/js/features/memos/apis/memoRepository.ts

export type Memo = {
    id: number;
    title?: string;
    content: string;
    created_at?: string;
    updated_at?: string;
};

export type CreateMemoPayload = {
    title?: string;
    content: string;
};

export type ValidationErrors = Record<string, string[]>;

export type ApiValidationError = Error & {
    status: 422;
    errors: ValidationErrors;
};

async function request<T>(input: RequestInfo, init?: RequestInit): Promise<T> {
    // headers は「Headers」で統一して安全にマージする
    const headers = new Headers(init?.headers);

    headers.set("Accept", "application/json");
    headers.set("Content-Type", "application/json");

    const res = await fetch(input, {
        ...init,
        headers,
    });

    if (res.status === 422) {
        const data = await res.json().catch(() => ({}));
        const err = new Error("validation error") as ApiValidationError;
        err.status = 422;
        err.errors = data?.errors ?? {};
        throw err;
    }

    if (!res.ok) {
        const text = await res.text().catch(() => "");
        throw new Error(`${res.status} ${res.statusText} ${text}`.trim());
    }

    if (res.status === 204) return undefined as T;

    return (await res.json()) as T;
}


export const memoRepository = {
    // APIが `[...]` でも `{ data: [...] }` でも動くようにする
    list(): Promise<Memo[]> {
        return request<any>("/api/memos", { method: "GET" }).then((r) => {
            if (Array.isArray(r)) return r as Memo[];
            if (Array.isArray(r?.data)) return r.data as Memo[];
            return [];
        });
    },

    create(payload: CreateMemoPayload): Promise<Memo> {
        return request<Memo>("/api/memos", {
            method: "POST",
            body: JSON.stringify(payload),
        });
    },

    delete(id: number): Promise<void> {
        return request<void>(`/api/memos/${id}`, { method: "DELETE" });
    },
};
