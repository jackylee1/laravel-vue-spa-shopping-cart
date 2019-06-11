<template>
  <fragment>
    <div class="col-md-2 item_images">
      <ul v-if="product.images.length">
        <template v-for="image in product.images">
          <li>
            <template v-if="product.main_image !== null && image.id !== product.main_image.id">
              <a data-fancybox="gallery"
                 :href="`/app/public/images/products/${image.origin}`">
                <img class="small_img"
                     :src="`/app/public/images/products/${image.preview}`"
                     alt="">
              </a>
            </template>
            <template v-else>
              <a data-fancybox="gallery"
                 :href="`/app/public/images/products/${image.origin}`">
                <img class="small_img"
                     :src="`/app/public/images/products/${image.preview}`"
                     alt="">
              </a>
            </template>
          </li>
        </template>
        <li v-for="video in product.video">
          <a data-fancybox="gallery"
             data-type="iframe"
             :data-src="getUrlIframe(video.url)">
            <img class="small_img"
                 src="/assets/public/images/items/video.png"
                 alt="">
          </a>
        </li>
      </ul>
    </div>
    <div v-if="product.main_image !== null" class="col-md-6 item_image">
      <img class="big_img" :src="`/app/public/images/products/${product.main_image.origin}`" alt="">
    </div>

    <div class="col-12 mobile_image_slider">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <template v-if="product.main_image !== null">
            <li data-target="#carouselExampleIndicators"
                data-slide-to="0"
                class="active"></li>
          </template>
          <template v-for="(image, index) in product.images">
            <li data-target="#carouselExampleIndicators"
                :class="(index === 0 && product.main_image === null) ? 'active' : null"
                :data-slide-to="(product.main_image !== null) ? index + 1 : index">
            </li>
          </template>
        </ol>
        <div class="carousel-inner">
          <template v-if="product.main_image !== null">
            <div class="carousel-item active">
              <img class="d-block w-100"
                   :src="`/app/public/images/products/${product.main_image.origin}`"
                   alt="">
            </div>
          </template>
          <template v-for="(image, index) in product.images">
            <div :class="(index === 0 && product.main_image === null) ? 'carousel-item active' : 'carousel-item'">
              <img class="d-block w-100"
                   :src="`/app/public/images/products/${image.origin}`"
                   alt="">
            </div>
          </template>
        </div>
      </div>
    </div>
  </fragment>
</template>

<script>
  export default {
    name: 'Images',
    props: ['product'],
    methods: {
      getUrlIframe: function (url) {
        let v = new URL(url).searchParams.get('v');

        return `https://www.youtube.com/embed/${v}`;
      }
    }
  }
</script>