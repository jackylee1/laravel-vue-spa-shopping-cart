<template>
    <div>
        <Breadcrumbs :items="breadcrumbs"/>

        <section class="wrapper">
            <Errors/>

            <div class="container">

                <Sort v-on:getProducts="getProducts"
                      v-on:updateSort="updateSort"
                      :currentCategory="currentCategory"/>

                <Filters :currentType="currentType"
                         :currentCategory="currentCategory"
                         v-on:getProducts="getProducts"/>

                <Products :products="products"/>

                <div v-if="products.length === 0" class="alert alert-info" style="margin-top:20px; width: 100%">
                    По запросу нет товаров
                </div>

                <Pagination :metaData="metaData"
                            v-on:getProducts="getProducts"/>
            </div>
        </section>
    </div>
</template>

<script>
    import * as ApiProducts from '../../../app/public/api/Products';

    import Breadcrumbs from "../Breadcrumbs";
    import Products from "./Products";
    import Sort from "./Sort";
    import Filters from "./Filters";
    import Errors from "../Errors";
    import Pagination from "../Pagination";

    export default {
        name: 'CatalogLayout',
        mounted() {
            if (this.types.length) {
                this.setTypesAndBreadcrumbs();
            }
        },
        computed: {
            types: function () {
                return this.$store.getters.types;
            }
        },
        data() {
            return {
                breadcrumbs: [],
                currentCategory: {},
                currentType: {},
                products: [],
                sort: (this.$route.query.sort !== undefined && this.$route.query.sort !== null) ? this.$route.query.sort : 'all',
                metaData: {
                    last_page: null,
                    current_page: 1,
                    prev_page_url: null
                }
            }
        },
        components: {
            Pagination,
            Errors,
            Filters,
            Sort,
            Products,
            Breadcrumbs,
        },
        methods: {
            updateSort: function (value) {
                this.sort = value;
            },
            getProducts: function (page = 1) {
                ApiProducts.get(page, {
                    type: this.currentType.id,
                    category: this.currentCategory.id,
                    filters: this.$route.query.filters,
                    sort: this.sort
                }).then((res) => {
                    this.metaData.last_page = res.data.products.last_page;
                    this.metaData.current_page = res.data.products.current_page;
                    this.metaData.prev_page_url = res.data.products.prev_page_url;

                    this.products = res.data.products.data;
                }).catch((error) => {
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка',
                        text: 'при выполнеении запроса'
                    });
                });
            },
            setTypesAndBreadcrumbs: function () {
                this.types.forEach((type) => {
                    if (type.slug === this.$route.query.type) {
                        this.currentType = type;
                    }
                    type.categories.forEach((category) => {
                        if (category.slug === this.$route.query.category) {
                            this.currentCategory = category;
                        }
                    });
                });

                this.breadcrumbs = [
                    {title: this.currentType.name, route: `{ "name": "catalog", "query": { "type": "${this.currentType.slug}"} }`},
                    {title: this.currentCategory.name},
                ];
            }
        },
        watch: {
            'types': function () {
                this.setTypesAndBreadcrumbs();
            },
            '$route' (to, from){
                this.getProducts();
                this.setTypesAndBreadcrumbs();
            }
        }
    }
</script>