import * as ApiCart from '../api/Cart';

export default {
  computed: {
    cart() {
      return this.$store.getters.cart;
    },
    filters() {
      return this.$store.getters.filters;
    },
    currentUser() {
      return this.$store.getters.currentUser;
    },
  },
  mounted() {
    if (this.cart !== null) {
      this.cartProducts = this.cart.products;
      this.setPrices();
    }
  },
  data() {
    return {
      idAvailable: null,
      maxQuantity: 999,
      valueQuantity: 1,
      minQuantity: 1,
      cartProducts: [],
      totalPrice: null,
      totalPriceDiscount: null,
    };
  },
  methods: {
    notAvailable() {
      if (this.idAvailable === null) {
        this.alerts = 'Товара нет в наличии';
        return true;
      }
      return false;
    },
    setPrices() {
      this.setTotalPrice();
      this.setTotalPriceDiscount();
    },
    changeIdAvailable(id) {
      this.idAvailable = id;
    },
    incValueQuantity() {
      if (this.notAvailable()) {
        return false;
      }
      this.valueQuantity += 1;
    },
    descValueQuantity() {
      if (this.notAvailable()) {
        return false;
      }
      this.valueQuantity -= 1;
    },
    deleteProductFromCart(id) {
      ApiCart.deleteProduct({
        cart_key: this.cart.key,
        id,
      }).then((res) => {
        if (res.data.status === 'success') {
          let cart = this.cart;
          let index = this.cart.products.findIndex(item => item.id === id);
          cart.products.splice(index, 1);
          this.$store.commit('updateCart', cart);

          this.$notify({
            type: 'success',
            title: 'Товар',
            text: 'был успешно удален с корзины',
          });
        }
      }).catch((error) => {
        this.alerts = error.response.data.errors;
        this.$notify({
          type: 'error',
          title: 'Ошибка',
          text: 'при удалении товара с корзины',
        });
      });
    },
    addProductToCart() {
      if (this.notAvailable()) {
        return false;
      }
      ApiCart.addProduct({
        cart_key: this.cart.key,
        product_id: this.product.id,
        product_available_id: this.idAvailable,
        quantity: this.valueQuantity,
      }).then((res) => {
        if (res.data.status === 'success') {
          let cart = this.cart;
          cart.products.unshift(res.data.cart_product);
          this.$store.commit('updateCart', cart);
          $('#modalCartProducts').modal('show');

          this.$notify({
            type: 'success',
            title: 'Товар',
            text: 'был успешно добавлен в корзину',
          });
        }
      }).catch((error) => {
        this.alerts = error.response.data.errors;
        this.$notify({
          type: 'error',
          title: 'Ошибка',
          text: 'при добавлении товара в корзину',
        });
      });
    },
    cartUpdateQuantityProduct(id, quantity) {
      return ApiCart.updateQuantityProduct({
        cart_key: this.cart.key,
        id,
        quantity,
      }).then((res) => {
        if (res.data.status === 'success') {
          this.$notify({
            type: 'success',
            title: 'Данные о количестве товара',
            text: 'были успешно обновлены',
          });

          this.setPrices();

          return true;
        }
      }).catch((error) => {
        this.alerts = error.response.data.errors;
        this.$notify({
          type: 'error',
          title: 'Ошибка',
          text: 'при обновлении данных о количестве товаров',
        });

        return false;
      });
    },
    setTotalPriceDiscount() {
      this.totalPriceDiscount = null;
      let discount = 0;

      if (this.currentUser !== null) {
        if (this.currentUser.discount !== null && this.currentUser.discount > 0) {
          discount = this.currentUser.discount;
        }
        else if (this.currentUser.group !== null) {
          discount = this.currentUser.group.user_group.discount;
        }
      }

      if (this.cart.promotional_code !== null && this.cart.promotional_code.type === 0) {
        let sumDiscount = discount + this.cart.promotional_code.discount;
        discount = (sumDiscount > 100) ? 100 : sumDiscount;
      }

      let price = 0;
      let discountPrice = 0;
      let onlyPrice = 0;

      this.cartProducts.forEach((product) => {
        let available = this.getAvailable(product.product_available_id, product.product);
        if (available.quantity > 0) {
          onlyPrice += (product.product.discount_price == null) ? product.product.price : 0;
          if (product.product.discount_price === null) {
            price += product.product.price * product.quantity;
          }
          else {
            discountPrice += product.product.discount_price * product.quantity;
          }
        }
      });

      if (discount > 0) {
        if (price > 0) {
          this.totalPriceDiscount = discount === 100 ? 0 : (price * ((100 - discount) / 100)) + discountPrice;
        }
      }

      if (this.cart.promotional_code !== null && this.cart.promotional_code.type === 1) {
        let onlyPriceDiffCashValue = (onlyPrice - this.cart.promotional_code.cash_value);
        onlyPriceDiffCashValue = (onlyPriceDiffCashValue < 0) ? 0 : onlyPriceDiffCashValue;
        let differenceCashValue = onlyPriceDiffCashValue + (this.totalPriceDiscount - onlyPrice);
        this.totalPriceDiscount = (differenceCashValue < 0) ? 0 : differenceCashValue;
      }

      if (this.totalPriceDiscount !== null) {
        this.totalPriceDiscount = this.totalPriceDiscount.toFixed(2).replace(/\.?0+$/, '');
      }
    },
    setTotalPrice() {
      this.totalPrice = null;
      let onlyPrice = 0;
      let onlyDiscountPrice = 0;
      if (this.cart !== null) {
        this.cart.products.forEach((item) => {
          let available = this.getAvailable(item.product_available_id, item.product);
          if (available.quantity > 0) {
            if (item.product.discount_price == null) {
              onlyPrice += item.product.price * item.quantity;
            }
            else {
              onlyDiscountPrice += item.product.discount_price * item.quantity;
            }
          }
        });

        if (this.cart.promotional_code !== null && this.cart.promotional_code.type === 1) {
          let onlyPriceDiffCashValue = (onlyPrice - this.cart.promotional_code.cash_value);
          onlyPriceDiffCashValue = (onlyPriceDiffCashValue < 0) ? 0 : onlyPriceDiffCashValue;
          this.totalPrice = onlyPriceDiffCashValue + onlyDiscountPrice;
          this.totalPrice = this.totalPrice.toFixed(2).replace(/\.?0+$/, '');
        }
        else {
          this.totalPrice = (onlyPrice + onlyDiscountPrice).toFixed(2).replace(/\.?0+$/, '');
        }
      }
    },
    getAvailable(availableId, product) {
      return product.available.find(item => item.id === availableId);
    },
    getParentAndSelectFilter(filterId) {
      let filter = this.filters.find(item => item.id === filterId);
      let parent = this.filters.find(item => item.id === filter.parent_id);

      if (filter !== undefined && parent !== undefined) {
        return `<p>${parent.name}: <strong>${filter.name}</strong></p>`;
      }
    },
  },
  watch: {
    idAvailable() {
      if (this.product !== null) {
        let available = this.product.available.find(item => item.id === this.idAvailable);
        this.maxQuantity = available.quantity;
      }
    },
    valueQuantity() {
      if (this.valueQuantity < 0) {
        this.valueQuantity = 0;
      }
      if (this.valueQuantity > this.maxQuantity) {
        this.valueQuantity = this.maxQuantity;

        this.$notify({
          type: 'info',
          title: 'Максимальное количество',
          text: `по этим параметрам равно: ${this.maxQuantity}`,
        });
      }

      this.$emit('setProductPrice', this.valueQuantity);
    },
    cart() {
      this.cartProducts = this.cart.products;
      this.setPrices();
    },
    'cart.products': function () {
      this.setPrices();
    },
    'cart.promotional_code': function () {
      this.setPrices();
    },
  },
};
