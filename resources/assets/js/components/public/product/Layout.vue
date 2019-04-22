<template>
    <div>
        <VueLoading :active.sync="isLoading" color="#df1e30"/>

        <Breadcrumbs :items="breadcrumbs"/>

        <section class="item_card">
            <div class="container">
                <Errors :type="typeAlerts"
                        v-on:clearAlerts="clearAlerts"
                        :alerts="alerts"/>

                <template v-if="product !== null && product !== undefined">
                    <div class="row">
                        <Images :product="product"/>

                        <div class="col-md-4 item_card_description">
                            <FilterImages :product="product" />

                            <h2>{{product.name}}</h2>
                            <p>Код товара:<span class="code">{{product.article}}</span></p>
                            <h3>
                                <template v-if="product.discount_price !== null">
                                    <strike>{{price}} <span class="currency">грн</span></strike>
                                </template>
                                <span class="red_price">{{currentPrice}} <span class="currency">грн</span></span>
                            </h3>

                            <AvailableAndControl :product="product"
                                                 :alerts="alerts"
                                                 v-on:setProductPrice="setProductPrice"/>
                        </div>
                    </div>
                    <div class="row description_title">
                        <div class="col-12">
                            <h4>
                                Описание товара
                            </h4>
                        </div>
                    </div>

                    <Description :product="product"/>

                    <Comments/>
                </template>

                <!-- <RecommendedProducts/> -->
            </div>
        </section>
    </div>
</template>

<script>
    import * as jquery from '../../../app/public/src/jquery';
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import * as ApiProducts from '../../../app/public/api/Products';
    import Comments from "./Comments";
    import Description from "./Description";
    import RecommendedProducts from "./RecommendedProducts";
    import Images from "./Images";
    import FilterImages from "./FilterImages";
    import AvailableAndControl from "./AvailableAndControl";
    import Breadcrumbs from "../Breadcrumbs";
    import VueLoading from "vue-loading-overlay/src/js/Component";
    import Errors from "../Errors";

    export default {
        name: 'ProductLayout',
        mixins: [mixinAlerts],
        components: {
            Errors,
            VueLoading,
            Breadcrumbs, AvailableAndControl, FilterImages, Images, RecommendedProducts, Description, Comments},
        created() {
            jquery.loadScript();
        },
        mounted() {
            if (this.products.data !== undefined && this.products.data.length) {
                this.product = this.products.data.find((item) => item.slug === this.$router.currentRoute.params.slug);

                if (this.product === undefined) {
                    return this.productView();
                }

                this.price = this.product.price;
                this.currentPrice = this.product.current_price;

                this.handleSetBreadcrumbs();

                this.isLoading = false;
            }
            else {
                this.productView();
            }

            this.$scrollTo('#top_line', 650);
        },
        computed: {
            'products': function () {
                return this.$store.getters.products;
            },
            'types': function () {
                return this.$store.getters.types;
            },
            'categoryPrevious': function () {
                return this.$store.getters.categoryPrevious;
            },
            'typePrevious': function () {
                return this.$store.getters.typePrevious;
            },
            'urlPrevious': function () {
                return this.$store.getters.urlPrevious;
            }
        },
        data() {
            return {
                product: null,
                breadcrumbs: [],
                isLoading: true,
                dataLoad: [],
                price: 0,
                currentPrice: 0
            }
        },
        methods: {
            setProductPrice: function (quantity = 1) {
                this.price = this.product.price * quantity;
                this.currentPrice = this.product.current_price * quantity;
            },
            productView: function () {
                ApiProducts.view(this.$router.currentRoute.params.slug).then((res) => {
                    if (res.data.status === 'success') {
                        this.product = res.data.product;
                        this.price = this.product.price;
                        this.currentPrice = this.product.current_price;

                        setTimeout(() => {
                            this.isLoading = false;
                        }, 800);
                    }
                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка',
                        text: 'при получении товара'
                    });
                    this.isLoading = false;
                });
            },
            setBreadcrumbs: function (typeId, categoryId) {
                let type = this.types.find((item) => item.id === typeId);
                if (type !== undefined) {
                    this.breadcrumbs.push({
                        title: type.name,
                        route: `{ "name": "catalog", "query": { "type": "${type.slug}"} }`
                    });
                }

                if (categoryId !== null) {
                    if (Array.isArray(categoryId)) {
                        categoryId = this.product.main_type.category_id[this.product.main_type.category_id.length - 1];
                    }

                    type.categories.forEach((item) => {
                        if (item.id === categoryId) {
                            if (this.urlPrevious !== null) {
                                this.breadcrumbs.push({
                                    'title': item.name,
                                    'url': this.urlPrevious
                                });
                            }
                            else {
                                this.breadcrumbs.push({
                                    'title': item.name,
                                    'route': `{ "name": "catalog", "query": { "type": "${type.slug}", "category": "${item.slug}"} }`
                                });
                            }

                        }
                    });
                }

                if (this.product !== null) {
                    this.breadcrumbs.push({'title': this.product.name});
                }
            },
            handleSetBreadcrumbs: function () {
                if (this.breadcrumbs.length === 0
                    && this.typePrevious === null && this.categoryPrevious === null
                    && this.types.length
                    && this.product !== null
                    && this.product.main_type !== null
                    && this.product.main_type.type_id !== undefined) {
                    this.setBreadcrumbs(this.product.main_type.type_id, this.product.main_type.category_id);
                }
                else {
                    let typeId = null;
                    let categoryId = null;
                    if (this.typePrevious !== null) {
                        typeId = this.typePrevious.id;
                    }
                    if (this.categoryPrevious !== null) {
                        categoryId = this.categoryPrevious.id;
                    }
                    this.setBreadcrumbs(typeId, categoryId);
                }
            }
        },
        watch: {
            'types': function () {
                this.dataLoad.push(true);
            },
            'product': function () {
                this.dataLoad.push(true);
            },
            'dataLoad': function (value) {
                if (value.length >= 2) {
                    this.handleSetBreadcrumbs();
                }
            }
        }
    }
</script>