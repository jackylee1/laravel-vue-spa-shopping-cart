<template>
    <div>
        <VueLoading :active.sync="isLoading" color="#df1e30"></VueLoading>

        <Breadcrumbs :items="breadcrumbs"/>

        <section class="wrapper">
            <div class="container">
                <Errors :type="typeAlerts"
                        :alerts="alerts"/>

                <Sort v-on:getProducts="getProducts"
                      v-on:updateSort="updateSort"
                      :currentCategory="currentCategory"
                      :currentType="currentType"/>

                <Filters :currentType="currentType"
                         :currentCategory="currentCategory"
                         v-on:getProducts="getProducts"/>

                <Products :products="products"/>

                <div v-if="products !== undefined && products.length === 0" class="alert alert-info" style="margin-top:20px; width: 100%">
                    По запросу нет товаров
                </div>

                <Pagination :pagination="pagination"
                            v-on:pageChange="getProducts"/>
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
    import VueLoading from "vue-loading-overlay/src/js/Component";

    export default {
        name: 'CatalogLayout',
        mounted() {
            this.$scrollTo('#top_line', 650);

            if (this.types.length) {
                this.setTypesAndBreadcrumbs();
            }

            this.$watch(vm => [vm.currentType, vm.currentCategory], val => {
                this.$scrollTo('#top_line', 650);

                let intervalId = setInterval(() => {
                    let checkType = this.intervalTypeCategory[0] === this.currentType;
                    let checkCategory = this.intervalTypeCategory[1] === this.currentCategory;
                    if (checkType && checkCategory) {
                        this.getProducts(this.$route.query.page);
                        clearInterval(intervalId);
                    }
                    else {
                        this.intervalTypeCategory = [this.currentType, this.currentCategory];
                    }
                }, 500);
            });

            this.$store.commit('updateSearchByText', this.$router.currentRoute.query.text);
        },
        computed: {
            types: function () {
                return this.$store.getters.types;
            },
            productsStore: function () {
                return this.$store.getters.products;
            },
            urlPrevious: function () {
                return this.$store.getters.urlPrevious;
            },
            watchProps: function () {
                return [this.currentCategory, this.currentType].join();
            },
            searchByText: function () {
                return this.$store.getters.searchByText;
            }
        },
        data() {
            return {
                breadcrumbs: [],
                currentCategory: null,
                currentType: null,
                isLoading: false,
                isFullPage: true,
                products: [],
                sort: (this.$route.query.sort !== undefined && this.$route.query.sort !== null) ? this.$route.query.sort : 'all',
                pagination: {
                    currentPage: 1,
                    totalPages: 1,
                    count: 1
                },
                intervalTypeCategory: [],
                typeAlerts: 'danger',
                alerts: null
            }
        },
        components: {
            VueLoading,
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
            setProducts: function (products) {
                this.pagination.currentPage = products.current_page;
                this.pagination.totalPages = products.last_page;
                this.pagination.count = products.total;

                this.products = products.data;

                this.isLoading = false;
            },
            getProducts: function (page = 1) {
                console.log('getProducts | call method');

                this.$router.push({ query: Object.assign({}, this.$route.query, { page: page }) });

                this.isLoading = true;

                if (this.$router.currentRoute.fullPath === this.urlPrevious) {
                    this.setProducts(this.productsStore);
                }
                else {
                    setTimeout(() => {
                        this.$store.commit('updateCategoryPrevious', this.currentCategory);
                        this.$store.commit('updateTypePrevious', this.currentType);

                        this.$store.commit('updateUrlPrevious', this.$router.currentRoute.fullPath);

                        ApiProducts.get(page, {
                            type: (this.currentType !== null) ? this.currentType.id : null,
                            category: (this.currentCategory !== null) ? this.currentCategory.id : null,
                            filters: this.$route.query.filters,
                            sort: this.sort,
                            text: this.$store.getters.searchByText
                        }).then((res) => {
                            console.log('getProducts | api response');

                            this.$store.commit('updateProducts', res.data.products);

                            this.setProducts(res.data.products);
                        }).catch((error) => {
                            this.alerts = error.response.data.errors;
                            this.$notify({
                                type: 'error',
                                title: 'Ошибка',
                                text: 'при выполнеении запроса'
                            });
                        });
                    }, 1200);
                }
            },
            setTypesAndBreadcrumbs: function () {
                this.breadcrumbs = [];
                let tempType = null;
                let tempCategory = null;
                this.types.forEach((type) => {
                    if (type.slug === this.$route.query.type) {
                        tempType = type;
                    }
                    type.categories.forEach((category) => {
                        if (category.slug === this.$route.query.category) {
                            tempCategory = category;
                        }
                    });
                });
                [this.currentType, this.currentCategory] = [tempType, tempCategory];

                if (this.currentType !== null) {
                    this.breadcrumbs.push({
                        title: this.currentType.name,
                        route: `{ "name": "catalog", "query": { "type": "${this.currentType.slug}"} }`
                    });
                }
                if (this.currentCategory !== null) {
                    this.breadcrumbs.push({
                        title: this.currentCategory.name
                    });
                }
            },
        },
        watch: {
            'types': function () {
                this.setTypesAndBreadcrumbs();
            },
            '$route' (to, from){
                this.$router.push({ query: Object.assign({}, this.$route.query, { text: this.searchByText }) });

                this.setTypesAndBreadcrumbs();
            },
            'sort': function () {
                this.getProducts();
            }
        },
        beforeDestroy() {
            this.$store.commit('updateSearchByText', null);
            this.$router.currentRoute.query.text = null;
        }
    }
</script>