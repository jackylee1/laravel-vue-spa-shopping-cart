<template>
    <section class="checkout_form sf_form">
        <div class="container">
            <div class="row checkout_login">
                <Sidebar/>

                <div class="col-md-8 col-lg-9 col-12 enter">
                    <h3>Информация</h3>
                    <form :model="form"
                          action="javascript:void(0)">
                        <div class="form-group row">
                            <label for="name" class="col-lg-3 col-form-label">Имя </label>
                            <div class="col-lg-9">
                                <input v-model="form.name"
                                       name="name" id="name"
                                       type="text" class="form-control" placeholder="Имя">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-lg-3 col-form-label">Фамилия</label>
                            <div class="col-lg-9">
                                <input v-model="form.surname"
                                       name="surname" id="surname"
                                       type="text" class="form-control" placeholder="Фамилия">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="patronymic" class="col-lg-3 col-form-label">Отчество</label>
                            <div class="col-lg-9">
                                <input v-model="form.patronymic"
                                       name="patronymic" id="patronymic"
                                       type="text" class="form-control" placeholder="Отчество">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-lg-3 col-form-label">Телефон</label>
                            <div class="col-lg-9">
                                <input v-model="form.phone"
                                       name="phone" id="phone"
                                       type="text" class="form-control" placeholder="Телефон">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-lg-3 col-form-label">Email </label>
                            <div class="col-lg-9">
                                <input v-model="form.email"
                                       name="email" id="email"
                                       type="text" class="form-control" placeholder="E-mail">
                            </div>
                        </div>

                        <h3>Смена пароля</h3>
                        <div class="form-group row">
                            <label for="old_password" class="col-lg-3 col-form-label">Старый пароль </label>
                            <div class="col-lg-9">
                                <input v-model="form.old_password"
                                       name="old_password" id="old_password"
                                       type="password" class="form-control" placeholder="Старый пароль ">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password" class="col-lg-3 col-form-label">Новый пароль </label>
                            <div class="col-lg-9">
                                <input v-model="form.new_password"
                                       name="new_password" id="new_password"
                                       type="password" class="form-control" placeholder="Новый пароль ">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="new_password_confirmation" class="col-lg-3 col-form-label">Подтвердите пароль </label>
                            <div class="col-lg-9">
                                <input v-model="form.new_password_confirmation"
                                       name="new_password_confirmation" id="new_password_confirmation"
                                       type="password" class="form-control" placeholder="Подтвердите пароль ">
                            </div>
                        </div>

                        <Errors :type="typeAlerts"
                                v-on:clearAlerts="clearAlerts"
                                :alerts="alerts"/>

                        <div class="form-group row invers-flex">
                            <div class="next_button">
                                <a @click="onSubmit"
                                   href="javascript:void(0)" class="submit_account">Сохранить <i class="fas fa-angle-right"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import * as ApiUser from '../../../app/public/api/User';
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import Sidebar from "./Sidebar";
    import Errors from "../Errors";

    export default {
        name: 'InformationLayout',
        mixins: [mixinAlerts],
        mounted() {
            if (!this.isLoggedIn) {
                this.$router.push({ name: 'login'});
            }
            if (this.currentUser !== null) {
                this.form.name = this.currentUser.user_name;
                this.form.surname = this.currentUser.user_surname;
                this.form.patronymic = this.currentUser.user_patronymic;
                this.form.email = this.currentUser.email;
                this.form.phone = this.currentUser.phone;
            }
        },
        computed: {
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn;
            },
            currentUser: function () {
                return this.$store.getters.currentUser;
            }
        },
        data() {
            return {
                form:  {
                    name: null,
                    surname: null,
                    patronymic: null,
                    email: null,
                    phone: null,
                    old_password: null,
                    new_password: null,
                    new_password_confirmation: null
                }
            }
        },
        methods: {
            onSubmit: function () {
                this.alerts = null;
                ApiUser.update(this.currentUser.id, this.form).then((res) => {
                    if (res.data.status === 'success') {
                        let data = {};
                        data.user = res.data.user;
                        data.access_token = (res.data.access_token !== null)
                            ? res.data.access_token : this.currentUser.token;
                        this.$store.commit('loginSuccess', data);

                        this.form.old_password = this.form.new_password = this.form.new_password_confirmation = null;

                        this.$notify({
                            type: 'success',
                            title: 'Данные',
                            text: 'успешно обновлены'
                        });
                    }
                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка',
                        text: 'при обновлении данных'
                    });
                })
            }
        },
        components: {Errors, Sidebar},
    }
</script>