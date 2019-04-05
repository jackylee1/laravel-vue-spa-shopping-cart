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
        textPages: []
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
    }
};