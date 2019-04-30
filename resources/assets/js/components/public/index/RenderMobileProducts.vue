<template>
    <div>
        <div v-for="productsChunk in _.take(_.chunk(products, 2), 2)" class="row new_items_mobile">
            <div v-for="product in productsChunk" class="col-6">
                <div class="img-box">
                    <router-link :to="{name: 'product', params: {slug: product.slug}}">
                        <img v-if="product.main_image !== null"
                             :src="`/app/public/images/products/${product.main_image.preview}`"
                             :alt="product.name"
                             class="img-responsive img-fluid">
                    </router-link>
                </div>
                <div class="thumb-content">
                    <router-link :to="{ name: 'product', params: {slug: product.slug} }">
                        <h4>{{product.name}}</h4>
                    </router-link>
                    <p class="item-price">
                        <template v-if="product.discount_price !== null">
                            <strike>{{product.price}} грн</strike>
                        </template>
                        <span>{{product.current_price}} грн</span>
                    </p>
                    <div class="add_to_cart">
                        <router-link class="btn"
                                     :to="{ name: 'product', params: {slug: product.slug} }">
                            <i class="fas fa-shopping-cart"></i>Купить
                        </router-link>
                        <a @click="productAddToFavorite(product.id)"
                           href="javascript:void(0)" class="hrt">
                            <i class="far fa-heart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import mixinFavorite from '../../../app/public/mixins/Favorite';

    export default {
        name: 'RenderMobileProducts',
        props: ['products'],
        mixins: [mixinFavorite],
        computed: {
            _() {
                return _;
            }
        },
    }
</script>