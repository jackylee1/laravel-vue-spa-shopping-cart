require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VuePageTitle from 'vue-page-title';
import { routes } from './app/public/routes';
import { Layout } from './components/public';
import StoreData from './app/public/store';
import { initialize } from './app/public/helpers/general';
import VeeValidate, { Validator, ErrorBag } from 'vee-validate';
import VeeValidateRu from 'vee-validate/dist/locale/ru';
import Notifications from 'vue-notification';

Vue.use(VeeValidate, {
    errorBagName: 'verificationErrors'
});
Validator.localize('ru', VeeValidateRu);
const VeeValidateBag = new ErrorBag();

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Notifications);

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

Vue.use(VuePageTitle, {
    router,
});

initialize(store, router);

console.log('public app');

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        Layout
    }
});