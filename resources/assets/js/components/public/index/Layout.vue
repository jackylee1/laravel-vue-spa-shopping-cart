<template>
    <div>
        <UTP></UTP>
        <Slider :sliders="sliders"></Slider>
        <NewProducts></NewProducts>
        <Banner></Banner>
        <BestSellers></BestSellers>
        <CertificatesVideo></CertificatesVideo>
        <Brands></Brands>
    </div>
</template>

<script>
    import * as ApiPage from '../../../app/public/api/Page';

    import { Banner, BestSellers, Brands, CertificatesVideo, NewProducts, Slider, UTP } from './';

    export default {
        name: 'IndexLayout',
        mounted() {
            if (!this.loadIndex) {
                ApiPage.index().then((res) => {
                    this.sliders = res.data.sliders;

                    this.$store.commit('updateSliders', this.sliders);
                    this.$store.commit('updateLoadIndex', true);
                });
            }
            else {
                this.sliders = this.slidersStore;
            }
        },
        computed: {
            slidersStore: function () {
                return this.$store.getters.sliders;
            },
            loadIndex: function () {
                return this.$store.getters.loadIndex;
            }
        },
        data() {
            return {
                sliders: []
            }
        },
        components: {
            Banner,
            BestSellers,
            Brands,
            CertificatesVideo,
            NewProducts,
            Slider,
            UTP
        }
    }
</script>