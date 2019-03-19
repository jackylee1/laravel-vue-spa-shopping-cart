<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="text_block_titles"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    width="50">
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="Заголовок">
            </el-table-column>
            <el-table-column
                    prop="sorting_order"
                    label="Порядок сортировки">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="Управление">
                <template slot-scope="props">
                    <el-button-group>
                        <el-button
                                @click.native.prevent="goToUpdate(props.row.id)"
                                size="mini">
                            <i class="el-icon-edit"></i>
                        </el-button>
                        <el-button
                                size="mini"
                                type="danger"
                                @click.native.prevent="btnDeleteModel(props.$index, text_block_titles)">
                            <i class="el-icon-delete"></i>
                        </el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>

        <el-dialog
                :title="titleDialog"
                :visible.sync="dialogVisible"
                width="30%">
            <el-alert
                    :title="titleAlert"
                    :type="typeAlert"
                    :closable="false">
            </el-alert>
            <span slot="footer" class="dialog-footer">
                <el-button-group>
                    <el-button @click="dialogVisible = false">Отмена</el-button>
                    <el-button type="primary" @click="deleteModel">Подтверждаю</el-button>
                </el-button-group>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as helperRouter from '../../../app/helpers/router';
    import * as ApiTextBlockTitles from '../../../app/admin/api/TextBlockTitles';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'text-block-titles-list',
        mounted () {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'text-block-titles-list'}).href, title: this.$router.currentRoute.meta.title},
            ];
            if (this.textBlockTitlesStore.length) {
                this.text_block_titles = this.textBlockTitlesStore;

                this.loading = false;

                return true;
            }
            this.getTextBlockTitles();
        },
        data() {
            return {
                text_block_titles: [],
                breadcrumbElements: [],
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnModel: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
            }
        },
        computed: {
            textBlockTitlesStore: function () {
                return this.$store.getters.textBlockTitles;
            }
        },
        methods: {
            deleteModel: function () {
                if (this.operationsOnModel) {
                    ApiTextBlockTitles.destroy(this.operationsOnModel.id).then((response) => {
                        let text_block_titles = this.textBlockTitlesStore;
                        let index = text_block_titles.findIndex((item) => item.id === this.operationsOnModel.id);
                        text_block_titles.splice(index, 1);
                        this.$store.commit('updateTextBlockTitles', text_block_titles);
                        this.dialogVisible = false;
                        this.operationsOnModel = null;
                        this.$notify.success({
                            offset: 50,
                            title: 'Удаление записи',
                            message: response.data.message
                        });
                    }).catch((error) => {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'при удалении записи'
                        });
                        this.typeAlert = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnModel = null;
                    });
                }
            },
            btnDeleteModel: function (index, sliders) {
                this.operationsOnModel = sliders[index];
                this.titleDialog = 'Удаление записи';
                this.titleAlert = `Вы дейстительно хотите удалить запись: ${this.operationsOnModel.title} (ID: ${this.operationsOnModel.id})?`;
                this.dialogVisible = true;
            },
            getTextBlockTitles: function () {
                this.loading = true;
                return ApiTextBlockTitles.get().then((response) => {
                    this.text_block_titles = response.data.text_block_titles;
                    this.$store.commit('updateTextBlockTitles', this.text_block_titles);

                    this.loading = false;
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'text-block-titles-update', params: {id: id}})
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
    }
</script>