<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="orderStatuses"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    min-width="50">
            </el-table-column>
            <el-table-column
                    label="Наименование"
                    min-width="100">
                <template slot-scope="props">
                    <p :style="`color: ${props.row.color}`">{{props.row.name}}</p>
                </template>
            </el-table-column>
            <el-table-column
                    label="По умолчанию"
                    min-width="50">
                <template slot-scope="props">
                    <template v-if="props.row.default">Да</template>
                    <template v-else>Нет</template>
                </template>
            </el-table-column>
            <el-table-column
                    prop="sorting_order"
                    label="Порядок сорт."
                    min-width="50">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    min-width="70">
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
                                @click.native.prevent="btnDeleteOrderStatus(props.$index, orderStatuses)">
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
                    <el-button type="primary" @click="deleteOrderStatus">Подтверждаю</el-button>
                </el-button-group>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as ApiOrderStatuses from '../../../app/admin/api/OrderStatuses';
    import * as helperRouter from '../../../app/helpers/router';
    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'order-statuses-list',
        mounted () {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'order-statuses-list'}).href, title: this.$router.currentRoute.meta.title},
            ];

            if (this.orderStatusesInStore.length) {
                this.orderStatuses = this.orderStatusesInStore;
                this.loading = false;

                return false;
            }
            this.getOrderStatuses();
        },
        computed: {
            orderStatusesInStore: function() {
                return this.$store.getters.orderStatuses;
            }
        },
        data() {
            return {
                orderStatuses: [],
                breadcrumbElements: [],
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnOrderStatuses: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
            }
        },
        methods: {
            deleteOrderStatus: function () {
                if (this.operationsOnOrderStatuses) {
                    ApiOrderStatuses.destroy(this.operationsOnOrderStatuses.id).then((response) => {
                        let orderStatuses = this.orderStatusesInStore;
                        let index = orderStatuses.findIndex((item) => item.id === this.operationsOnOrderStatuses.id);
                        orderStatuses.splice(index, 1);
                        this.$store.commit('updateOrderStatuses', orderStatuses);
                        this.dialogVisible = false;
                        this.operationsOnOrderStatuses = null;
                        this.$notify.success({
                            offset: 50,
                            title: 'Удаление статуса заказа',
                            message: response.data.message
                        });
                    }).catch((error) => {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'при удалении статуса заказа'
                        });
                        this.typeAlert = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnOrderStatuses = null;
                    });
                }
            },
            btnDeleteOrderStatus: function (index, group) {
                this.operationsOnOrderStatuses = group[index];
                this.titleDialog = 'Удаление статуса заказа';
                this.titleAlert = `Вы дейстительно хотите удалить статус "${this.operationsOnOrderStatuses.name}"?`;
                this.dialogVisible = true;
            },
            getOrderStatuses: function () {
                this.loading = true;
                return ApiOrderStatuses.get().then((response) => {
                    this.orderStatuses = response.data.order_statuses;
                    this.$store.commit('updateOrderStatuses', response.data.order_statuses);
                    this.loading = false;
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'order-statuses-update', params: {id: id}})
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        }
    }
</script>