<template>
  <div>
    <section class="banner" v-if="indexTypes !== undefined && indexTypes.length">
      <div class="container">
        <div class="row" v-for="typesChunk in _.chunk(indexTypes, 2)">
          <div v-for="type in typesChunk"
               :style="`background: url(/app/public/images/type/${type.image_index_origin})`"
               class="col-12 col-sm-6 left_banner centered">
            <h2>{{type.name}}</h2>
            <router-link class="btn"
                         :to="{ name: 'catalog', query: { type: type.slug } }">
              <i class="fas fa-shopping-cart"></i>
              Просмотреть
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    name: 'Banner',
    mounted() {
      this.indexTypes = this.types;
    },
    computed: {
      _: function () {
        return _;
      },
      types: function () {
        return _.filter(this.$store.getters.types, (item) => item.show_on_index === 1);
      }
    },
    data() {
      return {
        indexTypes: this.types
      }
    },
    watch: {
      'types': function () {
        this.indexTypes = this.types;
      }
    }
  }
</script>