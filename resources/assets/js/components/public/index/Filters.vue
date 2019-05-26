<template>
  <div>
    <template v-for="filter in this.getTreeFilters(this.filters)">
      <section class="brands">
        <div class="container">
          <div class="row">
            <div class="col-lg-12"><h1>{{filter.name}}</h1></div>
          </div>
          <div v-if="filter.children !== undefined && filter.children.length" class="row brands_images">
            <div class="col-12">
              <template v-for="filterChildren in filter.children">
                <a @click="productsByFilter(filterChildren)"
                   href="javascript:void(0)">
                  <img :src="`/app/public/images/filter/${filterChildren.image_preview}`"
                       :alt="filterChildren.name"
                       class="brand_logo">
                </a>
              </template>
            </div>
          </div>
          <div v-if="filter.children !== undefined && filter.children.length" class="row brands_images_mobile">
            <div :id="'carouselExampleIndicators-'+filter.id" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <template v-for="(item, index) in _.chunk(filter.children, 4)">
                  <li :data-target="'#carouselExampleIndicators-'+filter.id"
                      :data-slide-to="index"
                      :class="(index === 0) ? 'active' : ''">
                  </li>
                </template>
              </ol>
              <div class="carousel-inner">
                <template v-for="(filterChildrenChunk, index) in _.chunk(filter.children, 4)">
                  <div :id="`swipes-${filter.id}`"
                       :class="(index === 0) ? 'carousel-item active' : 'carousel-item'">
                    <div class="row">
                      <template v-for="filterItemsChunk in _.chunk(filterChildrenChunk, 2)">
                        <div class="col-6">
                          <template v-for="filterItemChunk in filterItemsChunk">
                            <a @click="productsByFilter(filterItemChunk)"
                               href="javascript:void(0)">
                              <img :src="`/app/public/images/filter/${filterItemChunk.image_preview}`"
                                   :alt="filterItemChunk.name"
                                   class="brand_logo">
                            </a>
                          </template>
                        </div>
                      </template>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<script>
  import mixinProducts from '../../../app/public/mixins/Products';

  let arrayToTree = require('array-to-tree');

  export default {
    name: 'Filters',
    mixins: [mixinProducts],
    computed: {
      _() {
        return _;
      },
      'filters': function () {
        return this.$store.getters.filters.filter((item) => {
          return item.show_on_index === 1;
        });
      }
    },
    methods: {
      getTreeFilters: function (filters) {
        let tempFilters = filters;
        this.filters.forEach((filter) => {
          this.$store.getters.filters.forEach((item) => {
            if (filter.id === item.parent_id && item.show_image) {
              tempFilters.push(item);
            }
          });
        });
        return arrayToTree(tempFilters, {
          parentProperty: 'parent_id',
          customID: 'id'
        });
      },
    }
  }
</script>