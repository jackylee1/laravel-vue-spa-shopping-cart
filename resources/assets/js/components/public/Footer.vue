<template>
    <div>
        <section v-if="showSocialLinks" class="social">
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
                              data-vv-scope="form-subscribe"
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

                            <div class="row col-sm-12" v-show="errors.has('form-subscribe.email')">
                                <div class="alert alert-danger" style="margin-top: 10px;min-width: 100%">
                                    {{ errors.first('form-subscribe.email') }}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <footer class="desktop_footer">
            <div class="container">
                <div class="row">
                    <template v-for="page in textPages">
                        <div class="col-lg-2 footer_menu">
                            <h3>{{page.title}}</h3>
                            <ul>
                                <template v-for="dataPage in page.data_page">
                                    <li>
                                        <template v-if="dataPage.type === 0">
                                            <router-link :to="{name: 'text_page', params: {slug: dataPage.slug}}">
                                                {{dataPage.title}}
                                            </router-link>
                                        </template>
                                        <template v-else>
                                            <a :href="dataPage.url">
                                                {{dataPage.title}}
                                            </a>
                                        </template>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    <div class="col-lg-2 footer_menu">
                        <h3>Наши контакты</h3>
                        <ul>
                            <li v-if="phone1 !== null">т. {{phone1}}</li>
                            <li v-if="phone2 !== null">т. {{phone2}}</li>
                            <li v-if="settingEmail">{{settingEmail}}</li>
                            <li v-if="linkToSocialNetworks.length">
                                <template v-for="link in linkToSocialNetworks">
                                    <a :href="link.url" target="_blank">
                                        <img :src="'/app/public/images/social_network/'+ link.image_preview"
                                             :alt="link.url">
                                    </a>
                                </template>
                            </li>
                        </ul>
                    </div>
                    <div v-if="types.length" class="col-lg-2 footer_menu">
                        <h3>Топ категории</h3>
                        <ul>
                            <template v-for="type in types">
                                <li>
                                    <router-link :to="{ name: 'catalog', query: { type: type.slug } }">
                                        {{type.name}}
                                    </router-link>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div v-if="this.filters.length" class="col-lg-2 footer_menu">
                        <template v-for="filter in this.getTreeFilters(this.filters)">
                            <template v-if="filter.children !== undefined && filter.children.length">
                                <h3>{{filter.name}}</h3>
                                <ul>
                                    <template v-for="filterChildren in filter.children">
                                        <li>
                                            <a @click="productsByFilter(filterChildren)"
                                               href="javascript:void(0)">{{filterChildren.name}}</a>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                        </template>
                    </div>

                    <div class="col-lg-4 footer_logo">
                        <router-link :to="{name: 'index'}">
                            <img class="navbar_logo" src="/assets/public/images/logo.png" alt="FitClothing">
                            <p class="slogan">брендовая спортивная одежда</p>
                        </router-link>
                        <p class="copyright">Авторское право FitClothing @2013-2018</p>
                        <p class="author">Разработка и техподдержка - <a href="javascript:void(0)">DesignStudio</a></p>
                    </div>
                </div>
            </div>
        </footer>
        <footer class="mobile_footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="accordion" id="accordionExample">
                            <template v-for="(page, index) in textPages">
                                <div class="card">
                                    <div class="card-header"
                                         :id="'heading-'+page.id">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3 class="collapsed"
                                                    data-toggle="collapse"
                                                    :data-target="'#collapse-'+page.id"
                                                    :aria-expanded="(index === 0) ? 'true': 'false'"
                                                    :aria-controls="'#collapse-'+page.id">
                                                    {{page.title}}
                                                </h3>
                                            </div>
                                            <div class="col-6">
                                                <h3 class="righted collapsed"
                                                    data-toggle="collapse"
                                                    :data-target="'#collapse-'+page.id"
                                                    :aria-expanded="(index === 0) ? 'true': 'false'"
                                                    :aria-controls="'#collapse-'+page.id">
                                                    <i class="fas fa-chevron-down"></i>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div :id="'collapse-'+page.id" class="collapse open_it"
                                         :aria-labelledby="'heading-'+page.id"
                                         data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <template v-for="dataPage in page.data_page">
                                                    <template v-if="dataPage.type === 0">
                                                        <li>
                                                            <router-link :to="{name: 'text_page', params: {slug: dataPage.slug}}">
                                                                {{dataPage.title}}
                                                            </router-link>
                                                        </li>
                                                    </template>
                                                    <template v-else>
                                                         <li><a :href="dataPage.url">{{dataPage.title}}</a></li>
                                                    </template>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </template>
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
                                <div id="collapseThree" class="collapse open_it"
                                     aria-labelledby="headingThree"
                                     data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul>
                                            <li v-if="phone1 !== null">т. {{phone1}}</li>
                                            <li v-if="phone2 !== null">т. {{phone2}}</li>
                                            <li v-if="settingEmail">{{settingEmail}}</li>
                                            <li v-if="linkToSocialNetworks.length">
                                                <template v-for="link in linkToSocialNetworks">
                                                    <a :href="link.url" target="_blank">
                                                        <img :src="'/app/public/images/social_network/'+ link.image_preview"
                                                             :alt="link.url">
                                                    </a>
                                                </template>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div v-if="types.length" class="card">
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
                                            <li v-for="type in types">
                                                <router-link :to="{ name: 'catalog', query: { type: type.slug } }">
                                                    <a href="javascript:void(0)">{{type.name}}</a>
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 footer_logo centered">
                        <router-link class="fl_link" :to="{ name: 'index' }">
                            <img class="navbar_logo" src="/assets/public/images/logo.png" alt="FitClothing">
                            <p class="slogan">брендовая спортивная одежда</p>
                        </router-link>
                        <p class="copyright">Авторское право FitClothing @2013-2018</p>
                        <p class="author">Разработка и техподдержка - <a href="javascript:void(0)">DesignStudio</a></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
    import mixinProducts from '../../app/public/mixins/Products';
    import * as ApiSubscribe from '../../app/public/api/Subscribe';
    import Errors from "./Errors";
    import VeeErrors from "./VeeErrors";

    let arrayToTree = require('array-to-tree');

    export default {
        name: 'Footer',
        mixins: [mixinProducts],
        components: {VeeErrors, Errors},
        props: ['textPages', 'linkToSocialNetworks', 'settingEmail', 'phone1', 'phone2'],
        computed: {
            'types': function () {
                return this.$store.getters.types.filter((item) => {
                    return item.show_on_footer === 1;
                });
            },
            'filters': function () {
                return this.$store.getters.filters.filter((item) => {
                    return item.show_on_footer === 1;
                });
            },
            showSocialLinks: function () {
                return this.currentRouteName !== 'user_information'
                    && this.currentRouteName !== 'login'
                    && this.currentRouteName !== 'checkout'
                    && this.currentRouteName !== 'registration';
            }
        },
        data() {
            return {
                email: '',
                currentRouteName: this.$router.currentRoute.name
            }
        },
        methods: {
            subscribe: function () {
                this.$validator.validateAll('form-subscribe').then((result) => {
                    if (result) {
                        let self = this;
                        ApiSubscribe.create({email: this.email}).then((res) => {
                            this.$notify({
                                type: 'success',
                                title: 'Запрос успешно выполнен',
                                text: res.data.message
                            });
                            this.email = '';
                            this.$validator.reset();
                        }).catch((error) => {
                            Object.keys(error.response.data.errors).forEach(function (key) {
                                error.response.data.errors[key].forEach((message) => {
                                    self.errors.add({
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
            },
            getTreeFilters: function (filters) {
                let tempFilters = filters;
                this.filters.forEach((filter) => {
                    this.$store.getters.filters.forEach((item) => {
                        if (filter.id === item.parent_id) {
                            tempFilters.push(item);
                        }
                    });
                });

                return arrayToTree(_.unionBy(tempFilters, 'id'), {
                    parentProperty: 'parent_id',
                    customID: 'id'
                });
            }
        },
        watch: {
            '$route': function (route) {
                this.currentRouteName = route.name;
            }
        }
    }
</script>