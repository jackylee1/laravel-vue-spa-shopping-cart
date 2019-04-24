<template>
    <div class="modal fade" id="modalByInOnOneClick" tabindex="-1" role="dialog" aria-labelledby="modalByInOnOneClick" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 centered">
                            <h3 class="modal_title">Купить товар в один клик</h3>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        Введите свой номер телефона и менеджер свяжется с Вами для оформление заказа на данный товар.
                        <br>
                        Товар будет добавлен в заказ на основе выбранных параметров на страницу просмотра товара.
                    </div>
                    <form data-vv-scope="form-by-in-on-click">
                        <div class="form-group row">
                            <label for="phone" class="col-lg-4 col-form-label">Телефон</label>
                            <div class="col-lg-8">
                                <input type="text"
                                       v-model="phone"
                                       data-vv-as="Телефон"
                                       name="phone"
                                       v-validate="'required'"
                                       class="form-control"
                                       id="phone"
                                       placeholder="Введите Телефон">
                                <small v-show="errors.has('form-by-in-on-click.phone')" class="text-danger">
                                    {{ errors.first('form-by-in-on-click.phone') }}
                                </small>
                            </div>
                        </div>
                    </form>

                    <Errors  style="margin-top: 10px" :type="typeAlerts"
                             v-on:clearAlerts="clearAlerts"
                             :alerts="alerts"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Отмена</button>
                    <button @click="sendOrder"
                            type="button" class="btn btn-danger">Купить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as ApiOrder from '../../../app/public/api/Order';
    import Errors from "../Errors";
    import mixinAlerts from '../../../app/public/mixins/Alerts';

    export default {
        name: 'ModalByInOnOneClick',
        props: ['valueQuantity', 'idAvailable', 'productId'],
        mixins: [mixinAlerts],
        computed: {
            orders: function () {
                return this.$store.getters.orders;
            },
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn;
            }
        },
        data() {
            return {
                phone: null
            }
        },
        methods: {
            sendOrder: function () {
                this.$validator.validateAll('form-by-in-on-click').then((result) => {
                    if (result) {
                        ApiOrder.byInOnOneClick({
                            product_id: this.productId,
                            quantity: this.valueQuantity,
                            available_id: this.idAvailable,
                            phone: this.phone
                        }).then((res) => {
                            if (res.data.status === 'success') {
                                if (this.orders.data !== undefined) {
                                    let order = this.orders;
                                    order.data.unshift(res.data.order);
                                    this.$store.commit('updateOrders', order);
                                }
                                this.phone = null;
                                this.typeAlerts = 'success';
                                this.alerts = 'Заказ успешно создан. В ближайшее время с Вами свяжется менеджер для уточнения данных по заказу';
                                this.$notify({
                                    type: 'success',
                                    title: 'Заказ',
                                    text: 'успешно создан'
                                });
                            }
                        }).catch((error) => {
                            this.typeAlerts = 'danger';
                            this.alerts = error.response.data.errors;
                            this.$notify({
                                type: 'error',
                                title: 'Ошибка',
                                text: 'при выполнеении запроса'
                            });
                        })
                    }
                });
            }
        },
        components: {Errors}
    }
</script>