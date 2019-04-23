<template>
    <div>
        <div v-if="product.size_table !== null" class="row">
            <div class="col-sm-6 variants">
            </div>
            <div class="col-sm-6 tablecell">
                <a href="#size_table">Таблица размеров</a>
            </div>
        </div>

        <Errors :type="typeAlerts"
                v-on:clearAlerts="clearAlerts"
                :alerts="alerts"/>

        <div class="row">
            <div class="col-md-12 variations">
                <form class="form-horizontal " method="post" action="">
                    <div class="form-group">
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            <RenderAvailable :availableModels="product.available"
                                             :idAvailable="idAvailable"
                                             v-on:changeIdAvailable="changeIdAvailable"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 size_table_link">
                <a v-if="product.size_table !== null" href="#size_table">Таблица размеров</a>
            </div>
        </div>
        <div class="row by_it">
            <div class="col-4 by_it_form">
                <div class="input-group spinner">
                    <input type="text" class="form-control"
                           v-model="valueQuantity"
                           :min="minQuantity"
                           :max="maxQuantity">
                    <div class="input-group-btn-vertical">
                        <button @click="incValueQuantity()" class="btn btn-default" type="button">+</button>
                        <button @click="descValueQuantity()" class="btn btn-default" type="button">-</button>
                    </div>
                </div>
            </div>
            <div class="col-8 add_to_cart my-auto">
                <a @click="addProductToCart()" href="javascript:void(0)"><i class="fas fa-shopping-cart"></i>КУПИТЬ</a>
            </div>
        </div>
        <div class="row by_it">
            <div class="col-12 one_click my-auto">
                <a href="#">Купить в один клик</a>
            </div>
        </div>
        <div class="row like_it">
            <div class="col-md-12 like_it_icon my-auto">
                <a @click="productAddToFavorite(product.id)"
                   href="javascript:void(0)" class="hrt">
                    <i class="far fa-heart"></i>
                    Добавить в избранное
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import RenderAvailable from "./RenderAvailable";
    import mixinFavorite from '../../../app/public/mixins/Favorite';
    import mixinCart from '../../../app/public/mixins/Cart';
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import Errors from "../Errors";

    export default {
        name: 'AvailableAndControl',
        props: ['product'],
        mixins: [mixinFavorite, mixinCart, mixinAlerts],
        components: {Errors, RenderAvailable}
    }
</script>