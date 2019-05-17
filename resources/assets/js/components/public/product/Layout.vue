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

        <RecommendedProducts :products="recommendedProducts"/>
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

        if (this.product !== undefined) {
          this.setMetaTags();
        }

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
        recommendedProducts: [],
        product: null,
        breadcrumbs: [],
        isLoading: true,
        dataLoad: [],
        price: 0,
        currentPrice: 0,
        mTitle: '',
        mDescription: '',
        mKeywords: '',
        mImage: '',
      }
    },
    methods: {
      setMetaTags: function () {
        this.mTitle = this.mDescription = this.mKeywords = this.mImage = '';

        this.mTitle = (this.product.m_title !== null) ? `| ${this.product.m_title}` : '';
        this.mDescription = (this.product.m_description !== null) ? this.product.m_description : '';
        this.mKeywords = (this.product.m_keywords !== null) ? this.product.m_keywords : '';
        if (this.product.main_image !== null) {
          this.mImage = `${location.protocol+'//'+location.hostname}/app/public/images/products/${this.product.main_image.origin}`;
        }
      },
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

            this.setMetaTags();

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
      getRecommendedProducts: function (typeId, categoryId) {
        categoryId = (Array.isArray(categoryId)) ? _.last(categoryId) : categoryId;

        if (typeId !== null && categoryId !== null) {
          ApiProducts.get(1, {
            type: typeId,
            category: categoryId,
            limit: 8
          }).then((res) => {
            this.recommendedProducts = res.data.products;
          }).catch((error) => {
            this.alerts = error.response.data.errors;
            this.$notify({
              type: 'error',
              title: 'Ошибка',
              text: 'при получении рекомендованных товаров'
            });
          });
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
          this.getRecommendedProducts(this.product.main_type.type_id, this.product.main_type.category_id)
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
          this.getRecommendedProducts(typeId, categoryId);
        }
      }
    },
    watch: {
      '$route' (to, from){
        this.breadcrumbs = [];
        this.productView();
        this.$scrollTo('#top_line', 650);
      },
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