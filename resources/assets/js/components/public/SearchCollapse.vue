<template>
  <div class="collapse searchcollapse" id="collapseSubmenu">
    <div class="card card-body">
      <form class="form-inline d-flex justify-content-center"
            action="javascript:void(0)" v-on:keyup.enter="getProducts">
        <input v-model="textSearch"
               v-on:keyup="debounceProductsAutoComplete"
               @blur="resultProductsAutoComplete = []"
               name="mobileAutocomplete"
               id="mobileAutocomplete"
               class="form-control"
               type="search"
               placeholder="Введите поисковое слово"
               aria-label="Поиск">
        <ListProductsAutoComplete v-on:selectResultAutoComplete="selectResultAutoComplete"
                                  :resultProductsAutoComplete="resultProductsAutoComplete"/>
        <button @click="getProducts"
                class="btn">Поиск</button>
      </form>
    </div>
  </div>
</template>

<script>
  import * as ApiProducts from '../../app/public/api/Products';
  import ListProductsAutoComplete from "./product/ListProductsAutoComplete";

  export default {
    name: 'SearchCollapse',
    created: function () {
      this.debounceProductsAutoComplete = _.debounce(this.productsAutoComplete, 500);
    },
    mounted() {
      this.textSearch = this.searchByText;
    },
    computed: {
      searchByText: function () {
        return this.$store.getters.searchByText;
      },
    },
    data() {
      return {
        textSearch: null,
        resultProductsAutoComplete: [],
        debounceProductsAutoComplete: null,
      }
    },
    methods: {
      selectResultAutoComplete: function (value) {
        this.textSearch = value;
        this.resultProductsAutoComplete = [];
      },
      productsAutoComplete: function () {
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
        this.$store.commit('updateSearchByText', this.textSearch);
        this.$router.push({ query: Object.assign({}, this.$route.query, { text: this.textSearch }) });

        this.$emit('getProducts');
      },
    },
    components: { ListProductsAutoComplete },
    watch: {
      'searchByText': function (value) {
        this.textSearch = value;
      },
    }
  }
</script>