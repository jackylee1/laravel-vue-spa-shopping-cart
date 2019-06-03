<template>
  <div>
    <VueLoading :active.sync="isLoading" color="#df1e30" />

    <Breadcrumbs :items="breadcrumbs" />

    <section class="checkout_form" style="max-width: 800px;display: block;margin-left: auto;margin-right: auto;">
      <div class="container">
        <div class="row checkout_login">
          <div class="col-12 without_reg" v-if="cart !== null">
            <h3 class="text-center">Контактные данные</h3>
            <form data-vv-scope="form-checkout">
              <div class="form-group row">
                <label for="user_surname" class="col-lg-4 col-form-label">Фамилия</label>
                <div class="col-lg-8">
                  <input type="text"
                         v-model="cart.user_surname"
                         data-vv-as="Фамилия"
                         name="user_surname"
                         v-validate="'max:50'"
                         class="form-control"
                         id="user_surname"
                         placeholder="Введите Фамилию">
                  <small v-show="errors.has('form-checkout.user_surname')" class="text-danger">
                    {{ errors.first('form-checkout.user_surname') }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <label for="user_name" class="col-lg-4 col-form-label">Имя <span class="validate">*</span></label>
                <div class="col-lg-8">
                  <input type="text"
                         v-model="cart.user_name"
                         data-vv-as="Имя"
                         name="user_name"
                         v-validate="'required|max:50'"
                         class="form-control"
                         id="user_name"
                         placeholder="Введите Имя">
                  <small v-show="errors.has('form-checkout.user_name')" class="text-danger">
                    {{ errors.first('form-checkout.user_name') }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <label for="phone" class="col-lg-4 col-form-label">Телефон <span class="validate">*</span></label>
                <div class="col-lg-8">
                  <input type="text"
                         v-model="cart.phone"
                         data-vv-as="Телефон"
                         name="phone"
                         v-validate="'required|max:191'"
                         class="form-control"
                         id="phone"
                         placeholder="Введите Телефон">
                  <small v-show="errors.has('form-checkout.phone')" class="text-danger">
                    {{ errors.first('form-checkout.phone') }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-lg-4 col-form-label">E-Mail</label>
                <div class="col-lg-8">
                  <input type="text"
                         v-model="cart.email"
                         data-vv-as="E-Mail"
                         name="email"
                         v-validate="'email'"
                         class="form-control"
                         id="email"
                         placeholder="Введите E-Mail">
                  <small v-show="errors.has('form-checkout.email')" class="text-danger">
                    {{ errors.first('form-checkout.email') }}
                  </small>

                  <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           v-model="subscribe"
                           name="subscribe"
                           checked="checked"
                           id="subscribe">
                    <label class="form-check-label" for="subscribe">
                      Подписаться на акции и новинки
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="delivery" class="col-lg-4 col-form-label">Способ доставки</label>
                <div class="col-lg-8">
                  <v-select v-model="cart.delivery"
                            :reduce="delivery => delivery.value"
                            data-vv-as="Способ доставки"
                            name="delivery"
                            v-validate="'required'"
                            :clearable="false"
                            id="delivery"
                            placeholder="Выберите способ доставки"
                            :options="delivery" label="name">
                  </v-select>
                  <small v-show="errors.has('form-checkout.delivery')" class="text-danger">
                    {{ errors.first('form-checkout.delivery') }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <label for="area" class="col-lg-4 col-form-label">Выберите область</label>
                <div class="col-lg-8">
                  <v-select v-model="cart.area_id"
                            :reduce="areas => areas.ref"
                            data-vv-as="Область"
                            name="area_id"
                            v-validate="'required'"
                            :clearable="false"
                            v-on:input="changeSelect('area')"
                            id="area"
                            placeholder="Выберите область"
                            :options="areas" label="description">
                    <div slot="no-options">По запросу нет данных</div>
                  </v-select>
                  <small v-show="errors.has('form-checkout.area_id')" class="text-danger">
                    {{ errors.first('form-checkout.area_id') }}
                  </small>
                </div>
              </div>
              <div v-if="cities !== undefined && cities.length" class="form-group row">
                <label for="city" class="col-lg-4 col-form-label">Выберите населенный пункт</label>
                <div class="col-lg-8">
                  <v-select v-model="cart.city_id"
                            :reduce="cities => cities.ref"
                            data-vv-as="Населенный пункт"
                            name="city_id"
                            v-validate="'required'"
                            :clearable="false"
                            v-on:input="changeSelect('city')"
                            id="city"
                            placeholder="Выберите населенный пункт"
                            :options="cities" label="description">
                    <div slot="no-options">По запросу нет данных</div>
                  </v-select>
                  <small v-show="errors.has('form-checkout.city_id')" class="text-danger">
                    {{ errors.first('form-checkout.city_id') }}
                  </small>
                </div>
              </div>
              <div v-if="warehouses !== undefined && warehouses.length" class="form-group row">
                <label for="warehouse" class="col-lg-4 col-form-label">Выберите отделение</label>
                <div class="col-lg-8">
                  <v-select v-model="cart.warehouse_id"
                            :reduce="warehouses => warehouses.ref"
                            data-vv-as="Отделение"
                            name="warehouse_id"
                            v-validate="'required'"
                            :clearable="false"
                            id="warehouse"
                            placeholder="Выберите отделение"
                            :options="warehouses" label="description">
                    <div slot="no-options">По запросу нет данных</div>
                  </v-select>
                  <small v-show="errors.has('form-checkout.warehouse_id')" class="text-danger">
                    {{ errors.first('form-checkout.warehouse_id') }}
                  </small>
                </div>
              </div>
              <div class="form-group row">
                <label for="order_payment_method_id" class="col-lg-4 col-form-label">Способ оплаты</label>
                <div class="col-lg-8">
                  <v-select v-model="cart.order_payment_method_id"
                            id="order_payment_method_id"
                            data-vv-as="Способ оплаты"
                            name="order_payment_method_id"
                            v-validate="'required'"
                            :reduce="paymentMethods => paymentMethods.id"
                            :clearable="false"
                            placeholder="Выберите способ оплаты"
                            :options="paymentMethods" label="name">
                    <div slot="no-options">По запросу нет данных</div>
                  </v-select>
                  <small v-show="errors.has('form-checkout.order_payment_method_id')" class="text-danger">
                    {{ errors.first('form-checkout.order_payment_method_id') }}
                  </small>
                </div>
              </div>

              <Errors :type="typeAlerts"
                      v-on:clearAlerts="clearAlerts"
                      :alerts="alerts"/>
            </form>
            <div class="next_button">
              <a @click="saveCart" href="javascript:void(0)">Продолжить оформление</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import Login from "../../public/user/Login";
  import * as ApiNovaPoshta from '../../../app/public/api/NovaPoshta';
  import mixinAlerts from '../../../app/public/mixins/Alerts';
  import * as ApiCart from '../../../app/public/api/Cart';
  import Breadcrumbs from "../Breadcrumbs";
  import Errors from "../Errors";
  import VueLoading from "vue-loading-overlay/src/js/Component";

  export default {
    name: 'Checkout',
    mixins: [mixinAlerts],
    computed: {
      currentUser: function () {
        return this.$store.getters.currentUser;
      },
      cartStorage: function () {
        return this.$store.getters.cart;
      },
      paymentMethodsStore: function () {
        return this.$store.getters.paymentMethods;
      },
      areasStore: function () {
        return this.$store.getters.areas;
      },
      citiesStore: function () {
        return this.$store.getters.cities;
      },
      warehousesStore: function () {
        return this.$store.getters.warehouses;
      }
    },
    mounted() {
      this.$scrollTo('#top_line', 650);

      if (this.cartStorage !== null) {
        setTimeout(() => {
          this.cart = this.cartStorage;
          this.checkCartProducts();
          this.setUserData();
          this.isLoading = false;
        }, 500);
      }

      if (this.areasStore.length === 0) {
        ApiNovaPoshta.areas().then((res) => {
          if (res.data.status === 'success') {
            this.areas = res.data.areas;
            this.$store.commit('updateAreas', this.areas);
          }
        });
      }
      else {
        this.areas = this.areasStore;
      }

      if (this.paymentMethodsStore.length) {
        this.paymentMethods = this.paymentMethodsStore;
      }

      this.breadcrumbs.push({title: 'Корзина', route_name: 'cart'});
      this.breadcrumbs.push({title: 'Оформление и ввод данных'});
    },
    data() {
      return {
        cart: null,
        breadcrumbs: [],
        paymentMethods: [],
        isLoading: true,
        isFullPage: true,
        delivery: [
          {name: 'Новая почта', value: 1}
        ],
        subscribe: true,
        areas: [],
        cities: this.citiesStore,
        warehouses: this.warehousesStore
      }
    },
    methods: {
      changeSelect: function (select) {
        switch (select) {
          case 'area':
            this.cart.city_id = null;
            this.cart.warehouse_id = null;
            this.cities = [];
            this.warehouses = [];
            break;
          case 'city':
            this.cart.warehouse_id = null;
            this.warehouses = [];
            break;
        }
      },
      saveCart: function () {
        this.$validator.validateAll('form-checkout').then((result) => {
          if (result) {
            this.cart.subscribe = this.subscribe;
            this.cart.cart_key = this.cart.key;
            ApiCart.update(this.cart).then((res) => {
              if (res.data.status === 'success') {
                this.$store.commit('updateCart', res.data.cart);

                this.$notify({
                  type: 'success',
                  title: 'Данные',
                  text: 'о заказе успешно обновлены'
                });

                setTimeout(() => {
                  this.$router.push({name: 'confirm'});
                }, 1000)
              }
            }).catch((error) => {
              this.alerts = error.response.data.errors;
              this.typeAlerts = 'danger';
              this.$notify({
                type: 'error',
                title: 'Ошибка',
                text: 'при обновлении данных заказа'
              });
            });
          }
          else {
            this.$notify({
              type: 'error',
              title: 'Ошибка',
              text: 'При валидации формы. Проверте правильность ввода.'
            });
          }
        });
      },
      checkCartProducts: function () {
        if (this.cart.products.length === 0) {
          this.$router.push({name: 'cart'});
        }
      },
      setUserData: function () {
        if (this.cart.user_name === null && this.currentUser !== null) {
          this.cart.user_name = this.currentUser.user_name;
        }
        if (this.cart.user_surname === null && this.currentUser !== null) {
          this.cart.user_surname = this.currentUser.user_surname;
        }
        if (this.cart.phone === null && this.currentUser !== null) {
          this.cart.phone = this.currentUser.phone;
        }
        if (this.cart.email === null && this.currentUser !== null) {
          this.cart.email = this.currentUser.email;
        }
      }
    },
    watch: {
      'cartStorage': function (cart) {
        this.cart = cart;
        this.checkCartProducts();

        this.setUserData();

        this.isLoading = false;
      },
      'paymentMethodsStore': function (methods) {
        this.paymentMethods = methods;
      },
      'cart.area_id': function (area) {
        if (area !== null) {
          ApiNovaPoshta.cities({
            area_ref: area
          }).then((res) => {
            if (res.data.status === 'success') {
              this.cities = res.data.cities;
              this.$store.commit('updateCities', this.cities);

              if (this.cities.length === 0) {
                this.$notify({
                  type: 'info',
                  title: 'По запросу',
                  text: 'нет населенных пунктов'
                });
              }
            }
          });
        }
      },
      'cart.city_id': function (city) {
        if (city !== null) {
          ApiNovaPoshta.warehouses({
            city_ref: city
          }).then((res) => {
            if (res.data.status === 'success') {
              this.warehouses = res.data.warehouses;
              this.$store.commit('updateWarehouses', this.warehouses);

              if (this.warehouses.length === 0) {
                this.$notify({
                  type: 'info',
                  title: 'По запросу',
                  text: 'нет отделений'
                });
              }
            }
          });
        }
      },
    },
    beforeDestroy() {
      this.$store.commit('updateCart', this.cart);
    },
    components: {Errors, Breadcrumbs, Login, VueLoading},
    metaInfo: {
      title: '| Корзина | Оформление и ввод данных'
    }
  }
</script>