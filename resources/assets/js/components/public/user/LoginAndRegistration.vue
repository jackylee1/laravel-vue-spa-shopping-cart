<template>
  <div>
    <Breadcrumbs :items="breadcrumbs"/>
    <section class="checkout_form">
      <div class="container">
        <div class="row checkout_login">
          <div class="col-md-5 col-12 enter">
            <Login/>
          </div>

          <hr class="vrt_divider">

          <Registration/>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import Breadcrumbs from "../Breadcrumbs";
  import Registration from "./Registration";
  import Login from "./Login";

  export default {
    name: 'LoginAndRegistration',
    mounted() {
      if (this.$store.getters.isLoggedIn) {
        return this.$router.push({name: 'user_information'});
      }

      this.breadcrumbs.push({title: 'Авторизация и регистрация'});
    },
    data() {
      return {
        breadcrumbs: [],
      }
    },
    components: {Registration, Breadcrumbs, Login},
    metaInfo: {
      title: '| Авторизация и регистрация'
    }
  }
</script>