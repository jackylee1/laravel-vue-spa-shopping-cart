import {getLocalUser} from "../../helpers/auth";

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        selectData: {
            listStatuses: [
                { value: 'user', label: 'Пользователь' },
                { value: 'administration', label: 'Администрация' }
            ],
            listPromotionalCodeStatuses: [
                { value: 1, label: 'Активные' },
                { value: 0, label: 'Использованные' }
            ],
            listProductStatuses: [
                { value: 1, label: 'Да' },
                { value: 0, label: 'Нет' }
            ],
            listReliability: [
                { value: 1, label: 'Надежный пользователь' },
                { value: 0, label: 'Не надежный пользователь' }
            ],
            filterTypes: [
                { value: 1, label: 'Выпадающий список (выбор одного элемента)' },
                { value: 2, label: 'Выпадающий список (выбор нескольких элементов)' },
                { value: 0, label: 'Дочерний элемент' }
            ],
            boolean: [
                { value: 0, label: 'Нет' },
                { value: 1, label: 'Да' }
            ],
            textBlockDataTypes: [
                { value: 0, label: 'Страница' },
                { value: 1, label: 'Ссылка' }
            ]
        },
        users: [],
        search: {
            users: {
                q: '',
                status: 'all'
            },
            subscribes: {
                q: '',
                only_new: 0,
            },
            products: {
                q: '',
                selected_type: null,
                selected_categories: [],
                selected_filters: [],
                only_discounts: 0,
            },
            promotionalCodes: {
                q: '',
                status: 'all',
                user_id: null
            },
            orders: {
                id: null,
                user_name: '',
                user_surname: '',
                user_patronymic: '',
                user_id: null,
                only_new: 0,
            }
        },
        subscribes: [],
        types: [],
        userGroups: [],
        promotionalCodes: [],
        filters: [],
        products: [],
        sliders: [],
        textBlockTitles: [],
        textBlockData: [],
        orderStatuses: [],
        orderPaymentMethods: [],
        orders: [],
        linkToSocialNetworks: [],
        newSubscribes: 0,
        loadNotifications: false,
        sizeTables: []
    },
    getters: {
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        linkToSocialNetworks(state) {
            return state.linkToSocialNetworks;
        },
        currentUser: function (state) {
            return state.currentUser;
        },
        users: function (state) {
            return state.users;
        },
        types: function (state) {
            return state.types;
        },
        searchUsers: function (state) {
            return state.search.users
        },
        userGroups: function (state) {
            return state.userGroups
        },
        promotionalCodes: function (state) {
            return state.promotionalCodes
        },
        searchPromotionalCodes: function (state) {
            return state.search.promotionalCodes;
        },
        filters: function (state) {
            return state.filters;
        },
        products: function (state) {
            return state.products;
        },
        sliders: function (state) {
            return state.sliders;
        },
        textBlockTitles: function (state) {
            return state.textBlockTitles;
        },
        textBlockData: function (state) {
            return state.textBlockData;
        },
        selectDataListStatuses: function (state) {
            return state.selectData.listStatuses;
        },
        selectDataListPromotionalCodeStatuses: function (state) {
            return state.selectData.listPromotionalCodeStatuses;
        },
        selectDataListProductStatuses: function (state) {
            return state.selectData.listProductStatuses;
        },
        selectDataListReliability: function (state) {
            return state.selectData.listReliability;
        },
        selectDataFilterTypes: function (state) {
            return state.selectData.filterTypes;
        },
        selectDataBoolean: function (state) {
            return state.selectData.boolean;
        },
        selectDataTextBlockDataTypes: function (state) {
            return state.selectData.textBlockDataTypes;
        },
        searchProducts: function (state) {
            return state.search.products;
        },
        searchOrders: function (state) {
            return state.search.orders;
        },
        orderStatuses: function (state) {
            return state.orderStatuses;
        },
        orderPaymentMethods: function (state) {
            return state.orderPaymentMethods;
        },
        orders: function (state) {
            return state.orders;
        },
        subscribes: function (state) {
            return state.subscribes;
        },
        searchSubscribes: function (state) {
            return state.search.subscribes;
        },
        newSubscribes: function (state) {
            return state.newSubscribes;
        },
        loadNotifications: function (state) {
            return state.loadNotifications;
        },
        sizeTables: function (state) {
            return state.sizeTables;
        },
    },
    mutations: {
        login: function (state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess: function (state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

            if (payload.remember) {
                localStorage.setItem('user', JSON.stringify(state.currentUser));
            }
            else {
                window.$cookies.set('user', state.currentUser, 0);
            }
        },
        loginFailed: function (state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout: function (state) {
            localStorage.removeItem('user');
            window.$cookies.remove('user');

            state.isLoggedIn = false;
            state.currentUser = null;
        },
        updateUsers: function (state, payload) {
            state.users = payload;
        },
        updateSearchUsers: function (state, payload) {
            state.search.users = payload;
        },
        updateTypes: function (state, payload) {
            state.types = payload;
        },
        updateUserGroups: function (state, payload) {
            state.userGroups = payload;
        },
        updatePromotionalCodes: function (state, payload) {
            state.promotionalCodes = payload;
        },
        updateSearchPromotionalCodes: function (state, payload) {
            state.search.promotionalCodes = payload;
        },
        updateFilters: function(state, payload) {
            state.filters = payload;
        },
        updateProducts: function (state, payload) {
            state.products = payload;
        },
        updateSliders: function (state, payload) {
            state.sliders = payload;
        },
        updateTextBlockTitles: function (state, payload) {
            state.textBlockTitles = payload;
        },
        updateTextBlockData: function (state, payload) {
            state.textBlockData = payload;
        },
        updateSearchProducts: function (state, payload) {
            state.search.products = payload;
        },
        updateSearchOrders: function (state, payload) {
            state.search.orders = payload;
        },
        updateOrderStatuses: function (state, payload) {
            state.orderStatuses = payload;
        },
        updateOrderPaymentMethods: function (state, payload) {
            state.orderPaymentMethods = payload;
        },
        updateOrders: function (state, payload) {
            state.orders = payload;
        },
        updateLinkToSocialNetworks: function (state, payload) {
            state.linkToSocialNetworks = payload;
        },
        updateSubscribes: function (state, payload) {
            state.subscribes = payload;
        },
        updateSearchSubscribes: function (state, payload) {
            state.search.subscribes = payload;
        },
        updateNewSubscribes: function (state, payload) {
            state.newSubscribes = payload;
        },
        updateLoadNotifications: function (state, payload) {
            state.loadNotifications = payload;
        },
        updateSizeTables: function (state, payload) {
            state.sizeTables = payload;
        }
    },
    actions: {
        login: function (context) {
            context.commit('login');
        }
    },
};
