<template>
    <div class="card-body">
        <p class="bold">Всего товаров: <span>{{cart.products.length}} шт.</span></p>
        <hr>
        <div class="scroll_items">
            <div class="row" v-for="product in cart.products">
                <div class="col-6">
                    <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                        <img v-if="product.product.main_image !== null"
                             :src="`/app/public/images/products/${product.product.main_image.preview}`"
                             :alt="product.product.name">
                    </router-link>
                </div>
                <div class="col-6">
                    <ul>
                        <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                            <li><a href="javascript:void(0)" class="bold">{{product.product.name}}</a></li>
                        </router-link>
                        <template v-for="filter in getAvailable(product.product_available_id, product.product).filters">
                            <li v-html="getParentAndSelectFilter(filter.filter_id)"></li>
                        </template>
                        <li>КОЛИЧЕСТВО: {{product.quantity}}</li>
                        <li v-if="product.quantity > 1">ЦЕНА:
                            <strike v-if="product.product.discount_price !== null">
                                {{product.product.price}} грн
                            </strike>
                            {{product.product.current_price}} грн
                        </li>
                        <li>НА СУММУ:
                            <strike v-if="product.product.discount_price !== null">
                                {{product.product.price*product.quantity}} грн
                            </strike>
                            {{product.product.current_price*product.quantity}} грн
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <router-link :to="{ name: 'cart' }">
            <a href="javascript:void(0)">Редактировать заказ</a>
        </router-link>
    </div>
</template>

<script>
    import mixinCart from '../../../app/public/mixins/Cart';

    export default {
        name: 'ConfirmProducts',
        mixins: [mixinCart],
    }
</script>