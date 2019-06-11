<template>
  <div>
    <section class="submenu">
      <div class="container">
        <div class="row">
          <div class="col-4 phones my-auto">
            <ul>
              <li style="cursor: pointer"
                  @click="openTel(phone1)"
                  v-if="phone1 !== null">
                {{getPhone(phone1)}}
              </li>
              <li style="cursor: pointer"
                  @click="openTel(phone2)"
                  v-if="phone2 !== null">
                {{getPhone(phone2)}}
              </li>
            </ul>
          </div>
          <template v-if="this.isLoggedIn">
            <div class="col-2 heart my-auto">
              <router-link :to="{ name: 'user_information' }">
                <img src="/assets/public/images/cart/home.png" alt="home">
              </router-link>
            </div>
          </template>
          <template v-else>
            <div class="col-2 heart my-auto">
              <router-link :to="{ name: 'login' }">
                <img src="/assets/public/images/cart/signin.png" alt="signin">
              </router-link>
            </div>
          </template>
          <div class="col-2 heart my-auto">
            <router-link :to="{ name: 'user_favorite' }">
              <img src="/assets/public/images/cart/heart.png" alt="heart">
            </router-link>
          </div>
          <div class="col-2 carte my-auto">
            <router-link :to="{ name: 'cart' }">
              <img src="/assets/public/images/cart/cart.png" alt="cart">
            </router-link>
          </div>
          <div class="col-2 search my-auto">
            <a data-toggle="collapse" href="#collapseSubmenu" role="button"
               aria-expanded="false" aria-controls="collapseSubmenu">
              <img src="/assets/public/images/cart/search.png" alt="">
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import { isMobileOnly } from 'mobile-device-detect';

  export default {
    name: 'SubMenu',
    props: ['phone1', 'phone2'],
    computed: {
      getIsMobile: function () {
        return isMobileOnly;
      },
      isLoggedIn: function () {
        return this.$store.getters.isLoggedIn;
      },
    },
    methods: {
      getPhone: function (phone) {
        return (this.getIsMobile) ? phone.replace(/[^0-9]/g, '') : phone;
      },
      openTel: function (phone) {
        window.location.href = `tel:${phone}`;
      }
    }
  }
</script>