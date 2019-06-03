<template>
  <div class="col-md-6 col-12 without_reg">
    <h3>Зарегистрироваться</h3>
    <form data-vv-scope="form-reg">
      <div class="form-group row">
        <label for="user_surname" class="col-lg-4 col-form-label">
          Фамилия <span class="validate">*</span>
        </label>
        <div class="col-lg-8">
          <input type="text"
                 v-model="form.surname"
                 v-validate="'required|max:50'"
                 data-vv-as="Фамилия"
                 class="form-control" id="user_surname" name="user_surname"
                 placeholder="Введите Фамилию">
          <small v-show="errors.has('form-reg.user_surname')" class="text-danger">
            {{ errors.first('form-reg.user_surname') }}
          </small>
        </div>
      </div>
      <div class="form-group row">
        <label for="user_name" class="col-lg-4 col-form-label">
          Имя <span class="validate">*</span>
        </label>
        <div class="col-lg-8">
          <input type="text"
                 v-model="form.name"
                 v-validate="'required|max:50'"
                 data-vv-as="Имя"
                 class="form-control" id="user_name" name="user_name"
                 placeholder="Введите Имя">
          <small v-show="errors.has('form-reg.user_name')" class="text-danger">
            {{ errors.first('form-reg.user_name') }}
          </small>
        </div>
      </div>
      <div class="form-group row">
        <label for="phone" class="col-lg-4 col-form-label">
          Телефон <span class="validate">*</span>
        </label>
        <div class="col-lg-8">
          <input type="text"
                 v-model="form.phone"
                 v-validate="'required|'"
                 data-vv-as="Телефон"
                 class="form-control"
                 id="phone" name="phone"
                 placeholder="Введите Телефон">
          <small v-show="errors.has('form-reg.phone')" class="text-danger">
            {{ errors.first('form-reg.phone') }}
          </small>
        </div>
      </div>

      <div class="form-group row">
        <label for="email" class="col-lg-4 col-form-label">
          E-Mail <span class="validate">*</span>
        </label>
        <div class="col-lg-8">
          <input type="text"
                 v-model="form.email"
                 v-validate="'required|email'"
                 data-vv-as="E-Mail"
                 class="form-control"
                 id="email" name="email"
                 placeholder="Введите E-Mail">
          <small v-show="errors.has('form-reg.email')" class="text-danger">
            {{ errors.first('form-reg.email') }}
          </small>
        </div>
      </div>
      <div class="form-group row">
        <label for="password" class="col-lg-4 col-form-label">
          Пароль <span class="validate">*</span>
        </label>
        <div class="col-lg-8">
          <input type="password"
                 v-model="form.password"
                 v-validate="'required|min:6'"
                 ref="password"
                 data-vv-as="Пароль"
                 class="form-control"
                 id="password" name="password"
                 placeholder="Введите Пароль">
          <small v-show="errors.has('form-reg.password')" class="text-danger">
            {{ errors.first('form-reg.password') }}
          </small>
        </div>

      </div>
      <div class="form-group row">
        <label for="password_confirmation" class="col-lg-4 col-form-label">
          Подтвердите пароль <span class="validate">*</span>
        </label>
        <div class="col-lg-8">
          <input type="password"
                 v-model="form.password_confirmation"
                 v-validate="'required|confirmed:password'"
                 data-vv-as="Подтверждение пароля"
                 class="form-control"
                 id="password_confirmation" name="password_confirmation"
                 placeholder="Подтвердите пароль">
          <small v-show="errors.has('form-reg.password_confirmation')" class="text-danger">
            {{ errors.first('form-reg.password_confirmation') }}
          </small>

          <div class="form-check">
            <input class="form-check-input"
                   v-model="form.subscribe"
                   type="checkbox"
                   checked="checked"
                   id="subscribe">
            <label class="form-check-label" for="subscribe">
              Подписаться на акции и новинки
            </label>
          </div>
        </div>
      </div>

      <Errors :type="typeAlerts"
              v-on:clearAlerts="clearAlerts"
              :alerts="alerts"/>
    </form>
    <div class="next_button">
      <a @click="onSubmit" href="javascript:void(0)">Зарегистрироваться</a>
    </div>
  </div>
</template>

<script>
  import * as ApiUser from '../../../app/public/api/User';
  import Errors from "../Errors";
  import mixinAlerts from '../../../app/public/mixins/Alerts';

  export default {
    name: 'Registration',
    mixins: [mixinAlerts],
    components: {Errors},
    data() {
      return {
        form: {
          surname: '',
          name: '',
          email: '',
          phone: '',
          password: '',
          password_confirmation: '',
          subscribe: true
        }
      }
    },
    methods: {
      onSubmit: function () {
        this.$validator.validateAll('form-reg').then((result) => {
          if (result) {
            let self = this;
            ApiUser.registration(this.form).then((res) => {
              if (res.data.status === 'success') {
                this.$store.commit('loginSuccess', res.data);
                this.typeAlerts = 'success';
                this.alerts = 'Вы успешно прошли регистрацию';
                this.$notify({
                  type: 'success',
                  title: 'Вы успешно',
                  text: 'прошли регистрацию'
                });
                setTimeout(() => {
                  this.$router.push({ name: 'user_information' });
                }, 3000);
              }
            }).catch(function (error) {
              self.typeAlerts = 'danger';
              self.alerts = error.response.data.errors;
              self.$notify({
                type: 'error',
                title: 'Ошибка',
                text: 'при регистрации'
              });
            });
          }
        });
      }
    }
  }
</script>