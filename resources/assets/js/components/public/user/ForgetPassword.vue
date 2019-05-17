<template>
  <div>
    <Breadcrumbs :items="breadcrumbs"/>
    <section class="checkout_form" style="text-align: center;">
      <div class="container">
        <div class="row checkout_login" style="display: inline-block;min-width: 70%">
          <form data-vv-scope="form-forget-password" >
            <div class="form-group row">
              <label for="email" class="col-lg-4 col-form-label">
                E-Mail <span class="validate">*</span></label>
              <div class="col-lg-8">
                <input v-model="email"
                       data-vv-as="E-Mail"
                       name="email"
                       v-validate="'required|email'"
                       type="text"
                       class="form-control"
                       id="email"
                       placeholder="Введите Ваш E-Mail">
                <small v-show="errors.has('form-forget-password.email')" class="text-danger">{{ errors.first('form-forget-password.email') }}</small>
              </div>
            </div>

            <Errors  style="margin-top: 10px" :type="typeAlerts"
                     v-on:clearAlerts="clearAlerts"
                     :alerts="alerts"/>

            <div class="form-group row login_button righted">
              <div class="col-lg-12">
                <div class="log_button" style="margin-top: 10px">
                  <a @click="onSubmit" href="javascript:void(0)"
                     class="submit_login">
                    Выслать инструкцию
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import * as ApiUser from '../../../app/public/api/User';
  import Breadcrumbs from "../Breadcrumbs";
  import mixinAlerts from '../../../app/public/mixins/Alerts';
  import Errors from "../Errors";

  export default {
    name: 'ForgetPassword',
    mixins: [mixinAlerts],
    mounted() {
      if (this.$store.getters.isLoggedIn) {
        return this.$router.push({name: 'user_information'});
      }

      this.breadcrumbs = [
        {title: 'Авторизация и регистрация', route_name: 'login'},
        {title: 'Восстановление пароля'}
      ];
    },
    methods: {
      onSubmit: function () {
        this.$validator.validateAll('form-forget-password').then((result) => {
          if (result) {
            ApiUser.sendResetPassword({
              email: this.email
            }).then((res) => {
              if (res.data.status === 'success') {
                this.typeAlerts = 'success';
                this.alerts = res.data.message;
                this.email = null;
              }
            }).catch((error) => {
              this.typeAlerts = 'danger';
              this.alerts = error.response.data.errors;
              this.$notify({
                type: 'error',
                title: 'Ошибка',
                text: 'при выполнении запроса'
              });
            })
          }
        });
      }
    },
    data() {
      return {
        breadcrumbs: [],
        email: '',

      }
    },
    components: { Breadcrumbs, Errors },
    metaInfo: {
      title: '| Авторизация и регистрация | Восстановление пароля'
    }
  }
</script>