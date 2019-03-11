<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

        <el-form :inline="true" :model="formSearch" class="ds-query-form">
            <el-form-item label="Поиск по">
                <el-input v-model="formSearch.q"
                          style="min-width: 300px"
                          placeholder="наименованию и краткому описанию"></el-input>
            </el-form-item>
            <el-form-item label="Только акционные">
                <el-select v-model="formSearch.onlyDiscounts" placeholder="Только акционные товары">
                    <el-option
                            v-for="item in this.selectBoolean"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="onSubmitSearch">
                    <i class="el-icon-search"></i> Поиск
                </el-button>
            </el-form-item>
        </el-form>

        <el-table
                v-loading="loading"
                :data="products"
                style="width: 100%">
            <el-table-column
                    fixed
                    prop="id"
                    label="ID"
                    min-width="50">
            </el-table-column>
            <el-table-column
                    fixed
                    label="Изображение"
                    width="120">
                <template slot-scope="props" v-if="props.row.main_image !== null">
                    <img width="70px" height="auto" :src="'/app/public/images/products/'+ props.row.main_image.preview">
                </template>
            </el-table-column>
            <el-table-column
                    prop="article"
                    label="Артикул"
                    min-width="100">
            </el-table-column>
            <el-table-column type="expand">
                <template slot-scope="props">
                    <p>SEO URL: {{props.row.slug}}</p>
                    <p v-if="props.row.preview_description.length">Краткое описание: {{props.row.preview_description}}</p>
                    <p v-if="props.row.discount_price > 0">Акцилнная цена: {{props.row.discount_price}}</p>
                    <p v-if="props.row.date_inclusion">Дата включения: {{props.row.date_inclusion}}</p>
                    <p>Дата добавления: {{props.row.created_at}}</p>
                </template>
            </el-table-column>
            <el-table-column
                    label="Наименование"
                    min-width="150">
                <template slot-scope="props">
                    <template v-if="props.row.discount_price !== null && props.row.discount_price > 0">
                        {{props.row.name}}
                        <el-tooltip class="item" effect="dark" content="Акционный товар" placement="top-start">
                            <i class="ai-tag-o share-price" />
                        </el-tooltip>
                    </template>
                    <template v-else>
                        {{props.row.name}}
                    </template>
                </template>
            </el-table-column>
            <el-table-column
                    label="Цена"
                    min-width="70">
                <template slot-scope="props">
                    <template v-if="props.row.discount_price !== null && props.row.discount_price > 0">
                        <p>{{props.row.discount_price}} <strike style="color: red">{{props.row.price}}</strike></p>
                    </template>
                    <template v-else>
                        <p>{{props.row.price}}</p>
                    </template>
                </template>
            </el-table-column>
            <el-table-column
                    label="Статус"
                    min-width="70"
                    max-width="70">
                <template slot-scope="props">
                    <template v-if="props.row.status">
                        <el-tooltip class="item" effect="dark" content="Отображается на сайте" placement="top-start">
                            <i class="el-icon-circle-check-outline icons-on-table"></i>
                        </el-tooltip>
                    </template>
                    <template v-else>
                        <el-tooltip class="item" effect="dark" content="Отключен на сайте" placement="top-start">
                            <i class="el-icon-circle-close-outline icons-on-table"></i>
                        </el-tooltip>
                    </template>
                </template>
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="Управление"
                    min-width="150">
                <template slot-scope="props">
                    <el-button
                            @click.native.prevent="goToUpdate(props.row.id)"
                            size="mini">
                        <i class="el-icon-edit"></i>
                    </el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click.native.prevent="btnDeleteProduct(props.$index, products)">
                        <i class="el-icon-delete"></i>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <PageElementsPagination :total="total"
                                :currentPage="currentPage"
                                :pageSize="pageSize"
                                v-on:change="handleCurrentPageChange"/>

        <el-dialog
                :title="titleDialog"
                :visible.sync="dialogVisible"
                width="30%">
            <el-alert
                    :title="titleAlert"
                    :type="typeAlert"
                    :closable="false">
            </el-alert>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">Отмена</el-button>
                <el-button type="primary" @click="deleteProduct">Подтверждаю</el-button>
            </span>
        </el-dialog>

    </div>
</template>

<script>
    import * as helperRouter from '../../../app/helpers/router';
    import * as ApiProducts from '../../../app/admin/api/Products';
    import * as ApiFilters from '../../../app/admin/api/Filters';
    import * as ApiTypes from '../../../app/admin/api/Types';

    import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

    export default {
        name: 'products-list',
        mounted () {
            if (this.typesStore.length) {
                this.types = this.typesStore;
            }
            else {
                this.setTypes();
            }

            if (this.filtersStore.length) {
                this.filters = this.filtersStore;
            }
            else {
                this.setFilters();
            }

            this.formSearch = this.searchProducts;
            this.oldFormSearch = _.cloneDeep(this.formSearch);

            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'products-list'}).href, title: this.$router.currentRoute.meta.title},
            ];
            if (this.productsStore.data !== undefined
                && this.productsStore.data.length) {
                this.products = this.productsStore.data;
                this.total = this.productsStore.total;
                this.currentPage = this.productsStore.current_page;
                this.pageSize = this.productsStore.per_page;

                helperRouter.updatePage(this.$router, this.currentPage);

                this.loading = false;

                return true;
            }

            let page = helperRouter.currentPage(this.$router);
            this.getProducts((page !== undefined) ? page : 1);
        },
        data() {
            return {
                formSearch: {
                    q: '',
                    selectedType: null,
                    selectedCategories: [],
                    selectedFilters: [],
                    onlyDiscounts: 0,
                    selectedTypeObject: {}
                },
                oldFormSearch: null,
                products: [],
                currentPage: 0,
                total: 0,
                pageSize: 0,
                breadcrumbElements: [],
                loading: true,
                dialogVisible: false,
                titleDialog: '',
                operationsOnProduct: null,
                typeAlert: 'warning',
                titleAlert: '',
                alerts: [],
                filters: [],
                types: [],
            }
        },
        computed: {
            changeSelectType: function () {

            },
            productsStore: function () {
                return this.$store.getters.products;
            },
            searchProducts: function () {
                return this.$store.getters.searchProducts;
            },
            selectBoolean: function () {
                return this.$store.getters.selectDataBoolean;
            },
            filtersStore: function () {
                return this.$store.getters.filters;
            },
            typesStore: function () {
                return this.$store.getters.types;
            },
        },
        methods: {
            setFilters: function () {
                ApiFilters.get().then((response) => {
                    this.$store.commit('updateFilters', response.data.filters);
                    this.filters = response.data.filters;
                });
            },
            setTypes: function () {
                ApiTypes.get().then((response) => {
                    this.$store.commit('updateTypes', response.data.types);
                    this.types = response.data.types;
                });
            },
            onSubmitSearch: function () {

            },
            deleteProduct: function () {
                if (this.operationsOnProduct) {
                    ApiProducts.destroy(this.operationsOnProduct.id).then((response) => {
                        let products = this.productsStore;
                        let index = products.data.findIndex((item) => item.id === this.operationsOnProduct.id);
                        products.data.splice(index, 1);
                        this.$store.commit('updateProducts', products);
                        this.dialogVisible = false;
                        this.operationsOnProduct = null;
                        this.$notify.success({
                            offset: 50,
                            title: 'Удаление продукта',
                            message: response.data.message
                        });
                    }).catch((error) => {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'при удалении продукта'
                        });
                        this.typeAlert = 'error';
                        this.alerts = error.response.data.errors;
                        this.dialogVisible = false;
                        this.operationsOnProduct = null;
                    });
                }
            },
            btnDeleteProduct: function (index, products) {
                this.operationsOnProduct = products[index];
                this.titleDialog = 'Удаление продукта';
                this.titleAlert = `Вы дейстительно хотите удалить продукт: ${this.operationsOnProduct.name} (ID: ${this.operationsOnProduct.id})?`;
                this.dialogVisible = true;
            },
            getProducts: function (page = 1) {
                this.loading = true;
                return ApiProducts.get(page).then((response) => {
                    this.products = response.data.products.data;
                    this.total = response.data.products.total;
                    this.currentPage = response.data.products.current_page;
                    this.pageSize = response.data.products.per_page;
                    this.$store.commit('updateProducts', response.data.products);

                    helperRouter.updatePage(this.$router, this.currentPage);

                    this.loading = false;
                });
            },
            handleCurrentPageChange: function (page) {
                this.getProducts(page);
            },
            goToUpdate: function (id) {
                this.$router.push({name: 'products-update', params: {id: id}})
            }
        },
        components: {
            PageElementsPagination,
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
        beforeDestroy() {

        }
    }
</script>

<style scoped>
    .share-price {
        margin-left: 5px;
    }

    .icons-on-table {
        font-size: 25px;
    }
</style>