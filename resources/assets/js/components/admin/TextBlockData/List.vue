<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="textBlockData"
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
            <el-table-column label="Тип">
                <template slot-scope="props">
                    {{getType(props.row.type).label}}
                </template>
            </el-table-column>
            <el-table-column label="Добавлен в заголовок">
                <template slot-scope="props">
                    {{getTitle(props.row.text_block_title_id).title}}
                </template>
            </el-table-column>
            <el-table-column
                    prop="sorting_order"
                    label="Порядок сорт.">
            </el-table-column>
            <el-table-column
                    fixed="right">
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
                                @click.native.prevent="btnDeleteModel(props.$index, textBlockData)">
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
    import * as ApiTextBlockData from '../../../app/admin/api/TextBlockData';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'text-block-data-list',
        mounted () {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'text-block-data-list'}).href, title: this.$router.currentRoute.meta.title},
            ];

            this.textBlockTypes = this.textBlockDataTypes;

            if (this.textBlockTitlesStore.length) {
                this.textBlockTitles = this.textBlockTitlesStore;
            }
            else {
                this.getTextBlockTitles();
            }

            if (this.textBlockDataStore.length) {
                this.textBlockData = this.textBlockDataStore;

                this.loading = false;

                return true;
            }
            this.getTextBlockData();
        },
        data() {
            return {
                textBlockTitles: [],
                textBlockData: [],
                textBlockTypes: [],
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
            textBlockDataTypes: function () {
                return this.$store.getters.selectDataTextBlockDataTypes;
            },
            textBlockTitlesStore: function () {
                return this.$store.getters.textBlockTitles;
            },
            textBlockDataStore: function () {
                return this.$store.getters.textBlockData;
            }
        },
        methods: {
            getType: function (value) {
                return this.textBlockTypes.find((item) => item.value === value);
            },
            getTitle: function (titleId) {
                return this.textBlockTitles.find((item) => item.id === titleId);
            },
            deleteModel: function () {
                if (this.operationsOnModel) {
                    ApiTextBlockData.destroy(this.operationsOnModel.id).then((response) => {
                        let textBlockData = this.textBlockDataStore;
                        let index = textBlockData.findIndex((item) => item.id === this.operationsOnModel.id);
                        textBlockData.splice(index, 1);
                        this.$store.commit('updateTextBlockData', textBlockData);
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
                    this.loading = false;
                    this.textBlockTitles = response.data.text_block_titles;
                    this.$store.commit('updateTextBlockTitles', this.textBlockTitles);
                });
            },
            getTextBlockData: function () {
                this.loading = true;
                return ApiTextBlockData.get().then((response) => {
                    this.loading = false;
                    this.textBlockData = response.data.text_block_data;
                    this.$store.commit('updateTextBlockData', this.textBlockData);
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'text-block-data-update', params: {id: id}})
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
    }
</script>