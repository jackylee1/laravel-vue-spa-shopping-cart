<template>
    <div>
        <section class="checkout_form sf_form ">
            <div class="container">
                <div class="row checkout_login">
                    <Sidebar/>

                    <div class="col-md-8 col-lg-9 wrapper ">
                        <div class="row">
                            <h3>Список заказов</h3>
                        </div>
                        <div class="row">
                            <div class="hostory">
                                <Errors :type="typeAlerts"
                                        v-on:clearAlerts="clearAlerts"
                                        :alerts="alerts"/>

                                <template v-if="orders.length">
                                    <div v-for="order in orders" class="order">
                                        <div class="order_column">
                                            <router-link :to="{ name: 'view_order', params: { id: order.id } }">
                                                <a href="javascript:void(0)">Заказ №{{order.id}}</a>
                                            </router-link>
                                        </div>
                                        <div class="order_column text-center">
                                            {{getDateTimeString(order.created_at)}}
                                        </div>
                                        <div class="order_column text-center">
                                            <template v-if="order.total_discount_price !== null && order.total_discount_price > 0">
                                                <strike style="padding-right: 5px">{{order.total_price}}грн. </strike> {{order.total_discount_price}} грн.
                                            </template>
                                            <template v-else>
                                                {{order.total_price}} грн.
                                            </template>
                                        </div>
                                        <template v-if="order.history_statuses.length">
                                            <div class="order_column text-center" :style="`color: ${getDataStatus(order.history_statuses).color}`">
                                                {{getDataStatus(order.history_statuses).name}}
                                            </div>
                                        </template>
                                    </div>

                                    <Pagination :pagination="pagination"
                                                v-on:pageChange="getOrders"/>
                                </template>
                                <template v-else>
                                    <div class="alert alert-info">
                                        У Вас пока нет заказов
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import * as ApiOrder from '../../../app/public/api/Order';
    import Sidebar from "./Sidebar";
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import mixinDate from '../../../app/public/mixins/Date';
    import Errors from "../Errors";
    import Pagination from "../Pagination";

    export default {
        name: 'ListOrders',
        mixins: [mixinAlerts, mixinDate],
        computed: {
            ordersStore: function () {
                return this.$store.getters.orders;
            }
        },
        mounted() {
            if (!this.$store.getters.isLoggedIn) {
                return this.$router.push({name: '/login'});
            }

            if (this.ordersStore.data !== undefined) {
                this.setOrders(this.ordersStore);
            }
            else if (this.orders.length === 0) {
                this.getOrders((this.$router.currentRoute.query.page !== null) ? this.$router.currentRoute.query.page : 1);
            }
        },
        data() {
            return {
                orders: [],
                pagination: {
                    currentPage: 1,
                    totalPages: 1,
                    count: 1
                },
                isLoading: true,
                isFullPage: true,
            }
        },
        methods: {
            getDataStatus: function (historyStatuses) {
                let status = null;
                if (historyStatuses.length) {
                    status = _.last(historyStatuses).status;
                }
                return status;
            },
            setOrders: function (orders) {
                this.pagination.currentPage = orders.current_page;
                this.pagination.totalPages = orders.last_page;
                this.pagination.count = orders.total;

                this.orders = orders.data;

                this.isLoading = false;
            },
            getOrders: function (page = 1) {
                this.$router.push({ query: Object.assign({}, this.$route.query, { page: page }) });

                ApiOrder.get(page).then((res) => {
                    this.$store.commit('updateOrders', res.data.orders);

                    this.setOrders(res.data.orders);
                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка',
                        text: 'при выполнеении запроса'
                    });
                    this.isLoading = false;
                });
            }
        },
        components: {Pagination, Errors, Sidebar},
        metaInfo: {
            title: '| Кабинет | Список заказов'
        }
    }
</script>