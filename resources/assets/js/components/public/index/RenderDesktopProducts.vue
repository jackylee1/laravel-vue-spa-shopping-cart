<template>
  <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="0">
    <div class="carousel-inner">
      <template v-for="(productsChunk, index) in _.chunk(products, 4)">
        <div :class="(index === 0) ? 'item carousel-item active' : 'item carousel-item'">
          <div class="row">
            <template v-for="product in productsChunk">
              <div class="col-sm-3 the_items_card">
                <div class="thumb-wrapper">
                  <div class="img-box">
                    <router-link :to="{name: 'product', params: {slug: product.slug}}">
                      <img v-if="product.main_image !== null"
                           :src="`/app/public/images/products/${product.main_image.preview}`"
                           :alt="product.name"
                           class="img-responsive img-fluid">
                    </router-link>
                  </div>
                  <div class="thumb-name">
                    <router-link :to="{ name: 'product', params: {slug: product.slug} }">
                      <h4>{{product.name}}</h4>
                    </router-link>
                  </div>
                </div>
                <div class="thumb-content">
                  <p class="item-price">
                    <template v-if="product.discount_price !== null">
                      <strike>{{product.price}} грн</strike>
                    </template>
                    <span :style="(product.discount_price === null) ? 'color: #000' : ''">{{product.current_price}} грн</span>
                  </p>
                  <div class="add_to_cart">
                    <router-link class="btn"
                                 :to="{ name: 'product', params: {slug: product.slug} }">
                      <i class="fas fa-shopping-cart"></i>Купить
                    </router-link>
                    <a @click="productAddToFavorite(product.id)"
                       href="javascript:void(0)" class="hrt"><i class="far fa-heart"></i></a>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </template>
    </div>
    <a class="carousel-control left carousel-control-prev" href="#myCarousel" data-slide="prev">
      <i class="fa fa-angle-left"></i>
    </a>
    <a class="carousel-control right carousel-control-next" href="#myCarousel" data-slide="next">
      <i class="fa fa-angle-right"></i>
    </a>
  </div>
</template>

<script>
  import mixinFavorite from '../../../app/public/mixins/Favorite';

  export default {
    name: 'RenderDesktopProducts',
    props: ['products'],
    mixins: [mixinFavorite],
    computed: {
      _() {
        return _;
      }
    },
  }
</script>