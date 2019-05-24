import * as ApiProducts from '../api/Products';
import mixinAlerts from './Alerts';

export default {
  mixins: [mixinAlerts],
  created: function () {
    this.debounceProductsAutoComplete = _.debounce(this.productsAutoComplete, 500);
  },
  data() {
    return {
      debounceProductsAutoComplete: null
    }
  },
  methods: {
    productsAutoComplete: function () {
      return false;
      
      if (this.textSearch !== null && this.textSearch.length > 0) {
        ApiProducts.get(1, {
          text: this.textSearch,
          autocomplete: 1
        }).then(res => {
          if (res.data.products !== undefined) {
            console.log(res.data.products);
            $('#desktopAutocomplete').typeahead({
              source: res.data.products
            });
            $('#mobileAutocomplete').typeahead({
              source: res.data.products
            });
          }
        }).catch((error) => {
          this.alerts = error.response.data.errors;
        })
      }
    }
  }
}