<template>
    <div>
        <section class="checkout_form sf_form ">
            <div class="container">
                <Errors  style="margin-top: 10px" :type="typeAlerts"
                         v-on:clearAlerts="clearAlerts"
                         :alerts="alerts"/>

                <div class="row checkout_login">
                    <Sidebar/>

                    <div class="col-md-8 col-lg-9 ">
                        <section v-if="order !== null && order !== undefined" class="cart cart_sf">
                            <h3>Заказ №{{order.id}}</h3>
                            <div class="container">
                                <template v-if="order.user_surname !== null || order.user_name !== null || order.user_patronymic !== null">
                                    <p>Заказчик: {{order.user_surname}} {{order.user_name}} {{order.user_patronymic}}</p>
                                </template>
                                <p v-if="order.phone !== null">Телефон: {{order.phone}}</p>
                                <p v-if="order.email !== null">E-Mail: {{order.email}}</p>
                                <p v-if="order.payment_method !== null">Способ оплаты: {{order.payment_method.name}}</p>
                                <template v-if="order.delivery_method === 1">
                                    <p>Способ доставки:
                                        <template v-if="order.delivery_method === 1">
                                            Новая почта
                                        </template>
                                    </p>
                                    <p v-if="order.np_area !== undefined && order.np_area !== null">
                                        Область: <span class="delivery_data">{{order.np_area.description}}</span>
                                    </p>

                                    <p v-if="order.np_city !== undefined && order.np_city !== null">
                                        Населенный пункт: <span class="delivery_data">{{order.np_city.description}}</span>
                                    </p>

                                    <p v-if="order.np_warehouse !== undefined && order.np_warehouse !== null">
                                        <span class="delivery_data"> {{order.np_warehouse.description}}</span>
                                    </p>
                                </template>


                                <div class="row cart_description">
                                    <table class="table table-bordered" style="margin-top: 10px;">
                                        <thead>
                                        <tr class="head_table">
                                            <th scope="col">
                                                <h4>Фото</h4>
                                            </th>
                                            <th scope="col">
                                                <h4>Наименование товара</h4>
                                            </th>
                                            <th scope="col">
                                                <h4>Цена</h4>
                                            </th>
                                            <th scope="col">
                                                <h4>Сумма</h4>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="product in order.products">
                                            <th scope="row c_foto">
                                                <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                                                    <img v-if="product.product.main_image !== null"
                                                         :src="`/app/public/images/products/${product.product.main_image.preview}`"
                                                         :alt="product.product.name">
                                                </router-link>
                                            </th>
                                            <td class="lefted description_item">
                                                <router-link class="bold"
                                                             :to="{name: 'product', params: {slug: product.product.slug}}">
                                                    {{product.product.name}}
                                                </router-link>
                                                <template v-for="filter in getAvailable(product.product_available_id, product.product).filters">
                                                    <p v-html="getParentAndSelectFilter(filter.filter_id)"></p>
                                                </template>
                                                <p>Количество: {{product.quantity}}</p>
                                            </td>
                                            <td>
                                                <p>
                                                    <template v-if="product.product_discount_price !== null">
                                                        <strike>{{product.product_price}} грн </strike>
                                                        {{product.product_discount_price}} грн
                                                    </template>
                                                    <template v-else>
                                                        {{product.product_price}} грн
                                                    </template>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <template v-if="product.product_discount_price !== null">
                                                        <strike>{{product.product_price*product.quantity}} грн </strike>
                                                        {{product.product_discount_price*product.quantity}} грн
                                                    </template>
                                                    <template v-else>
                                                        {{product.product_price*product.quantity}} грн
                                                    </template>
                                                </p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-for="product in order.products" class="row cart_description_mobile">
                                    <div class="col-5">
                                        <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                                            <img v-if="product.product.main_image !== null"
                                                 :src="`/app/public/images/products/${product.product.main_image.preview}`"
                                                 :alt="product.product.name">
                                        </router-link>
                                    </div>
                                    <div class="col-7 item_in_cart">
                                        <router-link class="bold"
                                                     :to="{name: 'product', params: {slug: product.product.slug}}">
                                            {{product.product.name}}
                                        </router-link>
                                        <template v-for="filter in getAvailable(product.product_available_id, product.product).filters">
                                            <p v-html="getParentAndSelectFilter(filter.filter_id)"></p>
                                        </template>
                                        <p>Количество: {{product.quantity}}</p>
                                        <p>Цена:
                                            <template v-if="product.product_discount_price !== null">
                                                <strike>{{product.product_price}} грн </strike>
                                                {{product.product_discount_price}} грн
                                            </template>
                                            <template v-else>
                                                {{product.product_price}} грн
                                            </template>
                                        </p>
                                        <p>Сумма:
                                            <template v-if="product.product_discount_price !== null">
                                                <strike>{{product.product_price*product.quantity}} грн </strike>
                                                {{product.product_discount_price*product.quantity}} грн
                                            </template>
                                            <template v-else>
                                                {{product.product_price*product.quantity}} грн
                                            </template>
                                        </p>
                                    </div>
                                </div>

                                <div class="row lets_checkout">
                                    <div class="col-12">
                                        <p class="items_sum_text">
                                            ТОВАРОВ НА СУММУ:
                                            <span class="items_sum_numb">{{order.total_price}} грн.</span>
                                        </p>
                                        <p class="items_sum_text"
                                           v-if="order.total_discount_price !== null && order.total_price !== order.total_discount_price">
                                            С УЧЕТОМ СКИДКИ ЗА ГРУППУ И(ИЛИ) ПРОМО-КОД:
                                            <span class="items_sum_numb">{{order.total_discount_price}} грн.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <h3>История изменения статуса заказа</h3>
                            <div v-for="status in order.history_statuses" class="row status">
                                <span>{{getDateTimeString(status.created_at)}}</span>
                                <span>Статус: <span :style="`color: ${status.status.color}`">{{status.status.name}}</span> </span>
                            </div>
                            <div class="form-group row invers-flex">
                                <div class="next_button">
                                    <a @click="pageOrders"
                                       href="javascript:void(0)"
                                       class="submit_account">
                                        <i class="fas fa-angle-left"></i> Назад
                                    </a>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import * as ApiOrder from '../../../app/public/api/Order';
    import mixinDate from '../../../app/public/mixins/Date';
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import mixinCart from '../../../app/public/mixins/Cart';

    import Sidebar from "./Sidebar";
    import Errors from "../Errors";

    export default {
        name: 'ViewOrder',
        mixins: [mixinAlerts, mixinDate, mixinCart],
        components: {Errors, Sidebar},
        computed: {
            orders: function () {
                return this.$store.getters.orders;
            }
        },
        mounted() {
            if (this.orders.data !== undefined) {
                this.order = this.orders.data.find((item) => item.id === this.$router.currentRoute.params.id);
                if (this.order !== undefined) {
                    this.setMetaTags();
                }
                if (this.order === undefined) {
                    this.getOrder();
                }
            }
            else if (this.order === null)  {
                this.getOrder();
            }
        },
        methods: {
            setMetaTags: function () {
                this.mTitle = '';
                this.mTitle = `Заказ №${this.order.id}`;
            },
            pageOrders: function () {
                this.$router.push({ name: 'list_orders' });
            },
            getOrder: function () {
                ApiOrder.view({
                    order_id: this.$router.currentRoute.params.id
                }).then((res) => {
                    if (res.data.status === 'success') {
                        this.order = res.data.order;
                        this.setMetaTags();
                    }
                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка',
                        text: 'при выполнеении запроса'
                    });
                });
            }
        },
        data() {
            return {
                order: null,
                mTitle: ''
            }
        },
        metaInfo() {
            return {
                title: `| ${this.mTitle}`
            }
        }
    }
</script>