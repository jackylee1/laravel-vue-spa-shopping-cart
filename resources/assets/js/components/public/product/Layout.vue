<template>
    <div>
        <Breadcrumbs :items="breadcrumbs"/>

        <section class="item_card">
            <div class="container">
                <template v-if="product !== null">
                    <div class="row">
                        <Images :product="product"/>

                        <div class="col-md-4 item_card_description">
                            <FilterImages :product="product" />

                            <h2>{{product.name}}</h2>
                            <p>Код товара:<span class="code">{{product.article}}</span></p>
                            <h3>
                                <template v-if="product.discount_price !== null">
                                    <strike>{{product.price}} <span class="currency">грн</span></strike>
                                </template>
                                <span class="red_price">{{product.current_price}} <span class="currency">грн</span></span>
                            </h3>

                            <AvailableAndControl :product="product"/>
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

                <RecommendedProducts/>
            </div>
        </section>
    </div>
</template>

<script>
    import * as jquery from '../../../app/public/src/jquery';

    import * as ApiProducts from '../../../app/public/api/Products';
    import Comments from "./Comments";
    import Description from "./Description";
    import RecommendedProducts from "./RecommendedProducts";
    import Images from "./Images";
    import FilterImages from "./FilterImages";
    import AvailableAndControl from "./AvailableAndControl";
    import Breadcrumbs from "../Breadcrumbs";

    export default {
        name: 'ProductLayout',
        components: {Breadcrumbs, AvailableAndControl, FilterImages, Images, RecommendedProducts, Description, Comments},
        created() {
            jquery.loadScript();
        },
        mounted() {
            if (this.products.data !== undefined && this.products.data.length) {
                this.product = this.products.data.find((item) => item.slug === this.$router.currentRoute.params.slug);
            }
            else {
                ApiProducts.view(this.$router.currentRoute.params.slug).then((res) => {
                    if (res.data.status === 'success') {
                        this.product = res.data.product;
                    }
                }).catch((error) => {

                });
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
            }
        },
        data() {
            return {
                product: null,
                breadcrumbs: []
            }
        },
        methods: {
            setBreadcrumbs: function (typeId, categoryId) {
                let type = this.types.find((item) => item.id === typeId);
                if (type !== undefined) {
                    this.breadcrumbs.push({'title': type.name});
                }

                if (Array.isArray(categoryId)) {
                    categoryId = this.product.main_type.category_id[this.product.main_type.category_id.length - 1];
                }

                type.categories.forEach((item) => {
                    if (item.id === categoryId) {
                        this.breadcrumbs.push({
                            'title': item.name,
                            'route': `{ "name": "catalog", "query": { "type": "${type.slug}", "category": "${item.slug}"} }`
                        });
                    }
                });
                this.breadcrumbs.push({'title': this.product.name});
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
                else if (this.typePrevious !== null && this.categoryPrevious !== null) {
                    this.setBreadcrumbs(this.typePrevious.id, this.categoryPrevious.id);
                }
            }
        },
        watch: {
            'types': function () {
                this.handleSetBreadcrumbs();
            },
            'product': function () {
                this.handleSetBreadcrumbs();
            },
        }
    }
</script>