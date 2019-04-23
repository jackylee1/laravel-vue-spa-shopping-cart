<template>
    <div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <h3 class="modal_title">Товар добавлен в корзину</h3>
                            </div>
                        </div>
                        <div v-for="product in cartProducts" class="row">
                            <div class="col-3">
                                <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                                    <a href="javascript:void(0)">
                                        <img v-if="product.product.main_image !== null"
                                             :src="`/app/public/images/products/${product.product.main_image.preview}`"
                                             :alt="product.product.name"
                                             class="img-responsive img-fluid">
                                    </a>
                                </router-link>
                            </div>
                            <div class="col-md-6">
                                <h3 class="item_title">{{product.product.name}}</h3>
                                <p>Артикул: <strong>{{product.product.article}}</strong></p>
                                <template v-for="filter in getAvailable(product.product_available_id, product.product).filters">
                                    <p v-html="getParentAndSelectFilter(filter.filter_id)"></p>
                                </template>
                                <p>Кол-во: <strong>{{product.quantity}} шт.</strong></p>
                            </div>
                            <div class="col-3">
                                <h4 class="modal_price">
                                    <template v-if="product.product.discount_price !== null">
                                        <strike style="color: #b3b3b3">{{product.product.price * product.quantity}} грн</strike>
                                    </template>

                                    {{product.product.current_price * product.quantity}} грн
                                </h4>
                            </div>
                        </div>
                        <template v-if="cartProducts.length === 0">
                            <div class="alert alert-info">
                                У Вас нет товаров в корзине
                            </div>
                        </template>
                        <div v-if="totalPrice > 0" class="row">
                            <div class="col-12 righted">
                                <h5 class="item_sum">
                                    <span>Сумма заказа:</span> {{totalPrice}} грн
                                </h5>
                            </div>
                            <div class="col-12 righted">
                                <h5 v-if="totalPriceDiscount > 0" class="item_sum">
                                    <span>Сумма с учетом пользовательских скидок:</span> {{totalPriceDiscount}} грн
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">Продолжить покупки</button>
                        <button v-if="cartProducts.length" @click="openCart"
                                type="button" class="btn btn-danger">Оформить заказ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import mixinCart from '../../../app/public/mixins/Cart';

    export default {
        name: 'ModalProducts',
        mixins: [mixinCart],
        methods: {
            openCart: function () {
                $('#exampleModal').modal('hide');
                $('.modal-backdrop').removeClass('modal-backdrop');
                this.$router.push({ name: 'cart'});
            }
        }
    }
</script>