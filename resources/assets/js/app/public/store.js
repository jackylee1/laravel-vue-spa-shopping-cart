import {getLocalUser} from "../../helpers/auth";

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loadIndex: false,
        sliders: [],
    },
    getters: {
        sliders(state) {
            return state.sliders;
        },
        loadIndex(state) {
            return state.loadIndex;
        },
    },
    mutations: {
        updateSliders: function(state, payload) {
            state.sliders = payload;
        },
        updateLoadIndex: function(state, payload) {
            state.loadIndex = payload;
        },
    }
};