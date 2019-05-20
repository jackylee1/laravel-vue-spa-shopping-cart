<template>
  <div>
    <TopLine :phone1="phone1"
             :phone2="phone2"/>

    <SubMenu :phone1="phone1"
             :phone2="phone2"/>

    <SearchCollapse v-on:getProducts="handleGetProducts"/>

    <Header v-on:getProducts="handleGetProducts"/>

    <Menu/>

    <router-view></router-view>

    <Footer :linkToSocialNetworks="linkToSocialNetworks"
            :textPages="textPages"
            :settingEmail="settingEmail"
            :phone1="phone1"
            :phone2="phone2"/>

    <notifications/>
  </div>
</template>

<script>
  import * as ApiCommon from '../../app/public/api/Common';

  import Footer from "./Footer";
  import TopLine from "./TopLine";
  import Header from "./Header";
  import SubMenu from "./SubMenu";
  import SearchCollapse from "./SearchCollapse";
  import Menu from "./Menu";

  export default {
    name: 'Layout',
    mounted() {
      if (!this.loadCommon) {
        ApiCommon.get({
          cart_key: localStorage.getItem('cart_key'),
          favorite_key: localStorage.getItem('favorite_key'),
        }).then((res) => {
          this.linkToSocialNetworks = res.data.link_to_social_networks;
          this.textPages = res.data.text_pages;
          this.settingEmail = res.data.settings.find((item) => item.slug === 'email').value;
          this.phone1 = res.data.settings.find((item) => item.slug === 'phone1').value;
          this.phone2 = res.data.settings.find((item) => item.slug === 'phone2').value;

          localStorage.setItem('cart_key', res.data.cart.key);
          localStorage.setItem('favorite_key', res.data.favorite.key);

          this.$store.commit('updateLinkToSocialNetworks', this.linkToSocialNetworks);
          this.$store.commit('updateTextPages', this.textPages);
          this.$store.commit('updateTypes', res.data.types);
          this.$store.commit('updateFilters', res.data.filters);
          this.$store.commit('updateSizeTables', res.data.size_tables);
          this.$store.commit('updateNewProducts', res.data.new_products);
          this.$store.commit('updateCart', res.data.cart);
          this.$store.commit('updateFavorite', res.data.favorite);
          this.$store.commit('updatePaymentMethods', res.data.payment_methods);
          this.$store.commit('updateBestsellerProducts', res.data.bestseller_products);
          this.$store.commit('updateUtfRecords', res.data.utf_records);
          this.$store.commit('updateSettings', res.data.settings);
          this.$store.commit('updateIndexMediaFiles', res.data.index_media_files);

          if (res.data.user !== undefined) {
            let token = null;
            if (this.$store.getters.currentUser !== null) {
              token = this.$store.getters.currentUser.token;
            }
            this.$store.commit('loginSuccess', {
              user: res.data.user,
              access_token: token
            });
          }

          this.$store.commit('updateLoadCommon', true);
        });
      }
      else {
        this.linkToSocialNetworks = this.linkToSocialNetworksStore;
        this.textPages = this.textPagesStore;
      }
    },
    computed: {
      linkToSocialNetworksStore: function () {
        return this.$store.getters.linkToSocialNetworks;
      },
      textPagesStore: function () {
        return this.$store.getters.textPages;
      },
      loadCommon: function () {
        return this.$store.getters.loadCommon;
      }
    },
    data() {
      return {
        linkToSocialNetworks: [],
        textPages: [],
        settingEmail: null,
        phone1: null,
        phone2: null
      }
    },
    methods: {
      handleGetProducts: function () {
        if (this.$router.currentRoute.name === 'catalog') {
          let index = this.$children.findIndex((item) => item.$vnode.tag.includes('CatalogLayout'));
          if (index !== -1) {
            this.$children[index].getProducts();
          }
        }
        else {
          this.$router.push({name: 'catalog', query: { text: this.$store.getters.searchByText } })
        }
      }
    },
    components: {
      Menu,
      SearchCollapse,
      SubMenu,
      Header,
      TopLine,
      Footer
    },
    metaInfo: {
      titleTemplate: 'FitClothing %s',
      meta: [
        { 'http-equiv': 'Content-Type', content: 'text/html; charset=utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      ]
    }
  }
</script>