<template>
  <div class="col-sm-6 col-12">
    <p><span class="delivery_data">{{cart.user_surname}} {{cart.user_name}}</span></p>
    <template v-if="cart.delivery === 1">
      <p><span class="delivery_data">Доставка Новой почтой</span></p>
      <p v-if="cart.np_area !== undefined && cart.np_area !== null">
        Область: <span class="delivery_data">{{cart.np_area.description}}</span>
      </p>
      <p v-if="cart.np_city !== undefined && cart.np_city !== null">
        Населенный пункт: <span class="delivery_data">{{cart.np_city.description}}</span>
      </p>

      <p v-if="cart.np_warehouse !== undefined && cart.np_warehouse !== null">
        <span class="delivery_data">
            {{cart.np_warehouse.description}}
        </span>
      </p>
    </template>

    <p><span class="delivery_data">Контактный телефон: {{cart.phone}}</span></p>
    <p v-if="cart.payment_method !== undefined && cart.payment_method !== null">
      Способ оплаты: <span class="delivery_data">{{cart.payment_method.name}}</span>
    </p>

    <router-link :to="{ name: 'checkout' }">
      <a href="javascript:void(0)">Отредактировать данные</a>
    </router-link>

    <form data-vv-scope="form-note">
      <div class="delivery_comments">
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Комментарий к заказу:</label>
          <textarea class="form-control"
                    v-model="cart.note"
                    data-vv-as="Комментарий к заказу"
                    name="note"
                    v-validate="'max:2000'"
                    id="exampleFormControlTextarea1"
                    rows="3"></textarea>
          <small v-show="errors.has('form-note.note')" class="text-danger">
            {{ errors.first('form-note.note') }}
          </small>
        </div>
      </div>
    </form>

    <div class="confirm righted">
      <a @click="createOrder"
         href="javascript:void(0)">
        Оформить заказ
      </a>
    </div>
  </div>
</template>

<script>
  import * as ApiCart from '../../../app/public/api/Cart';
  import * as ApiOrder from '../../../app/public/api/Order';

  export default {
    name: 'InformationCart',
    props: ['cart'],
    computed: {
      orders: function () {
        return this.$store.getters.orders;
      }
    },
    methods: {
      createOrder: function () {
        this.$validator.validateAll('form-note').then((result) => {
          if (result) {
            this.cart['cart_key'] = this.cart.key;
            ApiCart.update(this.cart).then((res) => {
              if (res.data.status === 'success') {
                ApiOrder.create({
                  cart_key: this.cart.key
                }).then((res) => {
                  if (res.data.status === 'success') {
                    this.$store.commit('updateCart', res.data.cart);
                    this.alerts = 'Заказ успешно создан. В ближайшее время с Вами свяжется администрация сайта.';
                    this.typeAlerts = 'success';
                    this.$notify({
                      type: 'success',
                      title: 'Заказ успешно создан',
                      text: 'В ближайшее время с Вами свяжется администрация сайта.'
                    });

                    if (this.orders.data !== undefined) {
                      let order = this.orders;
                      order.data.unshift(res.data.order);
                      this.$store.commit('updateOrders', order);
                    }
                    setTimeout(() => {
                      this.$router.push({name: 'view_order', params: {id: res.data.order.id}});
                    }, 2000);
                  }
                }).catch((error) => {
                  this.alerts = error.response.data.errors;
                  this.typeAlerts = 'danger';
                  this.$notify({
                    type: 'error',
                    title: 'Ошибка',
                    text: 'при создании заказа'
                  });
                })
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
      }
    }
  }
</script>