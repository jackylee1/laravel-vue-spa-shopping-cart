<template>
  <div>
    <Breadcrumbs :items="breadcrumbs"/>

    <section class="cart">
      <div class="container">
        <Errors :type="typeAlerts"
                v-on:clearAlerts="clearAlerts"
                :alerts="alerts"/>

        <div class="row cart_title">
          <div class="col-lg-12">
            <h1>Корзина</h1>
          </div>
        </div>
        <div class="row cart_description">
          <table class="table table-bordered">
            <thead>
            <tr class="head_table">
              <th scope="col"></th>
              <th scope="col">
                <h4>Наименование товара</h4>
              </th>
              <th scope="col">
                <h4>Стоимость</h4>
              </th>
              <th scope="col">
                <h4>Количество</h4>
              </th>
              <th scope="col">
                <h4>Сумма</h4>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in cartProducts">
              <th scope="row c_foto">
                <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                  <img v-if="product.product.main_image !== null"
                       :src="`/app/public/images/products/${product.product.main_image.preview}`"
                       :alt="product.product.name">
                </router-link>
              </th>
              <td class="lefted description_item">
                <router-link class="bold"
                             :to="{name: 'product', params: {slug: product.product.slug}}">
                  {{product.product.name}}
                </router-link>
                <span>
                                    <a @click="deleteProductFromCart(product.id)" href="javascript:void(0)">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </span>
                <template v-for="filter in getAvailable(product.product_available_id, product.product).filters">
                  <p v-html="getParentAndSelectFilter(filter.filter_id)"></p>
                </template>
                <template v-if="getAvailable(product.product_available_id, product.product).quantity < product.quantity">
                  <div class="alert alert-danger" style="margin-top: 5px;padding: 5px;">
                    Нет в наличии. По этим параметрам в наличии: {{getAvailable(product.product_available_id, product.product).quantity}} единиц товара<br>
                    Если в момент создания заказа товар будет оставаться в корзине - он не попадет в заказ.
                  </div>
                </template>
                <br><br>
                <a @click="productAddToFavorite(product.product.id)"
                   href="javascript:void(0)" class="hrt">
                  <i class="far fa-heart"></i> Добавить в избранное
                </a>
              </td>
              <td class="price">
                <p>
                  <strike v-if="product.product.discount_price !== null">
                    {{product.product.price}} грн
                  </strike>
                  {{product.product.current_price}} грн
                </p>
              </td>
              <td class="item_in_cart">
                <div class="input-group spinner">
                  <input type="text"
                         disabled
                         class="form-control"
                         v-model="product.quantity">
                  <div class="input-group-btn-vertical">
                    <button @click="btnChangeCartQuantity(product, 'inc')"
                            class="btn btn-default"
                            type="button">+</button>
                    <button @click="btnChangeCartQuantity(product, 'desc')"
                            class="btn btn-default"
                            type="button">-</button>
                  </div>
                </div>
              </td>
              <td>
                <p>
                  <strike v-if="product.product.discount_price !== null">
                    {{product.product.price * product.quantity}} грн
                  </strike>
                  {{product.product.current_price * product.quantity}} грн
                </p>
              </td>
            </tr>

            <template v-if="cartProducts.length === 0">
              <tr>
                <td colspan="5">
                  <div class="alert alert-info">
                    У Вас нет товаров в корзине
                  </div>
                </td>
              </tr>
            </template>
            </tbody>
          </table>
        </div>
        <div class="row cart_description_mobile">
          <template v-for="product in cartProducts">
            <div class="col-5">
              <router-link :to="{name: 'product', params: {slug: product.product.slug}}">
                <img v-if="product.product.main_image !== null"
                     :src="`/app/public/images/products/${product.product.main_image.preview}`"
                     :alt="product.product.name">
              </router-link>
            </div>
            <div class="col-7 item_in_cart">
              <router-link class="bold"
                           :to="{name: 'product', params: {slug: product.product.slug}}">
                {{product.product.name}}
              </router-link>
              <template v-for="filter in getAvailable(product.product_available_id, product.product).filters">
                <p v-html="getParentAndSelectFilter(filter.filter_id)"></p>
              </template>
              <a @click="deleteProductFromCart(product.id)" href="javascript:void(0)">
                <i class="far fa-trash-alt"></i>
              </a>
              <p>Цена:
                <strike v-if="product.product.discount_price !== null">
                  {{product.product.price}} грн
                </strike>
                {{product.product.current_price}} грн
              </p>
              <p>Сумма
                <strike v-if="product.product.discount_price !== null">
                  {{product.product.price * product.quantity}} грн
                </strike>
                {{product.product.current_price * product.quantity}} грн
              </p>
              <div class="input-group spinner">
                <input type="text" class="form-control"
                       v-model="product.quantity"
                       disabled>
                <div class="input-group-btn-vertical">
                  <button @click="btnChangeCartQuantity(product, 'inc')"
                          class="btn btn-default"
                          type="button">+</button>
                  <button @click="btnChangeCartQuantity(product, 'desc')"
                          class="btn btn-default"
                          type="button">-</button>
                </div>
              </div>
            </div>
          </template>

          <template v-if="cartProducts.length === 0">
            <div class="alert alert-info text-center" style="width: 100%">
              У Вас нет товаров в корзине
            </div>
          </template>
        </div>

        <hr>

        <div v-if="totalPrice > 0" class="row lets_checkout">
          <div class="col-12">
            <p class="items_sum_text">
              ТОВАРОВ НА СУММУ:
              <span class="items_sum_numb">{{totalPrice}} грн</span>
            </p>
            <p v-if="totalPriceDiscount > 0" class="items_sum_text">
              Сумма с учетом пользовательских скидок:
              <span class="items_sum_numb">{{totalPriceDiscount}} грн</span>
            </p>
            <a @click="openCheckout" href="javascript:void(0)">ОФОРМИТЬ ЗАКАЗ</a>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import Breadcrumbs from "../Breadcrumbs";
  import mixinCart from '../../../app/public/mixins/Cart';
  import mixinAlerts from '../../../app/public/mixins/Alerts';
  import mixinFavorite from '../../../app/public/mixins/Favorite';
  import Errors from "../Errors";

  export default {
    name: 'CartLayout',
    mixins: [mixinCart, mixinAlerts, mixinFavorite],
    mounted() {
      this.$scrollTo('#top_line', 650);

      this.breadcrumbs.push({
        title: 'Корзина'
      });
      if (this.cart !== null && this.cart.products.length > 0) {
        this.cartProducts = this.cart.products;
        this.setPrices();
      }
    },
    data() {
      return {
        breadcrumbs: []
      }
    },
    methods: {
      openCheckout: function () {
        this.$router.push({ name: 'checkout' });
      },
      btnChangeCartQuantity: function (product, operation) {
        let oldQuantity = product.quantity;

        let available = product.product.available.find((item) => item.id === product.product_available_id);
        if (operation === 'inc') {
          product.quantity += 1;
        }
        else {
          product.quantity -= 1;
        }
        if (product.quantity > available.quantity) {
          product.quantity = available.quantity;
        }
        if (product.quantity < 1) {
          product.quantity = 1;
        }

        if (oldQuantity !== product.quantity) {
          this.cartUpdateQuantityProduct(product.id, product.quantity).then((result) => {
            if (!result) {
              if (operation === 'inc') {
                product.quantity -= 1;
              }
              else {
                product.quantity += 1;
              }
            }
          });

        }
      }
    },
    components: {Errors, Breadcrumbs},
    metaInfo: {
      title: '| Корзина'
    }
  }
</script>