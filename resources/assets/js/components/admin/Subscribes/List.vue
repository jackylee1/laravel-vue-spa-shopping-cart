<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-form :inline="true" :model="formSearch" class="ds-query-form">
            <el-form-item label="Текст запроса">
                <el-input v-model="formSearch.q" placeholder="Поиск по E-Mail"></el-input>
            </el-form-item>
            <el-form-item label="Только новые" prop="status">
                <el-select
                        v-model="formSearch.only_new"
                        placeholder="Только новые">
                    <el-option
                            v-for="item in this.selectBoolean"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmitSearch">
                    <i class="el-icon-search"></i> Поиск
                </el-button>
            </el-form-item>
        </el-form>

        <el-table
                v-loading="loading"
                :data="subscribes"
                style="width: 100%">
            <el-table-column
                    fixed
                    label="ID"
                    min-width="50">
                <template slot-scope="props">
                    {{props.row.id}} <template v-if="!props.row.read_status"><span style="color: red;">NEW!</span></template>
                </template>
            </el-table-column>
            <el-table-column
                    prop="email"
                    label="E-Mail"
                    min-width="270">
            </el-table-column>
            <el-table-column type="expand">
                <template slot-scope="props">
                    <p>Дата создания: {{ props.row.created_at }}</p>
                </template>
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="Управление"
                    min-width="150">
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
                                @click.native.prevent="btnDeleteSubscribe(props.$index, subscribes)">
                            <i class="el-icon-delete"></i>
                        </el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>

        <PageElementsPagination :total="total"
                                :currentPage="currentPage"
                                :pageSize="pageSize"
                                v-on:change="handleCurrentPageChange"/>

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
                    <el-button type="primary" @click="deleteSubscribe">Подтверждаю</el-button>
                </el-button-group>
            </span>
        </el-dialog>

    </div>
</template>

<script>
    import * as ApiSubscribes from '../../../app/admin/api/Subscribes';
    import * as helperRouter from '../../../app/helpers/router';

    import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'subscribes-list',
        mounted () {
            this.formSearch = this.searchData;
            this.oldFormSearch = _.cloneDeep(this.formSearch);

            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'subscribes-list'}).href, title: this.$router.currentRoute.meta.title},
            ];
            if (this.subscribesStore.data !== undefined
                && this.subscribesStore.data.length) {
                this.subscribes = this.subscribesStore.data;
                this.total = this.subscribesStore.total;
                this.currentPage = this.subscribesStore.current_page;
                this.pageSize = this.subscribesStore.per_page;

                helperRouter.updatePage(this.$router, this.currentPage);

                this.loading = false;

                return false;
            }

            let page = helperRouter.currentPage(this.$router);
            this.getSubscribes((page !== undefined) ? page : 1);
        },
        data() {
            return {
                subscribes: [],
                currentPage: 0,
                total: 0,
                pageSize: 0,
                breadcrumbElements: [],
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnSubscribe: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
                formSearch: {
                    q: '',
                    only_new: 0,
                },
                oldFormSearch: null,
            }
        },
        computed: {
            searchData: function () {
                return this.$store.getters.searchSubscribes;
            },
            subscribesStore: function () {
                return this.$store.getters.subscribes;
            },
            selectBoolean: function () {
                return this.$store.getters.selectDataBoolean;
            },
        },
        methods: {
            onSubmitSearch: function () {
                this.getSubscribes();
                this.oldFormSearch = this.formSearch;
                this.$store.commit('updateSearchSubscribes', this.formSearch);
            },
            deleteSubscribe: function () {
                if (this.operationsOnSubscribe) {
                    ApiSubscribes.destroy(this.operationsOnSubscribe.id).then((response) => {
                        let subscribes = this.subscribesStore;
                        let index = subscribes.data.findIndex((item) => item.id === this.operationsOnSubscribe.id);
                        subscribes.data.splice(index, 1);
                        this.$store.commit('updateSubscribes', subscribes);
                        this.dialogVisible = false;
                        if (!this.operationsOnSubscribe.read_status) {
                            this.$store.commit('updateNewSubscribes', this.$store.getters.newSubscribes - 1);
                        }
                        this.operationsOnSubscribe = null;
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
                        this.operationsOnSubscribe = null;
                    });
                }
            },
            btnDeleteSubscribe: function (index, subscribes) {
                this.operationsOnSubscribe = subscribes[index];
                this.titleDialog = 'Удаление записи';
                this.titleAlert = `Вы дейстительно хотите удалить запись с ID: ${this.operationsOnSubscribe.id}?`;
                this.dialogVisible = true;
            },
            getSubscribes: function (page = 1) {
                this.loading = true;
                return ApiSubscribes.get(page, this.formSearch).then((response) => {
                    this.subscribes = response.data.subscribes.data;
                    this.total = response.data.subscribes.total;
                    this.currentPage = response.data.subscribes.current_page;
                    this.pageSize = response.data.subscribes.per_page;
                    this.$store.commit('updateSubscribes', response.data.subscribes);

                    helperRouter.updatePage(this.$router, this.currentPage);

                    this.loading = false;
                });
            },
            handleCurrentPageChange: function (page) {
                this.getSubscribes(page);
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'subscribes-update', params: {id: id}})
            }
        },
        components: {
            PageElementsPagination,
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
        beforeDestroy() {
            this.$store.commit('updateSearchSubscribes', this.oldFormSearch);
        }
    }
</script>