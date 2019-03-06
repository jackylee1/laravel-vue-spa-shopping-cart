<template>
    <div>
        <el-breadcrumb class="ds-breadcrumb" separator="/">
            <template v-for="item in breadcrumbElements">
                <el-breadcrumb-item :to="{ path: item.href }">{{item.title}}</el-breadcrumb-item>
            </template>
        </el-breadcrumb>
    </div>
</template>

<script>
    export default {
        name: 'page-elements-breadcrumb',
        props: ['breadcrumbElements']
    }
</script>