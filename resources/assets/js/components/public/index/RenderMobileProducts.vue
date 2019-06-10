<template>
  <div>
    <div v-for="productsChunk in _.take(_.chunk(products, 2), 2)" class="row new_items_mobile justify-content-center">
      <div v-for="product in productsChunk" class="col-6 items_mobile_card">
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
        <div class="thumb-content">
          <p class="item-price">
            <template v-if="product.discount_price !== null">
              <strike>{{product.price}} грн</strike>
            </template>
            <span :style="(product.discount_price === null) ? 'color: #333' : ''">{{product.current_price}} грн</span>
          </p>
          <div class="add_to_cart">
            <router-link class="btn"
                         :to="{ name: 'product', params: {slug: product.slug} }">
              <i class="fas fa-shopping-cart"></i>Купить
            </router-link>
            <a @click="productAddToFavorite(product.id)"
               href="javascript:void(0)" class="hrt">
              <i class="far fa-heart"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import mixinFavorite from '../../../app/public/mixins/Favorite';

  export default {
    name: 'RenderMobileProducts',
    props: ['products'],
    mixins: [mixinFavorite],
    computed: {
      _() {
        return _;
      }
    },
  }
</script>