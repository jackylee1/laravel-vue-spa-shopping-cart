<template>
    <div>
        <Breadcrumbs :items="breadcrumbs"/>
        <section class="checkout_form" style="text-align: center;">
            <div class="container">
                <div class="row checkout_login" style="display: inline-block;min-width: 70%">
                    <form data-vv-scope="form-reset-password" >
                        <div class="form-group row">
                            <label for="email" class="col-lg-4 col-form-label">
                                E-Mail <span class="validate">*</span></label>
                            <div class="col-lg-8">
                                <input v-model="form.email"
                                       data-vv-as="E-Mail"
                                       name="email"
                                       v-validate="'required|email'"
                                       type="text"
                                       class="form-control"
                                       id="email"
                                       placeholder="Введите Ваш E-Mail">
                                <small v-show="errors.has('form-reset-password.email')" class="text-danger">{{ errors.first('form-reset-password.email') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-lg-4 col-form-label">
                                Новый пароль <span class="validate">*</span></label>
                            <div class="col-lg-8">
                                <input v-model="form.password"
                                       data-vv-as="Новый пароль"
                                       name="password"
                                       v-validate="'required'"
                                       type="text"
                                       class="form-control"
                                       id="password"
                                       placeholder="Введите Новый пароль">
                                <small v-show="errors.has('form-reset-password.password')" class="text-danger">{{ errors.first('form-reset-password.password') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-lg-4 col-form-label">
                                Подтвердите пароль <span class="validate">*</span></label>
                            <div class="col-lg-8">
                                <input v-model="form.password_confirmation"
                                       data-vv-as="Подтвердите пароль"
                                       name="password_confirmation"
                                       v-validate="'required'"
                                       type="text"
                                       class="form-control"
                                       id="password_confirmation"
                                       placeholder="Подтвердите пароль">
                                <small v-show="errors.has('form-reset-password.password_confirmation')" class="text-danger">{{ errors.first('form-reset-password.password_confirmation') }}</small>
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
                                        Сохранить
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
        name: 'ResetPassword',
        mixins: [mixinAlerts],
        mounted() {
            if (this.$store.getters.isLoggedIn) {
                return this.$router.push({name: 'user_information'});
            }

            this.form.email = this.$router.currentRoute.params.email;
            this.form.token = this.$router.currentRoute.params.token;

            this.breadcrumbs = [
                {title: 'Авторизация и регистрация', route_name: 'login'},
                {title: 'Сбросить пароль'}
            ];
        },
        methods: {
            onSubmit: function () {
                this.$validator.validateAll('form-reset-password').then((result) => {
                    if (result) {
                        ApiUser.resetPassword(this.form).then((res) => {
                            this.typeAlerts = 'success';
                            this.alerts = 'Пароль был успешно изменен';
                            this.form = {
                                email: null,
                                    token: null,
                                    password: null,
                                    password_confirmation: null
                            };
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
                form: {
                    email: null,
                    token: null,
                    password: null,
                    password_confirmation: null
                },

            }
        },
        components: { Breadcrumbs, Errors },
        metaInfo: {
            title: '| Авторизация и регистрация | Сбросить пароль'
        }
    }
</script>