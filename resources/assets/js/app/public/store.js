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
    },
    getters: {
        sliders(state) {
            return state.sliders;
        },
        textPages(state) {
            return state.textPages;
        },
        loadIndex(state) {
            return state.loadIndex;
        },
        loadCommon(state) {
            return state.loadCommon;
        },
        linkToSocialNetworks(state) {
            return state.linkToSocialNetworks;
        },
        types(state) {
            return state.types;
        },
        filters(state) {
            return state.filters;
        },
        products(state) {
            return state.products;
        },
        urlPrevious(state) {
            return state.urlPrevious;
        },
        loadFBComments(state) {
            return state.loadFBComments;
        },
        categoryPrevious(state) {
            return state.categoryPrevious;
        },
        typePrevious(state) {
            return state.typePrevious;
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
    }
};