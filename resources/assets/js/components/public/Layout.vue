<template>
    <div>
        <Header/>

        <router-view></router-view>

        <Footer :linkToSocialNetworks="linkToSocialNetworks"
                :textPages="textPages" />

        <notifications/>
    </div>
</template>

<script>
    import * as ApiCommon from '../../app/public/api/Common';

    import { Header, Footer } from '.';

    export default {
        name: 'Layout',
        mounted() {
            if (!this.loadCommon) {
                ApiCommon.get().then((res) => {
                    this.linkToSocialNetworks = res.data.link_to_social_networks;
                    this.textPages = res.data.text_pages;

                    this.$store.commit('updateLinkToSocialNetworks', this.linkToSocialNetworks);
                    this.$store.commit('updateTextPages', this.textPages);

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
        components: {
            Header,
            Footer
        }
    }
</script>