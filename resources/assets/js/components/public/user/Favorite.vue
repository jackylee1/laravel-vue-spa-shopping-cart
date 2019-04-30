<template>
    <section class="checkout_form sf_form">
        <div class="container">
            <div class="row checkout_login">
                <Sidebar/>

                <div class="col-md-8 col-lg-9 wrapper ">
                    <div class="row">
                        <h3>Список избранного</h3>
                    </div>
                    <div v-if="favorite !== null && favorite.products !== undefined && favorite.products.length > 0" class="row">
                        <div v-for="product in favorite.products" class="col-md-6 col-lg-3 col-sm-6 col-6">
                            <div class="thumb-wrapper">
                                <div class="img-box">
                                    <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                                        <img v-if="product.product.main_image !== null"
                                             :src="`/app/public/images/products/${product.product.main_image.preview}`"
                                             :alt="product.product.name"
                                             class="img-responsive img-fluid">
                                    </router-link>
                                </div>
                                <div class="thumb-content">
                                    <router-link :to="{ name: 'product', params: {slug: product.product.slug} }">
                                        <h4>{{product.product.name}}</h4>
                                    </router-link>
                                    <p class="item-price">
                                        <template v-if="product.product.discount_price !== null">
                                            <strike>{{product.product.price}} грн</strike>
                                        </template>
                                        <span>{{product.product.current_price}} грн</span>
                                    </p>
                                    <div class="add_to_cart">
                                        <router-link class="btn" :to="{ name: 'product', params: {slug: product.product.slug} }">
                                            <i class="fas fa-shopping-cart"></i>Купить
                                        </router-link>
                                        <a @click="productRemoveFromFavorite(product.id)"
                                           href="javascript:void(0)" class="hrt">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="row">
                        <div class="alert alert-info col-sm-12">
                            У вас нет товаров в списке избранного
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import mixinFavorite from '../../../app/public/mixins/Favorite';
    import Sidebar from "./Sidebar";
    import Errors from "../Errors";

    export default {
        name: 'InformationLayout',
        mixins: [mixinAlerts, mixinFavorite],
        mounted() {
        },
        computed: {
            favorite: function () {
                return this.$store.getters.favorite;
            }
        },
        components: {Errors, Sidebar},
        metaInfo: {
            title: '| Кабинет | Список избранного'
        }
    }
</script>