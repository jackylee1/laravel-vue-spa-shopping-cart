<template>
    <div v-if="products !== null && products.length">
        <div class="row rec_title">
            <div class="col-lg-12">
                <h4>Рекомендуемые товары</h4>
            </div>
        </div>
        <div class="row prod_slider_content">
            <div class="col-md-12">
                <RenderDesktopProducts :products="products"/>
            </div>
        </div>
    </div>
</template>

<script>
    import RenderDesktopProducts from "./../index/RenderDesktopProducts";

    export default {
        name: 'RecommendedProducts',
        props: ['products'],
        components: { RenderDesktopProducts }
    }
</script>