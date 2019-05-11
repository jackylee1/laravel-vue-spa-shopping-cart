<template>
    <div>
        <h3>Войти в свой аккаунт</h3>
        <p class="login_info">Если у вас есть учётная запись, авторизуйтесь, используя
            адрес электронной почты (email).
        </p>
        <form data-vv-scope="form-login">
            <div class="form-group row">
                <label for="auth_email" class="col-lg-4 col-form-label">
                    E-Mail <span class="validate">*</span></label>
                <div class="col-lg-8">
                    <input v-model="form.email"
                           data-vv-as="E-Mail"
                           name="email"
                           v-validate="'required|email'"
                           type="text"
                           class="form-control"
                           id="auth_email"
                           placeholder="Введите Ваш E-Mail">
                    <small v-show="errors.has('form-login.email')" class="text-danger">{{ errors.first('form-login.email') }}</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="auth_password" class="col-lg-4 col-form-label">
                    Пароль <span class="validate">*</span></label>
                <div class="col-lg-8">
                    <input v-model="form.password"
                           data-vv-as="Пароль"
                           v-validate="{ required: true }"
                           name="password"
                           type="password"
                           class="form-control"
                           id="auth_password"
                           placeholder="Введите Ваш пароль">
                    <small v-show="errors.has('form-login.password')" class="text-danger">{{ errors.first('form-login.password') }}</small>

                    <div class="form-check">
                        <input v-model="form.remember"
                               class="form-check-input"
                               type="checkbox"
                               id="auth_remember">
                        <label class="form-check-label" for="auth_remember">
                            Запомнить меня
                        </label>
                    </div>
                </div>
            </div>
            <Errors  style="margin-top: 10px" :type="typeAlerts"
                     v-on:clearAlerts="clearAlerts"
                     :alerts="alerts"/>

            <div class="form-group row login_button righted">
                <div class="col-lg-12">
                    <div class="log_button">
                        <a @click="onSubmit" href="javascript:void(0)"
                           class="submit_login">
                            Войти <i class="fas fa-angle-right"></i>
                        </a>
                    </div>
                    <div class="authorization righted">
                        <router-link :to="{ name: 'forget_password' }">Забыли пароль?</router-link>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import { login } from '../../../helpers/auth';
    import * as ApiCommon from '../../../app/public/api/Common';
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import Errors from "../Errors";

    export default {
        name: 'Login',
        components: {Errors},
        mixins: [mixinAlerts],
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    remember: true
                },
            }
        },
        methods: {
            onSubmit: function () {
                this.$validator.validateAll('form-login').then((result) => {
                    if (result) {
                        login(this.form).then((res) => {
                            this.$store.commit('loginSuccess', res);
                            ApiCommon.get({
                                cart_key: localStorage.getItem('cart_key'),
                                favorite_key: localStorage.getItem('favorite_key'),
                            }).then((res) => {
                                localStorage.setItem('cart_key', res.data.cart.key);
                                localStorage.setItem('favorite_key', res.data.favorite.key);

                                this.$store.commit('updateCart', res.data.cart);
                                this.$store.commit('updateFavorite', res.data.favorite);

                                this.$router.push({ name: 'user_information'});
                            });
                        }).catch((error) => {
                            this.alerts = 'Вы ввели неверный E-mail или Пароль';
                        })
                    }
                });
            }
        }
    }
</script>