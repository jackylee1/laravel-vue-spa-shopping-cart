<template>
    <div>
        <template v-if="products !== undefined && products.length">
            <template v-for="(productsChunk, index) in _.chunk(products, 4)">
                <div class="row category_items">
                    <template v-for="(product, productIndex) in productsChunk">
                        <div class="col-md-3 col-sm-6 col-6">
                            <div class="thumb-wrapper">
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
                                            <i class="far fa-heart"></i>
                                        </a>
                                    </div>
                                    <div class="quick_view">
                                        <a href="javascript:void(0)"
                                           @click="quickView(product)"
                                           data-toggle="modal"
                                           data-target="#quick_view1">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template v-if="index === 0 && productIndex === 0">
                            <div class="modal fade"
                                 id="quick_view1"
                                 tabindex="-1" role="dialog"
                                 aria-labelledby="quick_view1Title"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="quick_view1Title">Быстрый просмотр товара</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div v-if="quickViewProduct !== null" class="row">
                                                <div class="col-5">
                                                    <img v-if="quickViewProduct.main_image !== null"
                                                         :src="`/app/public/images/products/${quickViewProduct.main_image.preview}`"
                                                         :alt="quickViewProduct.name"
                                                         class="img-responsive img-fluid">
                                                </div>
                                                <div class="col-7 variations">
                                                    <h5>{{quickViewProduct.name}}</h5>
                                                    <p>Код товара: {{quickViewProduct.article}}</p>
                                                    <p class="item-price">
                                                        Цена:
                                                        <template v-if="quickViewProduct.discount_price !== null">
                                                            <strike>
                                                                {{quickViewProduct.price}} грн.
                                                            </strike>
                                                            <span>{{quickViewProduct.discount_price}} грн.</span>
                                                        </template>
                                                        <template v-else>
                                                            <span>{{quickViewProduct.price}} грн.</span>
                                                        </template>
                                                    </p>
                                                    <div class="form-group">
                                                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                            <RenderAvailable :availableModels="quickViewProduct.available"
                                                                             :idAvailable="idAvailable"
                                                                             v-on:changeIdAvailable="changeIdAvailable"/>
                                                        </div>
                                                    </div>

                                                    <Errors  style="margin-top: 10px" :type="typeAlerts"
                                                             v-on:clearAlerts="clearAlerts"
                                                             :alerts="alerts"/>

                                                    <div class="add_to_cart">
                                                        <a @click="addProductToCart()"
                                                           href="javascript:void(0)"
                                                           class="btn">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            Купить
                                                        </a>
                                                        <a @click="productAddToFavorite(quickViewProduct.id)"
                                                           href="javascript:void(0)" class="hrt">
                                                            <i class="far fa-heart"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-12 more_details centered">
                                                    <a @click="viewProduct(quickViewProduct.slug)"
                                                       href="javascript:void(0)">Просмотеть детали...</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    import mixinFavorite from '../../../app/public/mixins/Favorite';
    import mixinCart from '../../../app/public/mixins/Cart';
    import RenderAvailable from "../product/RenderAvailable";
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import Errors from "../Errors";

    export default {
        name: 'Products',
        mixins: [mixinFavorite, mixinCart, mixinAlerts],
        components: {Errors, RenderAvailable},
        props: ['products'],
        computed: {
            _() {
                return _;
            }
        },
        data() {
            return {
                quickViewProduct: null,
                product: null
            }
        },
        methods: {
            quickView: function (product) {
                this.idAvailable = _.first(product.available);
                if (this.idAvailable !== undefined) {
                    this.idAvailable = this.idAvailable.id;
                }
                this.quickViewProduct = product;
                this.product = product;
            },
            viewProduct: function (slug) {
                $('#quick_view1').modal('hide');
                $('.modal-backdrop').removeClass('modal-backdrop');
                this.$router.push({ name: 'product', params: { slug: slug } });
            }
        }
    }
</script>