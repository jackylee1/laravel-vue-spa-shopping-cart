<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="orderPaymentMethods"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    min-width="50">
            </el-table-column>
            <el-table-column
                    label="Наименование"
                    prop="name"
                    min-width="100">
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
                                @click.native.prevent="btnDeleteOrderPaymentMethod(props.$index, orderPaymentMethods)">
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
                    <el-button type="primary" @click="deleteOrderPaymentMethod">Подтверждаю</el-button>
                </el-button-group>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as ApiOrderPaymentMethods from '../../../app/admin/api/OrderPaymentMethods';
    import * as helperRouter from '../../../app/helpers/router';
    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'order-statuses-list',
        mounted () {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'order-payment-methods-list'}).href, title: this.$router.currentRoute.meta.title},
            ];

            if (this.orderPaymentMethodsInStore.length) {
                this.orderPaymentMethods = this.orderPaymentMethodsInStore;
                this.loading = false;

                return false;
            }
            this.getOrderPaymentMethods();
        },
        computed: {
            orderPaymentMethodsInStore: function() {
                return this.$store.getters.orderPaymentMethods;
            }
        },
        data() {
            return {
                orderPaymentMethods: [],
                breadcrumbElements: [],
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnOrderPaymentMethods: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
            }
        },
        methods: {
            deleteOrderPaymentMethod: function () {
                if (this.operationsOnOrderPaymentMethods) {
                    ApiOrderPaymentMethods.destroy(this.operationsOnOrderPaymentMethods.id).then((response) => {
                        let orderPaymentMethods = this.orderPaymentMethodsInStore;
                        let index = orderPaymentMethods.findIndex((item) => item.id === this.operationsOnOrderPaymentMethods.id);
                        orderPaymentMethods.splice(index, 1);
                        this.$store.commit('updateOrderPaymentMethods', orderPaymentMethods);
                        this.dialogVisible = false;
                        this.operationsOnOrderPaymentMethods = null;
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
                        this.typeAlerts = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnOrderPaymentMethods = null;
                    });
                }
            },
            btnDeleteOrderPaymentMethod: function (index, group) {
                this.operationsOnOrderPaymentMethods = group[index];
                this.titleDialog = 'Удаление статуса заказа';
                this.titleAlert = `Вы дейстительно хотите удалить статус "${this.operationsOnOrderPaymentMethods.name}"?`;
                this.dialogVisible = true;
            },
            getOrderPaymentMethods: function () {
                this.loading = true;
                return ApiOrderPaymentMethods.get().then((response) => {
                    this.orderPaymentMethods = response.data.order_payment_methods;
                    this.$store.commit('updateOrderPaymentMethods', response.data.order_payment_methods);
                    this.loading = false;
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'order-payment-methods-update', params: {id: id}})
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        }
    }
</script>