require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VuePageTitle from 'vue-page-title';
import { routes } from './app/admin/routes';
import { Layout } from './components/admin';
import StoreData from './app/admin/store';
import { initialize } from './helpers/general';
import ElementUI from 'element-ui';
import tinymce from 'vue-tinymce-editor';
import FileManager from 'laravel-file-manager';
import Croppa from 'vue-croppa';
import VueClipboard from 'vue-clipboard2'

locale.use(lang);
VueClipboard.config.autoSetContainer = true;

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(ElementUI);
Vue.use(Croppa);
Vue.use(VueClipboard);

import './assets/css/theme-dark-purple/index.css';
import './assets/css/global-style.css';

import lang from 'element-ui/lib/locale/lang/ru-RU';
import locale from 'element-ui/lib/locale';

import 'ant-design-icons/dist/anticons.min.css';

Vue.component('tinymce', tinymce);

const store = new Vuex.Store(StoreData);

if (store.getters.currentUser !== null && store.getters.currentUser.status === 'administration') {
    Vue.use(FileManager, {
        store,
        headers: {'Authorization': `Bearer ${store.getters.currentUser.token}`},
        baseUrl: `${window.location.protocol}//${window.location.host}/file-manager/`,
    });
}

const router = new VueRouter({
    routes,
    mode: 'history'
});

Vue.use(VuePageTitle, {
    router,
    prefix: 'Vue SPA - '
});

initialize(store, router);

console.log('admin app');

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        Layout
    }
});
