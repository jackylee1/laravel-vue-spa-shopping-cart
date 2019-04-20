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
    data() {
        return {
            idAvailable: null,
            maxQuantity: 999,
            valueQuantity: 1,
            minQuantity: 1,
            cartProducts: [],
            totalPrice: null,
            totalPriceUserGroup: null
        }
    },
    methods: {
        changeIdAvailable: function (id) {
            this.idAvailable = id;
        },
        incValueQuantity: function () {
            this.valueQuantity += 1;
        },
        descValueQuantity: function () {
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
        setTotalPriceUserGroup: function () {
            this.totalPriceUserGroup = null;
            if (this.currentUser !== null && this.currentUser.group !== null) {
                this.totalPriceUserGroup = this.totalPrice * ((100 - this.currentUser.group.user_group.discount) / 100);
            }
        },
        setTotalPrice: function () {
            this.totalPrice = null;
            this.cart.products.forEach((item) => {
                this.totalPrice += item.product.current_price * item.quantity;
            });
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
            if (this.valueQuantity < 1) {
                this.valueQuantity = 1;
            }
            else if (this.valueQuantity > this.maxQuantity) {
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
            this.setTotalPrice();
            this.setTotalPriceUserGroup();
        },
        'cart.products': function () {
            this.setTotalPrice();
            this.setTotalPriceUserGroup();
        }
    },
}