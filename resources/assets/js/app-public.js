require('./bootstrap');

import Vue from 'vue';
import VueCookies from 'vue-cookies';
import vSelect from 'vue-select';
import Multiselect from 'vue-multiselect';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VueYoutube from 'vue-youtube';
import { routes } from './app/public/routes';
import Layout from './components/public/Layout';
import StoreData from './app/public/store';
import { initialize } from './app/public/helpers/general';
import VeeValidate, { Validator } from 'vee-validate';
import VeeValidateRu from 'vee-validate/dist/locale/ru';
import Notifications from 'vue-notification';
import VueScrollTo from 'vue-scrollto';
import Fragment from 'vue-fragment';
import VueLoading from 'vue-loading-overlay';
import Meta from 'vue-meta';
import { Cascader } from 'ant-design-vue'

import 'vue-loading-overlay/dist/vue-loading.css';
import 'vue-select/dist/vue-select.css';

Vue.use(Cascader);
Vue.use(VueCookies);
Vue.use(VeeValidate);
Validator.localize('ru', VeeValidateRu);
Vue.use(VueScrollTo);
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Notifications);
Vue.use(Fragment.Plugin);
Vue.use(VueLoading, {
  color: 'red',
});
Vue.component('v-select', vSelect);
Vue.component('multiselect', Multiselect);
Vue.use(Meta);
Vue.use(VueYoutube);

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
  routes,
  mode: 'history',
});

initialize(store, router);

console.log('public app');

const app = new Vue({
  el: '#app',
  router,
  store,
  components: {
    Layout,
  },
});
