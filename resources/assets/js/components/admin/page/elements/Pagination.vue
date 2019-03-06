<template>
    <div>
        <el-pagination v-if="total"
                       class="ds-pagination"
                       background
                       @current-change="handleCurrentPageChange"
                       :current-page.sync="currentPage"
                       :page-size="pageSize"
                       layout="prev, pager, next, jumper"
                       :total="total">
        </el-pagination>
    </div>
</template>

<script>
    export default {
        name: 'page-elements-pagination',
        props: ['total', 'pageSize', 'currentPage'],
        methods: {
            handleCurrentPageChange: function (page) {
                this.$emit('change', page);
            }
        }
    }
</script>