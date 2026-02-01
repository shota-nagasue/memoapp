import { createRouter, createWebHistory } from "vue-router";
import { routes } from "vue-router/auto-routes";

// ① ルート定義の中身を起動時に確認（重要）
console.log(
    "[routes]",
    routes.map((r: any) => ({
        path: r.path,
        name: r.name,
        redirect: r.redirect,
        // childrenがある場合だけ表示
        children: r.children?.map((c: any) => ({ path: c.path, name: c.name, redirect: c.redirect })),
    }))
);

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ② ルート遷移が起きるたびにログ
router.beforeEach((to, from) => {
    console.log("[beforeEach]", from.fullPath, "->", to.fullPath);
    return true;
});

router.afterEach((to, from) => {
    console.log("[afterEach ]", from.fullPath, "->", to.fullPath);
});

export default router;
