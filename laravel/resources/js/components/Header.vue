<script setup>
import DocumentSvg from "@/components/svgs/DocumentSvg.vue";
</script>

<template>
    <header class="app-header">
        <div class="app-header_inner">
            <div class="app-header_left">
                <DocumentSvg class="app-header_icon" />
                <div class="app-header_text">
                    <div class="app-header_title">かんたんメモ</div>
                    <div class="app-header_subtitle">思いついたことをサッと記録しよう</div>
                </div>
            </div>
        </div>
    </header>
</template>

<style scoped>
.app-header{
    padding: 12px 16px;
    border-bottom: 1px solid #e5e5e5;

}

.app-header_inner{
    display: flex;
    align-items: center;


}
.app-header_left{
    display: flex;
    align-items: center;
    gap: 10px;
}

.app-header_title{
    font-size: 16px;
    line-height: 1.2;
}
.app-header_subtitle{
    font-size: 12px;
    color: #666;
    line-height: 1.2;
}
</style>
