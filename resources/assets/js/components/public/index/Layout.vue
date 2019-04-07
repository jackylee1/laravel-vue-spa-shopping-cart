<template>
    <div>
        <UTP/>
        <Slider :sliders="sliders"/>
        <NewProducts/>
        <Banner/>
        <BestSellers/>
        <CertificatesVideo/>
        <Filters/>
    </div>
</template>

<script>
    import * as ApiPage from '../../../app/public/api/Page';
    import UTP from "./UTP";
    import Slider from "./Slider";
    import NewProducts from "./NewProducts";
    import Banner from "./Banner";
    import BestSellers from "./BestSellers";
    import CertificatesVideo from "./CertificatesVideo";
    import Filters from "./Filters";

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
            Filters,
            CertificatesVideo,
            BestSellers,
            Banner,
            NewProducts,
            Slider,
            UTP
        }
    }
</script>