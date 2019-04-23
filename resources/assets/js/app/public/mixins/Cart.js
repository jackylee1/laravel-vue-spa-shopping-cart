import * as ApiCart from '../../../app/public/api/Cart';

export default {
    computed: {
        cart: function () {
            return this.$store.getters.cart;
        },
        filters: function () {
            return this.$store.getters.filters;
        },
        currentUser: function () {
            return this.$store.getters.currentUser;
        }
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
            totalPriceDiscount: null
        }
    },
    methods: {
        notAvailable: function () {
            if (this.idAvailable === null) {
                this.alerts = 'Товара нет в наличии';
                return true;
            }
            return false;
        },
        setPrices: function () {
            this.setTotalPrice();
            this.setTotalPriceDiscount();
        },
        changeIdAvailable: function (id) {
            this.idAvailable = id;
        },
        incValueQuantity: function () {
            if (this.notAvailable()) {
                return false;
            }
            this.valueQuantity += 1;
        },
        descValueQuantity: function () {
            if (this.notAvailable()) {
                return false;
            }
            this.valueQuantity -= 1;
        },
        deleteProductFromCart: function (id) {
            ApiCart.deleteProduct({
                cart_key: this.cart.key,
                id: id
            }).then((res) => {
                if (res.data.status === 'success') {
                    let cart = this.cart;
                    let index = this.cart.products.findIndex((item) => item.id === id);
                    cart.products.splice(index, 1);
                    this.$store.commit('updateCart', cart);

                    this.$notify({
                        type: 'success',
                        title: 'Товар',
                        text: 'был успешно удален с корзины'
                    });
                }
            }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.$notify({
                    type: 'error',
                    title: 'Ошибка',
                    text: 'при удалении товара с корзины'
                });
            });
        },
        addProductToCart: function () {
            if (this.notAvailable()) {
                return false;
            }
            ApiCart.addProduct({
                cart_key: this.cart.key,
                product_id: this.product.id,
                product_available_id: this.idAvailable,
                quantity: this.valueQuantity
            }).then((res) => {
                if (res.data.status === 'success') {
                    let cart = this.cart;
                    cart.products.unshift(res.data.cart_product);
                    this.$store.commit('updateCart', cart);

                    this.$notify({
                        type: 'success',
                        title: 'Товар',
                        text: 'был успешно добавлен в корзину'
                    });
                }
            }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.$notify({
                    type: 'error',
                    title: 'Ошибка',
                    text: 'при добавлении товара в корзину'
                });
            });
        },
        cartUpdateQuantityProduct: function (id, quantity) {
            return ApiCart.updateQuantityProduct({
                cart_key: this.cart.key,
                id: id,
                quantity: quantity
            }).then((res) => {
                if (res.data.status === 'success') {
                    this.$notify({
                        type: 'success',
                        title: 'Данные о количестве товара',
                        text: 'был успешно обновлены'
                    });

                    this.setPrices();

                    return true;
                }
            }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.$notify({
                    type: 'error',
                    title: 'Ошибка',
                    text: 'при обновлении данных о количестве товаров'
                });

                return false;
            });
        },
        setTotalPriceDiscount: function () {
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

            if (this.cart.promotional_code !== null) {
                let sumDiscount = discount + this.cart.promotional_code.discount;
                discount = (sumDiscount > 100) ? 100 : sumDiscount;
            }

            if (discount > 0) {
                let price = 0;
                let discountPrice = 0;

                this.cartProducts.forEach((product) => {
                    let available = this.getAvailable(product.product_available_id, product.product);
                    if (available.quantity > 0) {
                        if (product.product.discount_price === null) {
                            price += product.product.price * product.quantity;
                        }
                        else {
                            discountPrice += product.product.discount_price * product.quantity;
                        }
                    }
                });

                if (price > 0) {
                    this.totalPriceDiscount = discount === 100 ? 0 : (price * ((100 - discount) / 100)) + discountPrice;
                }
            }
        },
        setTotalPrice: function () {
            this.totalPrice = null;
            if (this.cart !== null) {
                this.cart.products.forEach((item) => {
                    let available = this.getAvailable(item.product_available_id, item.product);
                    if (available.quantity > 0) {
                        this.totalPrice += item.product.current_price * item.quantity;
                    }
                });
            }
        },
        getAvailable: function(availableId, product) {
            return product.available.find((item) => item.id === availableId);
        },
        getParentAndSelectFilter(filterId) {
            let filter = this.filters.find((item) => item.id === filterId);
            let parent = this.filters.find((item) => item.id === filter.parent_id);

            if (filter !== undefined && parent !== undefined) {
                return `<p>${parent.name}: <strong>${filter.name}</strong></p>`;
            }
        }
    },
    watch: {
        'idAvailable': function () {
            if (this.product !== null) {
                let available = this.product.available.find((item) => item.id === this.idAvailable);
                this.maxQuantity = available.quantity;
            }
        },
        'valueQuantity': function () {
            if (this.valueQuantity < 0) {
                this.valueQuantity = 0;
            }
            if (this.valueQuantity > this.maxQuantity) {
                this.valueQuantity = this.maxQuantity;

                this.$notify({
                    type: 'info',
                    title: 'Максимальное количество',
                    text: `по этим параметрам равно: ${this.maxQuantity}`
                });
            }

            this.$emit('setProductPrice', this.valueQuantity);
        },
        'cart': function () {
            this.cartProducts = this.cart.products;
            this.setPrices();
        },
        'cart.products': function () {
            this.setPrices();
        },
        'cart.promotional_code': function () {
            this.setPrices();
        }
    },
}