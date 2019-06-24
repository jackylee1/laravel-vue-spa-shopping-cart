<template>
  <div>
    <el-form :model="formSearch" class="ds-query-form" label-width="250px">
      <el-form-item label="Поиск по">
        <el-input v-model="formSearch.q"
                  placeholder="артикул, наименованию и краткому описанию"></el-input>
      </el-form-item>
      <el-form-item label="Только акционные">
        <el-select style="min-width: 450px;"
                   v-model="formSearch.only_discounts"
                   placeholder="Только акционные товары">
          <el-option
              v-for="item in this.selectBoolean"
              :key="item.value"
              :label="item.label"
              :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Выберите тип">
        <el-select style="min-width: 450px;"
                   @change="changeSelectType"
                   v-model="formSearch.selected_type"
                   placeholder="Выберите тип">
          <el-option
              v-for="item in types"
              :key="item.id"
              :label="item.name"
              :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="formSearch.selected_type !== null"
                    label="Выберите категорию">
        <el-cascader style="min-width: 450px;"
                     expand-trigger="hover"
                     filterable
                     :options="this.getTreeCategories()"
                     :props="selectProps"
                     v-model="formSearch.selected_categories"
                     @change="changeSelectCategory">
        </el-cascader>
      </el-form-item>
      <el-form-item v-if="formSearch.selected_type !== null"
                    label="Выберите параметры">
        <el-select
            v-model="formSearch.selected_filters"
            multiple
            collapse-tags
            style="min-width: 450px;"
            placeholder="Выберите параметры">
          <el-option
              v-for="item in this.getFilters()"
              :key="item.id"
              :label="item.label"
              :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button-group>
          <el-button type="default" @click="onResetSearch">
            Сбросить
          </el-button>
          <el-button type="primary" @click="onSubmitSearch">
            <i class="el-icon-search"></i> Поиск
          </el-button>
        </el-button-group>
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
          min-width="50">
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
          min-width="100">
        <template slot-scope="props">
          <template v-if="props.row.discount_price !== null && props.row.discount_price > 0">
            {{props.row.name}}
            <el-tooltip class="item" effect="dark" content="Акционный товар" placement="top-start">
              <v-icon name="tag"></v-icon>
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
          label="Топ продаж"
          min-width="70">
        <template slot-scope="props">
          <template v-if="props.row.status_bestseller">
            <p>Да</p>
          </template>
          <template v-else>
            <p>Нет</p>
          </template>
          <template v-if="props.row.bestseller !== null">
            <small>продаж: {{props.row.bestseller.quantity}}</small>
          </template>
        </template>
      </el-table-column>
      <el-table-column
          fixed="right"
          min-width="150">
        <template slot-scope="props">
          <el-button
              @click.native.prevent="btnSelect(props.row.id)"
              size="mini">
            <i class="el-icon-check"></i>
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <PageElementsPagination :total="total"
                            :currentPage="currentPage"
                            :pageSize="pageSize"
                            v-on:change="handleCurrentPageChange"/>
  </div>
</template>

<script>
  import * as helperArray from '../../../app/admin/helpers/Array';
  import * as ApiProducts from '../../../app/admin/api/Products';
  import * as ApiFilters from '../../../app/admin/api/Filters';
  import * as ApiTypes from '../../../app/admin/api/Types';

  import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

  let arrayToTree = require('array-to-tree');

  export default {
    name: 'products-table-select',
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

      if (this.productsStore.data !== undefined
        && this.productsStore.data.length) {
        this.products = this.productsStore.data;
        this.total = this.productsStore.total;
        this.currentPage = this.productsStore.current_page;
        this.pageSize = this.productsStore.per_page;

        this.loading = false;

        return true;
      }

      this.getProducts(1);
    },
    data() {
      return {
        formSearch: {
          q: '',
          selected_type: null,
          selected_categories: [],
          selected_filters: [],
          only_discounts: 0,
        },
        oldFormSearch: null,
        products: [],
        currentPage: 0,
        total: 0,
        pageSize: 0,
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
        filters: [],
        types: [],
        treeCategories: [],
        selectProps: {
          value: 'id',
          label: 'name',
          children: 'children'
        },
        selectedCategory: [],
        selectedFilters: [],
      }
    },
    computed: {
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
      changeSelectType: function () {
        this.formSearch.selected_categories = this.formSearch.selected_filters = [];
      },
      getTreeCategories: function () {
        if (this.formSearch.selected_type !== null) {
          let type = this.types.find((item) => item.id === this.formSearch.selected_type);
          let categories = type.categories.map((item) => {
            if (item.parent_id === 1) {
              item.parent_id = 0;
            }
            return item;
          }).filter((item) => {
            return item.id !== 1;
          });
          categories = arrayToTree(categories);

          return categories;
        }
      },
      lastSelectedCategory: function () {
        return (this.formSearch.selected_categories !== null && this.formSearch.selected_categories.length) ? this.formSearch.selected_categories[this.formSearch.selected_categories.length - 1] : null;
      },
      getFilters: function () {
        let filters, treeFilters, listFilters = [];
        if (this.formSearch.selected_categories.length) {
          filters = this.types.find(item => item.id === this.formSearch.selected_type)
            .categories
            .find((item) => item.id === this.lastSelectedCategory())
            .filters;
        }
        else {
          filters = this.types.find(item => item.id === this.formSearch.selected_type).filters;
        }
        treeFilters = this.filters.map(filter => {
          let check = filters.findIndex(item => item.filter_id === filter.id || item.filter_id === filter.parent_id);
          if (check !== -1) {
            return filter;
          }
        }).filter(item => {
          return typeof item !== "undefined";
        });

        treeFilters = helperArray.sort(treeFilters);
        treeFilters = arrayToTree(treeFilters);

        listFilters = treeFilters.map(filter => {
          return filter.children.map(filterItem => {
            return {
              id: filterItem.id,
              label: `${filter.name} -> ${filterItem.name}`
            }
          });
        }).flat();

        return listFilters;
      },
      changeSelectCategory: function () {
        this.formSearch.selected_filters = [];
        return this.getFilters();
      },
      onResetSearch: function () {
        this.formSearch = {
          q: '',
          selected_type: null,
          selected_categories: [],
          selected_filters: [],
          only_discounts: 0,
        };
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchProducts', this.formSearch);

        this.getProducts(1);
      },
      onSubmitSearch: function () {
        this.getProducts(1);
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchProducts', this.formSearch);
      },
      btnSelect: function (id) {
        let index = this.products.findIndex(item => item.id === id);
        this.$emit('selectProduct', this.products[index]);
      },
      getProducts: function (page = 1) {
        this.loading = true;
        return ApiProducts.get(page, this.formSearch).then((response) => {
          this.products = response.data.products.data;
          this.total = response.data.products.total;
          this.currentPage = response.data.products.current_page;
          this.pageSize = response.data.products.per_page;
          this.$store.commit('updateProducts', response.data.products);

          this.loading = false;
        });
      },
      handleCurrentPageChange: function (page) {
        this.getProducts(page);
      },
      getFilter: function (id) {
        return this.filters.find(item => item.id === id) ||  {
          name: null
        }
      },
    },
    components: {
      PageElementsPagination,
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
    beforeDestroy() {

    },
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