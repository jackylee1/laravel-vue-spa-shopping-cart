import {getLocalUser} from "../../helpers/auth";

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loadIndex: false,
        loadCommon: false,
        sliders: [],
        linkToSocialNetworks: [],
        textPages: [],
        types: [],
        filters: [],
        products: [],
        urlPrevious: null,
        loadFBComments: false,
        categoryPrevious: null,
        typePrevious: null,
        sizeTables: [],
        newProducts: [],
        searchByText: null,
        cart: null,
        favorite: null
    },
    getters: {
        currentUser: function (state) {
            return state.currentUser;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        sliders: function (state) {
            return state.sliders;
        },
        textPages: function (state) {
            return state.textPages;
        },
        loadIndex: function (state) {
            return state.loadIndex;
        },
        loadCommon: function (state) {
            return state.loadCommon;
        },
        linkToSocialNetworks: function (state) {
            return state.linkToSocialNetworks;
        },
        types: function (state) {
            return state.types;
        },
        filters: function (state) {
            return state.filters;
        },
        products: function (state) {
            return state.products;
        },
        urlPrevious: function (state) {
            return state.urlPrevious;
        },
        loadFBComments: function (state) {
            return state.loadFBComments;
        },
        categoryPrevious: function (state) {
            return state.categoryPrevious;
        },
        typePrevious: function (state) {
            return state.typePrevious;
        },
        sizeTables: function (state) {
            return state.sizeTables;
        },
        newProducts: function (state) {
            return state.newProducts;
        },
        searchByText: function (state) {
            return state.searchByText;
        },
        cart: function (state) {
            return state.cart;
        },
        favorite: function (state) {
            return state.favorite;
        }
    },
    mutations: {
        loginSuccess: function (state, payload) {
            state.isLoggedIn = true;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

            if (payload.remember) {
                localStorage.setItem('user', JSON.stringify(state.currentUser));
            }
            else {
                window.$cookies.set('user', state.currentUser);
            }
        },
        logout: function (state) {
            localStorage.removeItem('user');
            window.$cookies.remove('user');

            state.isLoggedIn = false;
            state.currentUser = null;
        },
        updateSliders: function(state, payload) {
            state.sliders = payload;
        },
        updateTextPages: function(state, payload) {
            state.textPages = payload;
        },
        updateLoadIndex: function(state, payload) {
            state.loadIndex = payload;
        },
        updateLoadCommon: function(state, payload) {
            state.loadCommon = payload;
        },
        updateLinkToSocialNetworks: function(state, payload) {
            state.linkToSocialNetworks = payload;
        },
        updateTypes: function (state, payload) {
            state.types = payload;
        },
        updateFilters: function (state, payload) {
            state.filters = payload;
        },
        updateProducts: function (state, payload) {
            state.products = payload;
        },
        updateUrlPrevious: function (state, payload) {
            state.urlPrevious = payload;
        },
        updateLoadFBComments: function (state, payload) {
            state.loadFBComments = payload;
        },
        updateCategoryPrevious: function (state, payload) {
            state.categoryPrevious = payload;
        },
        updateTypePrevious: function (state, payload) {
            state.typePrevious = payload;
        },
        updateSizeTables: function (state, payload) {
            state.sizeTables = payload;
        },
        updateNewProducts: function (state, payload) {
            state.newProducts = payload;
        },
        updateSearchByText: function (state, payload) {
            state.searchByText = payload;
        },
        updateCart: function (state, payload) {
            state.cart = payload;
        },
        updateFavorite: function (state, payload) {
            state.favorite = payload;
        }
    }
};