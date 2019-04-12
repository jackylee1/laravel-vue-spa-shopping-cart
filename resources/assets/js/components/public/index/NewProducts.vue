<template>
    <div v-if="products.length">
        <section class="prod_slider">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>
                            НОВЫЕ ТОВАРЫ
                        </h1>
                    </div>
                </div>
                <div class="row prod_slider_content">
                    <div class="col-md-12">
                        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="0">
                            <div class="carousel-inner">
                                <template v-for="(productsChunk, index) in _.chunk(products, 4)">
                                    <div :class="(index === 0) ? 'item carousel-item active' : 'item carousel-item'">
                                        <div class="row">
                                            <template v-for="product in productsChunk">
                                                <div class="col-sm-3">
                                                    <div class="thumb-wrapper">
                                                        <div class="img-box">
                                                            <router-link :to="{name: 'product', params: {slug: product.slug}}">
                                                                <a href="javascript:void(0)">
                                                                    <img v-if="product.main_image !== null"
                                                                         :src="`/app/public/images/products/${product.main_image.preview}`"
                                                                         :alt="product.name"
                                                                         class="img-responsive img-fluid">
                                                                </a>
                                                            </router-link>
                                                        </div>
                                                        <div class="thumb-content">
                                                            <router-link :to="{ name: 'product', params: {slug: product.slug} }">
                                                                <a href="javascript:void(0)"><h4>{{product.name}}</h4></a>
                                                            </router-link>
                                                            <p class="item-price">
                                                                <template v-if="product.discount_price !== null">
                                                                    <strike>{{product.price}} грн</strike>
                                                                </template>
                                                                <span>{{product.current_price}} грн</span>
                                                            </p>
                                                            <div class="add_to_cart">
                                                                <a href="#" class="btn"><i class="fas fa-shopping-cart"></i>Купить</a>
                                                                <a href="#" class="hrt"><i class="far fa-heart"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <a class="carousel-control left carousel-control-prev" href="#myCarousel" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="carousel-control right carousel-control-next" href="#myCarousel" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-for="productsChunk in _.take(_.chunk(products, 2), 2)" class="row new_items_mobile">
                    <div v-for="product in productsChunk" class="col-6">
                        <div class="img-box">
                            <router-link :to="{name: 'product', params: {slug: product.slug}}">
                                <a href="javascript:void(0)">
                                    <img v-if="product.main_image !== null"
                                         :src="`/app/public/images/products/${product.main_image.preview}`"
                                         :alt="product.name"
                                         class="img-responsive img-fluid">
                                </a>
                            </router-link>
                        </div>
                        <div class="thumb-content">
                            <router-link :to="{ name: 'product', params: {slug: product.slug} }">
                                <a href="javascript:void(0)"><h4>{{product.name}}</h4></a>
                            </router-link>
                            <p class="item-price">
                                <template v-if="product.discount_price !== null">
                                    <strike>{{product.price}} грн</strike>
                                </template>
                                <span>{{product.current_price}} грн</span>
                            </p>
                            <div class="add_to_cart">
                                <a href="#" class="btn"><i class="fas fa-shopping-cart"></i>Купить</a>
                                <a href="#" class="hrt"><i class="far fa-heart"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'NewProducts',
        props: ['products'],
        computed: {
            _() {
                return _;
            }
        },
    }
</script>