<template>
  <div v-if="products !== undefined && products.length">
    <section class="prod_slider">
      <div class="container">
        <div class="row">
          <div class="col-lg-12"><h1>НОВЫЕ ТОВАРЫ</h1></div>
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
  }
</script>