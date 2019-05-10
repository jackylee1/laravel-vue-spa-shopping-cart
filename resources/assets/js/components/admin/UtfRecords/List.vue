<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="utfRecords"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    width="50">
            </el-table-column>
            <el-table-column
                    prop="description"
                    label="Описание">
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
                                @click.native.prevent="btnDeleteModel(props.$index, utfRecords)">
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
    import * as ApiUtfRecords from '../../../app/admin/api/UtfRecords';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'utf-records-list',
        mounted () {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'utf-records-list'}).href, title: this.$router.currentRoute.meta.title},
            ];
            if (this.utfRecordsStore.length) {
                this.utfRecords = this.utfRecordsStore;

                this.loading = false;

                return true;
            }
            this.getTextBlockTitles();
        },
        data() {
            return {
                utfRecords: [],
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
            utfRecordsStore: function () {
                return this.$store.getters.utfRecords;
            }
        },
        methods: {
            deleteModel: function () {
                if (this.operationsOnModel) {
                    ApiUtfRecords.destroy(this.operationsOnModel.id).then((response) => {
                        let utfRecords = this.utfRecordsStore;
                        let index = utfRecords.findIndex((item) => item.id === this.operationsOnModel.id);
                        utfRecords.splice(index, 1);
                        this.$store.commit('updateTextBlockTitles', utfRecords);
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
                        this.typeAlerts = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnModel = null;
                    });
                }
            },
            btnDeleteModel: function (index, sliders) {
                this.operationsOnModel = sliders[index];
                this.titleDialog = 'Удаление записи';
                this.titleAlert = `Вы дейстительно хотите удалить запись: ${this.operationsOnModel.description} (ID: ${this.operationsOnModel.id})?`;
                this.dialogVisible = true;
            },
            getTextBlockTitles: function () {
                this.loading = true;
                return ApiUtfRecords.get().then((response) => {
                    this.utfRecords = response.data.utf_records;
                    this.$store.commit('updateUtfRecords', this.utfRecords);

                    this.loading = false;
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'utf-records-update', params: {id: id}})
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
    }
</script>