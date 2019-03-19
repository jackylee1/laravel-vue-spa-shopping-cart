<template>
    <div>
        <el-form :inline="true" :model="formSearch" class="ds-query-form">
            <el-form-item label="Текст запроса">
                <el-input v-model="formSearch.q" placeholder="Введите текст запроса"></el-input>
            </el-form-item>
            <el-form-item label="Статус промокода" prop="status">
                <el-select v-model="formSearch.status" placeholder="Выберите статус промокода">
                    <el-option
                            v-for="item in this.listStatuses"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-if="showSwitchUser" label="Промокоды текущего пользователя">
                <el-switch  @change="handleSwitchUserChange"
                            v-model="statusSwitchUser"></el-switch>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmitSearch">
                    <i class="el-icon-search"></i> Поиск
                </el-button>
            </el-form-item>
        </el-form>

        <el-table
                v-loading="loading"
                :data="promotionalCodes"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    min-width="50">
            </el-table-column>
            <el-table-column
                    prop="code"
                    label="Промокод"
                    min-width="150">
            </el-table-column>
            <el-table-column
                    prop="discount"
                    label="Процент скидки"
                    min-width="150">
            </el-table-column>
            <el-table-column
                    label="Статус"
                    min-width="150">
                <template slot-scope="props">
                    <template v-if="props.row.status">
                        <p>Активный</p>
                    </template>
                    <template v-else>
                        <p>Был использован</p>
                    </template>
                </template>
            </el-table-column>

            <el-table-column
                    fixed="right"
                    label="Операции"
                    min-width="150">
                <template slot-scope="props">
                    <el-button-group>
                        <el-button
                                :type="btnSelect(props.row.id) ? 'danger' : 'success'"
                                :icon="btnSelect(props.row.id) ? 'el-icon-close' : 'el-icon-check'" circle
                                @click="selectPromotionalCode(props.row)">
                        </el-button>
                        <template v-if="currentModel === 'users' && btnSelect(props.row.id)">
                            <el-button v-if="btnSend(props.row.id) && props.row.status"
                                       type="success"
                                       icon="el-icon-message" circle
                                       @click="sendPromotionalCode(props.row.id)">
                            </el-button>
                        </template>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>

        <PageElementsPagination :total="total"
                                :currentPage="currentPage"
                                :pageSize="pageSize"
                                v-on:change="handleCurrentPageChange"/>
    </div>
</template>

<script>
    import * as ApiPromotionalCodes from '../../../app/admin/api/PromotionalCodes';

    import { PageElementsPagination, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'promotional-codes-table-select',
        props: ['currentModel', 'model'],
        mounted: function () {
            this.formSearch = this.searchData;
            this.oldFormSearch = _.cloneDeep(this.formSearch);

            if (this.currentModel === 'users') {
                this.showSwitchUser = true;
            }

            if (this.promotionalCodesStore.data !== undefined
                && this.promotionalCodesStore.data.length) {
                this.promotionalCodes = this.promotionalCodesStore.data;
                this.total = this.promotionalCodesStore.total;
                this.currentPage = this.promotionalCodesStore.current_page;
                this.pageSize = this.promotionalCodesStore.per_page;
                this.loading = false;
                return false;
            }
            this.getPromotionalCodes();
        },
        data() {
            return {
                promotionalCodes: [],
                currentPage: 0,
                total: 0,
                pageSize: 0,
                loading: true,
                formSearch: {
                    q: '',
                    status: 'all',
                    user_id: null
                },
                oldFormSearch: null,
                statuses: this.selectListPromotionalCodeStatuses,
                showSwitchUser: false,
                statusSwitchUser: false
            }
        },
        computed: {
            searchData: function () {
                return this.$store.getters.searchPromotionalCodes;
            },
            listStatuses: function () {
                let statuses = this.selectListPromotionalCodeStatuses;
                if (statuses.findIndex((item) => item.value === 'all') === -1) {
                    statuses.unshift({'label': 'Все промокоды', 'value': 'all'});
                }
                return statuses;
            },
            promotionalCodesStore: function () {
                return this.$store.getters.promotionalCodes;
            },
            usersStore: function() {
                return this.$store.getters.users;
            },
            selectListPromotionalCodeStatuses: function () {
                return this.$store.getters.selectDataListPromotionalCodeStatuses;
            }
        },
        methods: {
            sendPromotionalCode: function(codeId) {
                let userPromotionalCode = this.model.promotional_codes.find((code) => code.promotional_code_id === codeId);
                let indexUserPromotionalCode = this.model.promotional_codes.findIndex((code) => code.promotional_code_id === codeId);
                ApiPromotionalCodes.send({
                    user_id: userPromotionalCode.user_id,
                    promotional_code_id: userPromotionalCode.promotional_code_id,
                }).then((response) => {
                    response = response.data;
                    this.$notify.success({
                        offset: 50,
                        title: 'Запрос успешно выполнен',
                        message: response.message
                    });
                    this.model.promotional_codes[indexUserPromotionalCode].send_status_to_email = 1;
                    this.$emit('changeOldForm', this.model);

                    if (this.usersStore.data !== undefined) {
                        let users = this.usersStore;
                        let index = users.data.findIndex((user) => user.id === userPromotionalCode.user_id);
                        let user = users.data[index];
                        user.promotional_codes[indexUserPromotionalCode].send_status_to_email = 1;
                        users.data.splice(index, 1, user);
                        this.$store.commit('updateUsers', users);
                    }
                }).catch((error) => {
                    this.$notify.error({
                        offset: 50,
                        title: 'Ошибка при выполнении запроса',
                        message: error.response.data.errors
                    });
                });
            },
            handleSwitchUserChange: function() {
                this.formSearch.user_id = (this.formSearch.user_id === null) ? this.model.id : null;
            },
            btnSend: function (codeId) {
                let userPromotionalCode = this.model.promotional_codes.find((code) => code.promotional_code_id === codeId);

                return (userPromotionalCode.send_status_to_email === 0);
            },
            btnSelect: function(codeId) {
                let check = null;
                switch (this.currentModel) {
                    case 'users':
                        check = this.model.promotional_codes.find((code) => code.promotional_code_id === codeId);
                        break;
                }
                return (check !== null && check !== undefined);
            },
            selectPromotionalCode: function (promotionalCode) {
                this.$emit('selectPromotionalCode', promotionalCode);
            },
            onSubmitSearch: function () {
                this.getPromotionalCodes();
                this.oldFormSearch = this.formSearch;
                this.$store.commit('updateSearchPromotionalCodes', this.formSearch);
            },

            getPromotionalCodes: function (page = 1) {
                this.loading = true;
                return ApiPromotionalCodes.get(page, this.formSearch).then((response) => {
                    this.promotionalCodes = response.data.promotional_codes.data;
                    this.total = response.data.promotional_codes.total;
                    this.currentPage = response.data.promotional_codes.current_page;
                    this.pageSize = response.data.promotional_codes.per_page;
                    this.$store.commit('updatePromotionalCodes', response.data.promotional_codes);
                    this.loading = false;
                });
            },
            handleCurrentPageChange: function (page) {
                this.getPromotionalCodes(page);
            }
        },
        components: {
            PageElementsPagination,
            PageElementsAlerts
        },
        beforeDestroy() {
            this.$store.commit('updateSearchPromotionalCodes', this.oldFormSearch);
        }
    }
</script>