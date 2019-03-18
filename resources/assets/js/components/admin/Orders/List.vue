<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-table
                v-loading="loading"
                :data="orders"
                style="width: 100%">
            <el-table-column
                    fixed
                    label="ID"
                    min-width="35">
                <template slot-scope="props">
                    {{props.row.id}} <template v-if="!props.row.read_status"><span style="color: red;">NEW!</span></template>
                </template>
            </el-table-column>
            <el-table-column
                    label="Заказчик"
                    min-width="120">
                <template slot-scope="props">
                    <template v-if="props.row.user_surname !== null && props.row.user_name !== null && props.row.user_patronymic !== null">
                        {{props.row.user_surname}}
                        {{props.row.user_name}}
                        {{props.row.user_patronymic}}
                        <br>
                    </template>
                    <template v-if="props.row.email !== null">E-Mail: {{props.row.email}}<br></template>
                    <template v-if="props.row.phone !== null">Телефон: {{props.row.phone}}</template>
                </template>
            </el-table-column>
            <el-table-column
                    prop="total_price"
                    label="Сумма заказа"
                    min-width="80">
            </el-table-column>
            <el-table-column
                    prop="total_discount_price"
                    label="Сумма заказа (с учетом всех скидок)"
                    min-width="100">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="Управление"
                    min-width="70">
                <template slot-scope="props">
                    <el-button
                            @click.native.prevent="goToUpdate(props.row.id)"
                            size="mini">
                        <i class="el-icon-edit"></i>
                    </el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click.native.prevent="btnDeleteOrder(props.$index, orders)">
                        <i class="el-icon-delete"></i>
                    </el-button>
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
                <el-button @click="dialogVisible = false">Отмена</el-button>
                <el-button type="primary" @click="deleteOrder">Подтверждаю</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as ApiOrders from '../../../app/admin/api/Orders';
    import * as helperRouter from '../../../app/helpers/router';
    import { PageElementsBreadcrumb, PageElementsPagination, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'orders-list',
        mounted () {
            if (this.$route.query.update_model !== undefined) {
                this.goToUpdate(this.$route.query.update_model);
            }

            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'orders-list'}).href, title: this.$router.currentRoute.meta.title},
            ];

            if (typeof this.ordersInStore.data !== 'undefined' && this.ordersInStore.data.length) {
                this.orders = this.ordersInStore.data;
                this.total = this.ordersInStore.total;
                this.currentPage = this.ordersInStore.current_page;
                this.pageSize = this.ordersInStore.per_page;
                this.loading = false;

                return false;
            }

            let page = helperRouter.currentPage(this.$router);
            this.getOrders((page !== undefined) ? page : 1);
        },
        computed: {
            ordersInStore: function() {
                return this.$store.getters.orders;
            }
        },
        data() {
            return {
                orders: [],
                breadcrumbElements: [],
                currentPage: 0,
                total: 0,
                pageSize: 0,
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnOrder: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
            }
        },
        methods: {
            deleteOrder: function () {
                if (this.operationsOnOrder) {
                    ApiOrders.destroy(this.operationsOnOrder.id).then((response) => {
                        let order = this.ordersInStore;
                        let index = order.data.findIndex((item) => item.id === this.operationsOnOrder.id);
                        order.data.splice(index, 1);

                        this.$store.commit('updateOrders', order);
                        this.dialogVisible = false;
                        this.operationsOnOrder = null;

                        this.$notify.success({
                            offset: 50,
                            title: 'Удаление заказа',
                            message: response.data.message
                        });
                    }).catch((error) => {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'при удалении заказа'
                        });
                        this.typeAlert = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnOrder = null;
                    });
                }
            },
            btnDeleteOrder: function (index, orders) {
                this.operationsOnOrder = orders[index];
                this.titleDialog = 'Удаление заказа';
                this.titleAlert = `Вы дейстительно хотите удалить заказ с ID "${this.operationsOnOrder.id}"?`;
                this.dialogVisible = true;
            },
            getOrders: function (page = 1) {
                this.loading = true;
                return ApiOrders.get(page).then((response) => {
                    this.orders = response.data.orders.data;
                    this.total = response.data.orders.total;
                    this.currentPage = response.data.orders.current_page;
                    this.pageSize = response.data.orders.per_page;
                    this.$store.commit('updateOrders', response.data.orders);
                    this.loading = false;
                });
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'orders-update', params: {id: id}})
            },
            handleCurrentPageChange: function (page) {
                this.getOrders(page);
            },
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts,
            PageElementsPagination
        }
    }
</script>