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
    },
    getters: {
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
        }
    },
    mutations: {
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
    }
};