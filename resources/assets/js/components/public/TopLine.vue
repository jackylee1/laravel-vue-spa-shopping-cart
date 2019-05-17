<template>
  <div>
    <section id="top_line" class="top-line" >
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="telephone">
              <span class="first" v-if="phone1 !== null">
                {{phone1}}
              </span>
              <span class="second" v-if="phone2 !== null">
                {{phone2}}
              </span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="authotisation">
              <template v-if="isLoggedIn">
                <router-link class="acount" :to="{name: 'user_information'}">
                  Мой аккаунт
                </router-link>
              </template>
              <template v-else>
                <router-link class="login" :to="{ name: 'login' }">
                  Вход/Регистрация
                </router-link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    name: 'TopLine',
    props: ['phone1', 'phone2'],
    computed: {
      isLoggedIn: function () {
        return this.$store.getters.isLoggedIn;
      }
    }
  }
</script>