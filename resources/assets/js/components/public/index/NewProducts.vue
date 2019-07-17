<template>
  <div v-if="products !== undefined && products.length">
    <section class="prod_slider">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <a href="javascript:void(0)"
               @click="openLink('all')">
              <h1>НОВЫЕ ТОВАРЫ</h1>
            </a>
          </div>
        </div>
        <div class="row prod_slider_content">
          <div class="col-md-12">
            <RenderDesktopProducts :products="products"/>
          </div>
        </div>

        <RenderMobileProducts :products="products"/>
      </div>
    </section>
  </div>
</template>

<script>
  import RenderDesktopProducts from "./RenderDesktopProducts";
  import RenderMobileProducts from "./RenderMobileProducts";

  export default {
    name: 'NewProducts',
    components: {RenderMobileProducts, RenderDesktopProducts},
    props: ['products'],
    methods: {
      openLink: function (name) {
        this.$store.commit('updateSearchByText', null);

        this.$router.push({ query: Object.assign(
            {}, this.$route.query, {
              filters: null,
              sort: 'all',
              text: null
            }
          )});

        if (name === 'new' || name === 'promotional' || name === 'all') {
          return this.$router.push({name: 'catalog', query: { sort: name, text: null, type: null, category: null }});
        }

        return this.$router.push({name: name});
      },
    }
  }
</script>