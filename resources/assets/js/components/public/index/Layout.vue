<template>
  <div>
    <UTP/>

    <Slider :sliders="sliders"/>

    <NewProducts :products="newProducts"/>

    <Banner/>

    <BestSellers :products="bestsellerProducts"/>

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
      this.$scrollTo('#top_line', 650);
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

      if (this.settingsStore.length > 0) {
        this.settings = this.settingsStore;
        this.setMetaTags();
      }
    },
    computed: {
      slidersStore: function () {
        return this.$store.getters.sliders;
      },
      loadIndex: function () {
        return this.$store.getters.loadIndex;
      },
      newProducts: function () {
        return this.$store.getters.newProducts;
      },
      bestsellerProducts: function () {
        return this.$store.getters.bestsellerProducts;
      },
      settingsStore: function () {
        return this.$store.getters.settings;
      }
    },
    methods: {
      setMetaTags: function () {
        this.mTitle = this.mDescription = this.mKeywords = '';

        if (this.settings !== null) {
          let title = this.settings.find((item) => item.slug === 'index_m_title').value;
          let description = this.settings.find((item) => item.slug === 'index_m_description').value;
          let keywords = this.settings.find((item) => item.slug === 'index_m_keywords').value;

          this.mTitle = (title !== null) ? `| ${title}` : '';
          this.mDescription = (description !== null) ? description : '';
          this.mKeywords = (keywords !== null) ? keywords : '';
        }
      },
    },
    data() {
      return {
        sliders: [],
        mTitle: '',
        mDescription: '',
        mKeywords: '',
        settings: null
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
    },
    watch: {
      'settingsStore': function (settings) {
        this.settings = settings;
        this.setMetaTags();
      }
    },
    metaInfo() {
      return {
        title: this.mTitle,
        meta: [
          { name: 'description', content: this.mDescription },
          { name: 'keywords', content: this.mKeywords },

          {property: 'og:title', content: this.mTitle},
          {property: 'og:site_name', content: 'FitClothing'},
          {property: 'og:type', content: 'website'},
          {property: 'og:url', content: window.location.href},
          {property: 'og:description', content: this.mDescription},

          {name: 'twitter:card', content: 'summary'},
          {name: 'twitter:site', content: window.location.href},
          {name: 'twitter:title', content: this.mTitle},
          {name: 'twitter:description', content: this.mDescription},
        ]
      }
    }
  }
</script>