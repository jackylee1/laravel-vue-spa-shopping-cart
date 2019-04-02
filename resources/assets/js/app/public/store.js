import {getLocalUser} from "../../helpers/auth";

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loadIndex: false,
        sliders: [],
        linkToSocialNetworks: [],
    },
    getters: {
        sliders(state) {
            return state.sliders;
        },
        loadIndex(state) {
            return state.loadIndex;
        },
        linkToSocialNetworks(state) {
            return state.linkToSocialNetworks;
        },
    },
    mutations: {
        updateSliders: function(state, payload) {
            state.sliders = payload;
        },
        updateLoadIndex: function(state, payload) {
            state.loadIndex = payload;
        },
        updateLinkToSocialNetworks: function(state, payload) {
            state.linkToSocialNetworks = payload;
        },
    }
};