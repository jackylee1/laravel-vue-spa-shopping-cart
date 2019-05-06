require('./bootstrap');
window.Pusher = require('pusher-js');

import Vue from 'vue';
import VueCookies from 'vue-cookies';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VuePageTitle from 'vue-page-title';
import { routes } from './app/admin/routes';
import { Layout } from './components/admin';
import StoreData from './app/admin/store';
import { initialize } from './helpers/general';
import { listeningEvents } from './app/admin/helpers/Events';
import ElementUI from 'element-ui';
import tinymce from 'vue-tinymce-editor';
import FileManager from 'laravel-file-manager';
import Croppa from 'vue-croppa';
import VueClipboard from 'vue-clipboard2';
import VueYoutube from 'vue-youtube';

import './assets/css/theme-dark-purple/index.css';
import './assets/css/global-style.css';
import lang from 'element-ui/lib/locale/lang/ru-RU';
import locale from 'element-ui/lib/locale';
import 'ant-design-icons/dist/anticons.min.css';

locale.use(lang);
VueClipboard.config.autoSetContainer = true;

Vue.use(VueCookies);
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(ElementUI);
Vue.use(Croppa);
Vue.use(VueClipboard);
Vue.component('tinymce', tinymce);
Vue.use(VueYoutube);

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

Vue.use(VuePageTitle, {
    router,
    prefix: 'Admin Panel - '
});

initialize(store, router);

if (store.getters.currentUser !== null && store.getters.currentUser.status === 'administration') {
    Vue.use(FileManager, {
        store,
        headers: {'Authorization': `Bearer ${store.getters.currentUser.token}`},
        baseUrl: `${window.location.protocol}//${window.location.host}/file-manager/`,
    });

    listeningEvents(store);
}

console.log('admin app');

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        Layout
    }
});
