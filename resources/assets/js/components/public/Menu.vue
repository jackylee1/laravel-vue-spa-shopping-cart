<template>
  <div>
    <section class="megamenu">
      <div class="container">
        <div class="row">
          <div class="menu-container d-flex justify-content-center">
            <div class="menu">
              <ul>
                <li class="menu_link">
                  <a class="menu_tab" @click="openLink('index')" href="javascript:void(0)">
                    Главная
                  </a>
                </li>
                <li class="menu_link">
                  <a class="menu_tab" @click="openLink('new')" href="javascript:void(0)">
                    Новинки
                  </a>
                </li>
                <template v-for="type in this.types">
                  <li class="menu_link">
                    <a class="menu_tab" href="javascript:void(0)">{{type.name}}</a>
                    <ul v-if="type.categories.length">
                      <template v-for="(category, index) in getTreeCategories(type.categories)">
                        <li  v-if="category.show_on_header"
                             :class="(index === 0) ? 'first_column' : 'second_column'">
                          <ul >
                            <template v-if="category.children !== undefined && category.children.length">
                              <li v-if="!category.hidden_name"
                                  style="cursor: pointer"
                                  @click="openLinkByObject({ name: 'catalog', query: { type: type.slug, category:  category.slug } })">
                                <p>{{category.name}}</p>
                              </li>
                              <template v-for="categoryChildren in sortCategories(category.children)">
                                <li>
                                  <router-link :to="{ name: 'catalog', query: { type: type.slug, category: categoryChildren.slug } }">
                                    {{categoryChildren.name}}
                                  </router-link>
                                </li>
                              </template>
                            </template>
                            <template v-else>
                              <li v-if="!category.hidden_name"
                                  style="cursor: pointer"
                                  @click="openLinkByObject({ name: 'catalog', query: { type: type.slug, category:  category.slug } })">
                                <p>{{category.name}}</p>
                              </li>
                            </template>
                          </ul>
                        </li>
                      </template>
                      <li v-if="type.image_origin !== null" class="sixth_column">
                        <ul>
                          <li>
                            <a href="javascript:void(0)">
                              <img class="menu_img"
                                   :src="`/app/public/images/type/${type.image_origin}`"
                                   :alt="type.name">
                            </a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </template>
                <li class="menu_link"><a class="menu_tab" @click="openLink('promotional')" href="javascript:void(0)">Распродажа</a></li>
                <template v-if="filtersOnHeader.length">
                  <template v-for="(filter) in this.getTreeFilters(this.filtersOnHeader)">
                    <li class="menu_link menu_brands">
                      <a class="menu_tab last" href="#">{{filter.name}}</a>
                      <template v-if="filter.children !== undefined && filter.children.length">
                        <ul>
                          <template v-for="(filtersChunk, index) in _.chunk(filter.children, 4)">
                            <li :class="(index === 0) ? 'first_column brands' : 'second_column brands'">
                              <ul>
                                <template v-for="filterItemChunk in filtersChunk">
                                  <li>
                                    <a @click="productsByFilter(filterItemChunk)"
                                       href="javascript:void(0)">
                                      <img class="menu_brand_logo"
                                           :src="`/app/public/images/filter/${filterItemChunk.image_preview}`"
                                           :alt="filterItemChunk.name">
                                      {{filterItemChunk.name}}
                                    </a>
                                  </li>
                                </template>
                              </ul>
                            </li>
                          </template>
                          <li v-if="filter.image_preview !== null" class="sixth_column brands">
                            <ul>
                              <li>
                                <a href="javascript:void(0)">
                                  <img class="menu_img"
                                       :src="`/app/public/images/filter/${filter.image_preview}`"
                                       :alt="filter.name">
                                </a>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </template>
                    </li>
                  </template>
                </template>
                <li class="menu_link">
                  <a class="menu_tab" @click="openLink('contacts')" href="javascript:void(0)">
                    Контакты
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import mixinProducts from '../../app/public/mixins/Products';
  import * as jquery from '../../app/public/src/jquery';

  let arrayToTree = require('array-to-tree');

  export default {
    name: 'Header',
    mixins: [mixinProducts],
    mounted() {
      if (this.typesStore.length) {
        this.types = this.typesStore;
      }
    },
    updated() {
      jquery.createMenu();
    },
    computed: {
      _() {
        return _;
      },
      'typesStore': function () {
        return this.$store.getters.types;
      },
      'filtersOnHeader': function () {
        return this.$store.getters.filters.filter((item) => {
          return item.show_on_header === 1;
        });
      }
    },
    data() {
      return {
        types: []
      }
    },
    methods: {
      sortCategories: function (categories) {
        return categories.sort(function (a, b) {
          return a.sorting_order - b.sorting_order;
        });
      },
      getTreeCategories: function (categories) {
        return arrayToTree(categories, {
          parentProperty: 'parent_id',
          customID: 'id'
        });
      },
      getTreeFilters: function (filters) {
        let tempFilters = filters;
        filters.forEach((filter) => {
          this.$store.getters.filters.forEach((item) => {
            if (filter.id === item.parent_id) {
              tempFilters.push(item);
            }
          });
        });
        return arrayToTree(tempFilters, {
          parentProperty: 'parent_id',
          customID: 'id'
        });
      },
      openLink: function (name) {
        if (name === 'new' || name === 'promotional') {
          return this.$router.push({name: 'catalog', query: { sort: name }});
        }

        return this.$router.push({name: name});
      },
      openLinkByObject: function (obj) {
        return this.$router.push(obj);
      }
    },
    watch: {
      'typesStore': function (types) {
        this.types = types;
      }
    }
  }
</script>