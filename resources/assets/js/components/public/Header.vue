<template>
  <div>
    <header class="header" id="header">
      <div class="container">
        <div class="row">
          <div class="col-sm-3 logo">
            <router-link class="navbar-brand logotype" :to="{name: 'index'}">
              <img class="navbar_logo" src="/assets/public/images/logo.png" alt="FitClothing">
            </router-link>
            <p class="slogan">брендовая спортивная одежда</p>
          </div>
          <div class="col-sm-7 searching">
            <form class="form-inline" action="javascript:void(0)" v-on:keyup.enter="getProducts">
              <input v-model="textSearch"
                     v-on:keyup="debounceProductsAutoComplete"
                     name="desktopAutocomplete" id="desktopAutocomplete"
                     data-provide="typeahead" autocomplete="off"
                     class="form-control typeahead form-control-sm mr-3" type="text"
                     placeholder="Введите поисковое слово в этом поле..."
                     aria-label="Поиск">
              <i class="fas fa-search"
                 @click="getProducts"
                 aria-hidden="true"></i>
              <ListProductsAutoComplete v-on:selectResultAutoComplete="selectResultAutoComplete"
                                        :resultProductsAutoComplete="resultProductsAutoComplete"/>
            </form>
          </div>
          <div class="col-sm-1">
            <router-link :to="{ name: 'user_favorite' }">
              <img class="heart" src="/assets/public/images/cart/heart_white.png" alt="Heart">
              <span v-if="countFavoriteProducts > 0" class="badge">{{countFavoriteProducts}}</span>
            </router-link>
          </div>
          <div class="col-sm-1">
            <div class="dropdown">
              <router-link :to="{ name: 'cart' }">
                <img class="cart" src="/assets/public/images/cart/cart_white.png" alt="">
                <span v-if="countCartProducts > 0" class="badge">{{countCartProducts}}</span>
              </router-link>

              <ModalProducts/>

            </div>
          </div>
        </div>
      </div>
    </header>
  </div>
</template>

<script>
  import * as ApiProducts from '../../app/public/api/Products';
  import mixinAlerts from '../../app/public/mixins/Alerts';
  import ModalProducts from "./cart/ModalProducts";
  import ListProductsAutoComplete from "./product/ListProductsAutoComplete";

  export default {
    name: 'Header',
    mixins: ['mixinAlerts'],
    components: {ListProductsAutoComplete, ModalProducts },
    created: function () {
      this.debounceProductsAutoComplete = _.debounce(this.productsAutoComplete, 500);
    },
    mounted() {
      this.textSearch = this.searchByText;

      if (this.favoriteStore !== null) {
        this.countFavoriteProducts = this.favoriteStore.products.length;
      }
    },
    computed: {
      searchByText: function () {
        return this.$store.getters.searchByText;
      },
      favoriteStore: function () {
        return this.$store.getters.favorite;
      },
      cartStore: function () {
        return this.$store.getters.cart;
      }
    },
    data() {
      return {
        textSearch: null,
        countFavoriteProducts: 0,
        countCartProducts: 0,
        resultProductsAutoComplete: [],
        debounceProductsAutoComplete: null,
      }
    },
    methods: {
      selectResultAutoComplete: function (value) {
        this.textSearch = value;
        this.resultProductsAutoComplete = [];
        this.getProducts();
      },
      productsAutoComplete: function () {
        if (this.textSearch === this.searchByText) {
          return false;
        }
        if (this.textSearch !== null && this.textSearch.length > 0) {
          ApiProducts.get(1, {
            text: this.textSearch,
            autocomplete: 1
          }).then(res => {
            if (res.data.products !== undefined) {
              this.resultProductsAutoComplete = res.data.products;
            }
          }).catch((error) => {
            this.alerts = error.response.data.errors;
          })
        }
        else {
          this.resultProductsAutoComplete = [];
        }
      },
      getProducts: function () {
        this.resultProductsAutoComplete = [];
        this.$store.commit('updateSearchByText', this.textSearch);
        this.$router.push({ query: Object.assign({}, this.$route.query, { text: this.textSearch }) });

        this.textSearch = null;
        this.$emit('getProducts');
      },
      setCountFavoriteProducts: function () {
        this.countFavoriteProducts = this.favoriteStore.products.length;
      },
      setCountCartProducts: function () {
        this.countCartProducts = this.cartStore.products.length;
      }
    },
    watch: {
      'favoriteStore': function () {
        this.setCountFavoriteProducts();
      },
      'favoriteStore.products': function () {
        this.setCountFavoriteProducts();
      },
      'cartStore': function () {
        this.setCountCartProducts();
      },
      'cartStore.products': function () {
        this.setCountCartProducts();
      },
      '$route': function () {
        this.textSearch = null;
      }
    }
  }
</script>