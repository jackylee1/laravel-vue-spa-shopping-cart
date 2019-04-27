<template>
    <div>
        <Breadcrumbs :items="breadcrumbs"/>

        <section class="checkout_form">
            <div class="container" v-if="cart !== null">
                <div class="row checkout_titles">
                    <div class="col-12 lefted">
                        <h1>Подтверждение заказа</h1>
                    </div>
                </div>

                <Errors  style="margin-top: 10px" :type="typeAlerts"
                         v-on:clearAlerts="clearAlerts"
                         :alerts="alerts"/>

                <div class="row checkout_data">
                    <InformationCart :cart="cart"/>

                    <div class="col-sm-6 col-12 checkout_items righted">
                        <p v-if="totalPrice > 0">
                            Сумма заказа:
                            <span class="delivery_data">{{totalPrice}} грн</span>
                        </p>
                        <p v-if="totalPriceDiscount > 0">
                            Сумма с учетом пользовательских скидок:
                            <span class="delivery_data">{{totalPriceDiscount}} грн</span>
                        </p>

                        <div class="promo" style="padding-left: 0%;">
                            <form class="card" action="javascript:void(0)">
                                <div class="input-group">
                                    <input v-model="code"
                                           name="code"
                                           type="text"
                                           class="form-control"
                                           placeholder="Введите промо-код">

                                    <div class="input-group-append">
                                        <button v-if="cart.user_promotional_code_id === null"
                                                @click="updateCode(false)"
                                                class="btn btn-secondary">
                                            Подтвердить
                                        </button>
                                        <button v-else
                                                data-toggle="tooltip" data-placement="top"
                                                title="Открепить промо-код"
                                                @click="updateCode(true)" class="btn btn-danger">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <ConfirmProducts :cart="cart"/>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import mixinCart from '../../../app/public/mixins/Cart';
    import Breadcrumbs from "../Breadcrumbs";
    import InformationCart from "./InformationCart";
    import ConfirmProducts from "./ConfirmProducts";
    import * as ApiCart from '../../../app/public/api/Cart';
    import Errors from "../Errors";
    import mixinAlerts from '../../../app/public/mixins/Alerts';

    export default {
        name: 'Confirm',
        mixins: [mixinCart, mixinAlerts],
        components: {Errors, ConfirmProducts, InformationCart, Breadcrumbs},
        mounted() {
            this.$scrollTo('#top_line', 650);

            this.breadcrumbs.push({title: 'Корзина', route_name: 'cart'});
            this.breadcrumbs.push({title: 'Оформление и ввод данных', route_name: 'checkout'});
            this.breadcrumbs.push({title: 'Подтвержжение заказа'});

            if (this.cart !== null && this.cart.products.length === 0) {
                this.$router.push({name: 'cart'});
            }
        },
        data() {
            return {
                breadcrumbs: [],
                code: ''
            }
        },
        methods: {
            updateCode: function (clear = false) {
                if (clear) {
                    this.code = null;
                }
                ApiCart.updatePromotionalCode({
                    cart_key: this.cart.key,
                    code: this.code
                }).then((res) => {
                    if (res.data.status === 'success') {
                        this.$store.commit('updateCart', res.data.cart);

                        this.code = null;

                        this.$notify({
                            type: 'success',
                            title: 'Запрос',
                            text: 'успешно выполнен'
                        });
                    }
                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.typeAlerts = 'danger';
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка',
                        text: 'при выполнеении запроса'
                    });
                });
            }
        },
        watch: {
            'cart': function (cart) {
                if (cart.products.length === 0) {
                    this.$router.push({name: 'cart'});
                }
            }
        },
        metaInfo: {
            title: '| Корзина | Оформление и ввод данных | Подтвержжение заказа'
        }
    }
</script>