<template>
    <div>
        <section class="social">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 sociality">
                        <div v-if="linkToSocialNetworks.length"
                             class="social_text">
                            <h2>Следите в социальных сетях</h2>
                        </div>
                        <div v-if="linkToSocialNetworks.length"
                             class="social_icons">
                            <template v-for="link in linkToSocialNetworks">
                                <a :href="link.url" target="_blank">
                                    <img :src="'/app/public/images/social_network/'+ link.image_preview"
                                         :alt="link.url">
                                </a>
                            </template>
                        </div>
                    </div>
                    <div class="col-lg-6 searching">
                        <div class="social_text">
                            <h2>Подписаться на рассылку</h2>
                            <p>узнавайте первыми про акции и новинки</p>
                        </div>
                        <form @keyup.enter="subscribe"
                              action="javascript:void(0)"
                              class="form-inline">
                            <input v-model="email"
                                   v-validate="'required|email'"
                                   data-vv-as="E-Mail"
                                   name="email"
                                   class="form-control form-control-sm mr-3"
                                   type="text"
                                   placeholder="Введите Ваш электронный адрес..." aria-label="">
                            <a @click="subscribe" href="javascript:void(0)" class="to_subscribe">ПОДПИСАТЬСЯ</a>

                            <template v-if="verificationErrors.items !== undefined && verificationErrors.items.length">
                                    <div class="row col-sm-12">
                                        <div class="alert alert-danger" style="margin-top: 10px;min-width: 100%">
                                            <span v-for="error in verificationErrors.items">{{ error.msg }}</span>
                                        </div>
                                    </div>
                            </template>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <footer class="desktop_footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 footer_menu">
                        <h3>Как купить?</h3>
                        <ul>
                            <li><a href="#">Вопрос-ответ</a></li>
                            <li><a href="#">Доставка и оплата</a></li>
                            <li><a href="#">Условия возврата</a></li>
                            <li><a href="#">Как заказать?</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 footer_menu">
                        <h3>О нас</h3>
                        <ul>
                            <li><a href="#">Таблицы размеров</a></li>
                            <li><a href="#">О компании</a></li>
                            <li><a href="#">Отзывы</a></li>
                            <li><a href="#">Контакты</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 footer_menu">
                        <h3>Наши контакты</h3>
                        <ul>
                            <li>т. (073) 069-6097</li>
                            <li>т. (068) 225-7235</li>
                            <li>info@hmail.com</li>
                            <li>
                                <a href="#">
                                    <img src="/assets/public/assets/public/images/socials/telegram_white.png" alt="">
                                </a>
                                <a href="#">
                                    <img src="/assets/public/assets/public/images/socials/viber_white.png" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 footer_menu">
                        <h3>Топ категории</h3>
                        <ul>
                            <li><a href="#">Мужская</a></li>
                            <li><a href="#">Женская</a></li>
                            <li><a href="#">Новинки</a></li>
                            <li><a href="#">Распродажа</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 footer_logo">
                        <a class="fl_link" href="index.html">
                            <img class="navbar_logo" src="/assets/public/images/logo.png" alt="FitClothing">
                            <p class="slogan">брендовая спортивная одежда</p>
                        </a>
                        <p class="copyright">Авторское право FitClothing @2013-2018</p>
                        <p class="author">Разработка и техподдержка - <a href="#">DesignStudio</a></p>
                    </div>
                </div>
            </div>
        </footer>
        <footer class="mobile_footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Как купить?
                                            </h3>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="righted collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <i class="fas fa-chevron-down"></i>
                                            </h3>
                                        </div>
                                    </div>
                                </div>

                                <div id="collapseOne" class="collapse open_it" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul>
                                            <li><a href="#">Вопрос-ответ</a></li>
                                            <li><a href="#">Доставка и оплата</a></li>
                                            <li><a href="#">Условия возврата</a></li>
                                            <li><a href="#">Как заказать?</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                О нас
                                            </h3>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="righted collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <i class="fas fa-chevron-down"></i>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseTwo" class="collapse open_it" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul>
                                            <li><a href="#">Таблицы размеров</a></li>
                                            <li><a href="#">О компании</a></li>
                                            <li><a href="#">Отзывы</a></li>
                                            <li><a href="#">Контакты</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Наши контакты
                                            </h3>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="righted collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <i class="fas fa-chevron-down"></i>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseThree" class="collapse open_it" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul>
                                            <li>т. (073) 069-6097</li>
                                            <li>т. (068) 225-7235</li>
                                            <li>info@hmail.com</li>
                                            <li><a href="#"><img src="/assets/public/images/socials/telegram.png" alt=""></a> <a href="#"><img src="/assets/public/images/socials/viber.png" alt=""></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                Топ категории
                                            </h3>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="righted collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                <i class="fas fa-chevron-down"></i>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseFour" class="collapse open_it" aria-labelledby="headingFour" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul>
                                            <li><a href="#">Мужская</a></li>
                                            <li><a href="#">Женская</a></li>
                                            <li><a href="#">Новинки</a></li>
                                            <li><a href="#">Распродажа</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 footer_logo centered">
                        <a class="fl_link" href="index.html">
                            <img class="navbar_logo" src="/assets/public/images/logo.png" alt="FitClothing">
                            <p class="slogan">брендовая спортивная одежда</p>
                        </a>
                        <p class="copyright">Авторское право FitClothing @2013-2018</p>
                        <p class="author">Разработка и техподдержка - <a href="#">DesignStudio</a></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
    import * as ApiPage from '../../app/public/api/Page';
    import * as ApiSubscribe from '../../app/public/api/Subscribe';

    export default {
        name: 'Footer',
        mounted() {
            if (!this.loadIndex) {
                ApiPage.index().then((res) => {
                    this.linkToSocialNetworks = res.data.link_to_social_networks;

                    this.$store.commit('updateLinkToSocialNetworks', this.sliders);
                    this.$store.commit('updateLoadIndex', true);
                });
            }
            else {
                this.linkToSocialNetworks = this.linkToSocialNetworksStore;
            }
        },
        computed: {
            linkToSocialNetworksStore: function () {
                return this.$store.getters.linkToSocialNetworks;
            },
            loadIndex: function () {
                return this.$store.getters.loadIndex;
            }
        },
        data() {
            return {
                linkToSocialNetworks: [],
                email: ''
            }
        },
        methods: {
            subscribe: function () {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        let self = this;
                        ApiSubscribe.create({email: this.email}).then((res) => {
                            this.$notify({
                                type: 'success',
                                title: 'Запрос успешно выполнен',
                                text: res.data.message
                            });
                        }).catch((error) => {
                            Object.keys(error.response.data.errors).forEach(function (key) {
                                error.response.data.errors[key].forEach((message) => {
                                    self.verificationErrors.add({
                                        field: key,
                                        msg: message
                                    });
                                });
                            });
                        });
                        return;
                    }
                    this.$notify({
                        type: 'error',
                        title: 'Ошибка при валидации формы',
                        text: 'Проверте правильность ввода данных'
                    });
                });
            }
        },
    }
</script>