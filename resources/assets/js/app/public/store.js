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
    favorite: null,
    paymentMethods: [],
    areas: [],
    cities: [],
    warehouses: [],
    orders: [],
    bestsellerProducts: [],
    utfRecords: [],
    settings: [],
    indexMediaFiles: [],
    activeFilters: [],
    eventApp: false,
    perPage: 16,
    statusClearSearch: true,
  },
  getters: {
    currentUser: (state) => state.currentUser,
    isLoggedIn: (state) => state.isLoggedIn,
    sliders: (state) => state.sliders,
    textPages: (state) => state.textPages,
    loadIndex: (state) => state.loadIndex,
    loadCommon: (state) => state.loadCommon,
    linkToSocialNetworks: (state) => state.linkToSocialNetworks,
    types: (state) => state.types,
    filters: (state) => state.filters,
    products: (state) => state.products,
    urlPrevious: (state) => state.urlPrevious,
    loadFBComments: (state) => state.loadFBComments,
    categoryPrevious: (state) => state.categoryPrevious,
    typePrevious: (state) => state.typePrevious,
    sizeTables: (state) => state.sizeTables,
    newProducts: (state) => state.newProducts,
    searchByText: (state) => state.searchByText,
    cart: (state) => state.cart,
    favorite: (state) => state.favorite,
    paymentMethods: (state) => state.paymentMethods,
    areas: (state) => state.areas,
    cities: (state) => state.cities,
    warehouses: (state) => state.warehouses,
    orders: (state) => state.orders,
    bestsellerProducts: (state) => state.bestsellerProducts,
    utfRecords: (state) => state.utfRecords,
    settings: (state) => state.settings,
    indexMediaFiles: (state) => state.indexMediaFiles,
    activeFilters: (state) => state.activeFilters,
    eventApp: (state) => state.eventApp,
    perPage: (state) => state.perPage,
    statusClearSearch: (state) => state.statusClearSearch,
  },
  mutations: {
    loginSuccess: (state, payload) => {
      state.isLoggedIn = true;
      state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

      if (payload.remember) {
        localStorage.setItem('user', JSON.stringify(state.currentUser));
      }
      else {
        window.$cookies.set('user', state.currentUser, 0);
      }
    },
    logout: (state) => {
      localStorage.removeItem('user');
      window.$cookies.remove('user');

      state.isLoggedIn = false;
      state.currentUser = null;
    },
    updateSliders: (state, payload) => state.sliders = payload,
    updateTextPages: (state, payload) => state.textPages = payload,
    updateLoadIndex: (state, payload) => state.loadIndex = payload,
    updateLoadCommon: (state, payload) => state.loadCommon = payload,
    updateLinkToSocialNetworks: (state, payload) => state.linkToSocialNetworks = payload,
    updateTypes: (state, payload) => state.types = payload,
    updateFilters: (state, payload) => state.filters = payload,
    updateProducts: (state, payload) => state.products = payload,
    updateUrlPrevious: (state, payload) => state.urlPrevious = payload,
    updateLoadFBComments: (state, payload) => state.loadFBComments = payload,
    updateCategoryPrevious: (state, payload) => state.categoryPrevious = payload,
    updateTypePrevious: (state, payload) => state.typePrevious = payload,
    updateSizeTables: (state, payload) => state.sizeTables = payload,
    updateNewProducts: (state, payload) => state.newProducts = payload,
    updateSearchByText: (state, payload) => state.searchByText = payload,
    updateCart: (state, payload) => state.cart = payload,
    updateFavorite: (state, payload) => state.favorite = payload,
    updatePaymentMethods: (state, payload) => state.paymentMethods = payload,
    updateAreas: (state, payload) => state.areas = payload,
    updateCities: (state, payload) => state.cities = payload,
    updateWarehouses: (state, payload) => state.warehouses = payload,
    updateOrders: (state, payload) => state.orders = payload,
    updateBestsellerProducts: (state, payload) => state.bestsellerProducts = payload,
    updateUtfRecords: (state, payload) => state.utfRecords = payload,
    updateSettings: (state, payload) => state.settings = payload,
    updateIndexMediaFiles: (state, payload) => state.indexMediaFiles = payload,
    updateActiveFilters: (state, payload) => state.activeFilters = payload,
    updateEventApp: (state, payload) => state.eventApp = payload,
    updatePerPage: (state, payload) => state.perPage = payload,
    updateStatusClearSearch: (state, payload) => state.statusClearSearch = payload,
  }
};