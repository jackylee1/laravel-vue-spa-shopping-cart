<template>
  <div>
    <VueLoading :active.sync="isLoading" color="#df1e30"/>

    <Breadcrumbs :items="breadcrumbs"/>

    <section class="wrapper categories">
      <div class="container">
        <Errors  style="margin-top: 10px" :type="typeAlerts"
                 v-on:clearAlerts="clearAlerts"
                 :alerts="alerts"/>

        <Sort v-on:getProducts="getProducts"
              v-on:updateSort="updateSort"
              :currentCategory="currentCategory"
              :currentType="currentType"/>

        <Filters :currentType="currentType"
                 :currentCategory="currentCategory"
                 v-on:changeIsLoading="changeIsLoading"
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
  import mixinAlerts from '../../../app/public/mixins/Alerts';
  import Breadcrumbs from "../Breadcrumbs";
  import Products from "./Products";
  import Sort from "./Sort";
  import Filters from "./Filters";
  import Errors from "../Errors";
  import Pagination from "../Pagination";
  import VueLoading from "vue-loading-overlay/src/js/Component";

  export default {
    name: 'CatalogLayout',
    mixins: [mixinAlerts],
    mounted() {
      if (this.types.length) {
        this.setTypesAndBreadcrumbs();
      }

      this.$watch(vm => [vm.currentType, vm.currentCategory], val => {
        this.isLoading = true;

        let intervalId = setInterval(() => {
          let checkType = this.intervalData[0] === this.currentType;
          let checkCategory = this.intervalData[1] === this.currentCategory;
          if (checkType && checkCategory) {
            this.getProducts(this.$route.query.page);
            clearInterval(intervalId);
          }
          else {
            this.intervalData = [this.currentType, this.currentCategory];
          }
        }, 500);
      });

      this.$store.commit('updateSearchByText', this.$router.currentRoute.query.text);
    },
    computed: {
      activeFilters: function () {
        return this.$store.getters.activeFilters;
      },
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
        intervalData: [],
        mTitle: '',
        mDescription: '',
        mKeywords: '',
        mImage: '',
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
      setMetaTags: function () {
        this.mTitle = this.mDescription = this.mKeywords = this.mImage = '';

        if (this.currentType !== null && this.currentType.m_title !== null) {
          this.mTitle += `| ${this.currentType.m_title}`;
        }

        if (this.currentCategory !== null && this.currentCategory.m_title !== null) {
          this.mTitle += `| ${this.currentCategory.m_title}`;
        }

        let model = (this.currentCategory === null) ? this.currentType : this.currentCategory;
        if (model !== null) {
          this.mDescription = (model.m_description !== null) ? model.m_description : '';
          this.mKeywords = (model.m_keywords !== null) ? model.m_keywords : '';
        }
        else {
          this.mTitle = '| ';
          switch (this.sort) {
            case 'all':
              this.mTitle += 'Все товары';
              break;
            case 'from_cheap_to_expensive':
              this.mTitle += 'От дешевых к дорогим';
              break;
            case 'from_expensive_to_cheap':
              this.mTitle += 'От дорогих к дешевым';
              break;
            case 'popular':
              this.mTitle += 'Популярные';
              break;
            case 'new':
              this.mTitle += 'Новинки';
              break;
            case 'promotional':
              this.mTitle += 'Акционные';
              break;
          }
        }

        if (this.currentType !== null && this.currentType.image_preview !== null) {
          this.mImage = `${location.protocol + '//' + location.hostname}/app/public/images/type/${this.currentType.image_preview}`;
        }
      },
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
      getTypeIdAndCategoryId: function () {
        return {
          type_id: (this.currentType !== null) ? this.currentType.id : null,
          category_id: (this.currentCategory !== null) ? this.currentCategory.id : null
        };
      },
      removeLoadActiveFilters: function (str) {
        return str.replace('load_active_filter=1', '').replace('load_active_filter=0', '');
      },
      getSort: function () {
        return (this.$route.query.sort !== undefined
          && this.$route.query.sort !== null)
          ? this.$route.query.sort
          : 'all';
      },
      getProducts: function (page = 1) {
        this.isLoading = true;

        let statusActiveFilters = 1;
        if (this.activeFilters.length) {
          let selectActiveFilter = _.filter(this.activeFilters, {
            type_id: this.getTypeIdAndCategoryId().type_id,
            category_id: this.getTypeIdAndCategoryId().category_id,
            sort: this.getSort()
          });
          if (selectActiveFilter.length) {
            statusActiveFilters = 0;
          }
        }

        this.$router.push({ query: Object.assign({}, this.$route.query, {
          page: page,
          load_active_filter: statusActiveFilters
        }) });

        if (this.removeLoadActiveFilters(this.$router.currentRoute.fullPath) === this.urlPrevious) {
          this.setProducts(this.productsStore);
        }
        else {
          setTimeout(() => {
            if (this.removeLoadActiveFilters(this.$router.currentRoute.fullPath) !== this.urlPrevious) {
              this.$store.commit('updateTypePrevious', this.currentType);
              this.$store.commit('updateCategoryPrevious', this.currentCategory);

              ApiProducts.get(page, {
                type: this.getTypeIdAndCategoryId().type_id,
                category: this.getTypeIdAndCategoryId().category_id,
                filters: this.$route.query.filters,
                sort: this.sort,
                text: this.$store.getters.searchByText,
                load_active_filter: statusActiveFilters
              }).then((res) => {
                this.$store.commit('updateUrlPrevious', this.removeLoadActiveFilters(this.$router.currentRoute.fullPath));

                this.$store.commit('updateProducts', res.data.products);

                if (res.data.active_filters !== undefined) {
                  let activeFiltersData = this.activeFilters;
                  let index = activeFiltersData.findIndex((item) => {
                    return item.type_id === this.getTypeIdAndCategoryId().type_id
                      && item.category_id === this.getTypeIdAndCategoryId().category_id
                      && item.sort === this.getSort()
                  });
                  if (index === -1) {
                    activeFiltersData.push({
                      type_id: this.getTypeIdAndCategoryId().type_id,
                      category_id: this.getTypeIdAndCategoryId().category_id,
                      filters: res.data.active_filters,
                      sort: this.getSort()
                    });

                    this.$store.commit('updateActiveFilters', activeFiltersData);
                  }
                }

                this.setProducts(res.data.products);
              }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.$notify({
                  type: 'error',
                  title: 'Ошибка',
                  text: 'при выполнеении запроса'
                });
                this.isLoading = false;
              });
            }
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
          if (type.categories !== undefined) {
            type.categories.forEach((category) => {
              if (category.slug === this.$route.query.category) {
                tempCategory = category;
              }
            });
          }
        });
        [this.currentType, this.currentCategory] = [tempType, tempCategory];

        if (this.currentType !== null) {
          this.breadcrumbs.push({
            title: this.currentType.name,
            route: `{ "name": "catalog", "query": { "type": "${this.currentType.slug}"} }`
          });
        }
        if (this.currentCategory !== null) {
          if (this.currentCategory.parent_id !== 1) {
            let parentCategory = this.currentType.categories.find(item => item.id === this.currentCategory.parent_id);
            this.breadcrumbs.push({
              title: parentCategory.name,
              route: `{ "name": "catalog", "query": { "type": "${this.currentType.slug}", "category": "${parentCategory.slug}"} }`
            });
          }
          this.breadcrumbs.push({
            title: this.currentCategory.name
          });
        }
        this.setMetaTags();
      },
      changeIsLoading: function (value) {
        this.isLoading = value;
      }
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
    },
    metaInfo() {
      return {
        title: this.mTitle,
        meta: [
          { name: 'description', content: this.mDescription },
          { name: 'keywords', content: this.mKeywords },

          {property: 'og:title', content: this.mTitle},
          {property: 'og:site_name', content: 'FitClothing'},
          {property: 'og:type', content: 'website'},
          {property: 'og:url', content: window.location.href},
          {property: 'og:image', content: this.mImage},
          {property: 'og:description', content: this.mDescription},

          {name: 'twitter:card', content: 'summary'},
          {name: 'twitter:site', content: window.location.href},
          {name: 'twitter:title', content: this.mTitle},
          {name: 'twitter:description', content: this.mDescription},
          {name: 'twitter:image:src', content: this.mImage},

          { itemprop: 'image', content: this.mImage }
        ]
      }
    }
  }
</script>