<template>
    <div>
        <TopLine/>

        <SubMenu/>

        <SearchCollapse/>

        <Header v-on:getProducts="handleGetProducts"/>

        <Menu/>

        <router-view></router-view>

        <Footer :linkToSocialNetworks="linkToSocialNetworks"
                :textPages="textPages" />

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
                ApiCommon.get().then((res) => {
                    this.linkToSocialNetworks = res.data.link_to_social_networks;
                    this.textPages = res.data.text_pages;

                    this.$store.commit('updateLinkToSocialNetworks', this.linkToSocialNetworks);
                    this.$store.commit('updateTextPages', this.textPages);
                    this.$store.commit('updateTypes', res.data.types);
                    this.$store.commit('updateFilters', res.data.filters);
                    this.$store.commit('updateSizeTables', res.data.size_tables);
                    this.$store.commit('updateNewProducts', res.data.new_products);

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
            }
        },
        methods: {
            handleGetProducts: function () {
                if (this.$router.currentRoute.name === 'catalog') {
                    let index = this.$children.findIndex((item) => item.$vnode.tag === 'vue-component-5-CatalogLayout');
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
        }
    }
</script>