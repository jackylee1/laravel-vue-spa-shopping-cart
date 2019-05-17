<template>
  <div class="col-md-4 col-lg-3 col-12 account_sf">
    <ul>
      <template v-if="this.isLoggedIn">
        <li>
          <router-link :to="{ name: 'user_information' }">
            <i class="fas fa-pen"></i>
            Информация
          </router-link>
        </li>

        <li>
          <router-link :to="{ name: 'user_promotional_codes' }">
            <i class="fas fa-percent"></i>
            Промо-коды
          </router-link>
        </li>

        <li>
          <router-link :to="{ name: 'list_orders' }">
            <i class="fas fa-clipboard-list"></i>Заказы
          </router-link>
        </li>
      </template>
      <li>
        <router-link :to="{ name: 'user_favorite' }">
          <i class="fas fa-list-alt"></i>Список избранного
        </router-link>
      </li>
      <template v-if="this.isLoggedIn">
        <li>
          <a @click="logout"
             href="javascript:void(0)"><i class="fas fa-sign-out-alt"></i>Выйти</a>
        </li>
      </template>
    </ul>
  </div>
</template>

<script>
  import * as ApiCommon from '../../../app/public/api/Common';
  import * as ApiUser from '../../../app/public/api/User';

  export default {
    name: 'Sidebar',
    computed: {
      isLoggedIn: function () {
        return this.$store.getters.isLoggedIn;
      }
    },
    methods: {
      logout: function () {
        ApiUser.logout().then((res) => {
          this.$store.commit('logout');
          this.$router.push({name: 'login'});

          ApiCommon.get({
            cart_key: localStorage.getItem('cart_key'),
            favorite_key: localStorage.getItem('favorite_key'),
          }).then((res) => {
            localStorage.setItem('cart_key', res.data.cart.key);
            localStorage.setItem('favorite_key', res.data.favorite.key);

            this.$store.commit('updateFavorite', res.data.favorite);
            this.$store.commit('updateCart', res.data.cart);
          });
        });
      }
    }
  }
</script>