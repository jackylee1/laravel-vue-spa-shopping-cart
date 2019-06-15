<template>
  <div>
    <div v-if="this.renderArraySelect.length || typesAndCategories.length" class="row filter_wrapper">
      <div class="col-12 filter_title text-center">
        <h3>
          <a @click="handleCollapseFilter" v-html="htmlBtnCollapse"></a>
        </h3>
        <transition name="fade">
          <div class="row" v-show="activeCollapseFilter">
            <div class="col-md-3" v-if="selectTypeAndCategory.length" style="padding-bottom: 5px">
              <a-cascader :options="typesAndCategories"
                          size="large"
                          v-model="selectTypeAndCategory"
                          @change="changeTypeOrCategory"
                          :fieldNames="typesAndCategoriesFieldNames"
                          placeholder="Выберите категорию">
                <i slot="suffixIcon" class="fas fa-caret-down"style="font-size: 16px;color: #999;padding-right: 15px;"></i>
              </a-cascader>
            </div>

            <template v-for="(filterRender, index) in this.renderArraySelect">
              <div class="col-md-3" v-if="getChildrenFilters(filterRender, index).length && selectFilters[index] !== undefined" style="padding-bottom: 5px">
                  <multiselect :value="getActiveFilters(selectFilters[index])"
                               :options="getChildrenFilters(filterRender, index)"
                               @input="changeFilter"
                               @open="changeActiveVModel(index)"
                               label="name"
                               :closeOnSelect="false"
                               :searchable="false"
                               track-by="id"
                               :multiple="filterRender.type === 2"
                               selectLabel=""
                               deselectLabel=""
                               placeholder=""
                               noOptions="Нет данных"
                               selectedLabel="Выбрано">
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ option.name }}</span>
                      </span>
                    </template>
                  </multiselect>
              </div>
            </template>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
  import {isMobileOnly} from 'mobile-device-detect';

  let arrayToTree = require('array-to-tree');

  export default {
    name: 'Filters',
    props: ['currentType', 'currentCategory'],
    mounted() {
      this.activeCollapseFilter = (!isMobileOnly);

      this.typesAndCategories = this.getTypeAndCategories();

      _.delay(() => {
        this.setRenderArray();
        this.setSelectFilters();

        this.emitGetProducts();
      }, 550);
    },
    computed: {
      typesStore: function () {
        return this.$store.getters.types;
      },
      activeFilters: function () {
        return this.$store.getters.activeFilters;
      },
      'filters': function () {
        return this.$store.getters.filters;
      },
      watchProps: function () {
        return [
          this.currentCategory,
          this.currentType,
          this.$route.query.sort,
          this.filters
        ].join();
      },
      watchTypeCategory: function () {
        return [
          this.currentCategory,
          this.currentType,
        ].join();
      }
    },
    data() {
      return {
        selectFilters: [],
        renderArraySelect: [],
        intervalData: [],
        activeVModel: null,
        activeCollapseFilter: true,
        htmlBtnCollapse: 'Фильтр товаров <i class="fas fa-chevron-down"></i>',
        renderTypeAndCategory: [],
        typesAndCategories: [],
        typesAndCategoriesFieldNames: {
          label: 'name',
          value: 'slug',
          children: 'children'
        },
        selectTypeAndCategory: [],
        routeType: null,
        routeCategory: null
      }
    },
    methods: {
      takeTypeAndCategoryFromUrl: function () {
        let type = null;
        if (this.$route.query.type !== undefined && this.$route.query.type !== null) {
          type = this.typesStore.find((item) => item.slug === this.$route.query.type);

          if (type !== undefined && type !== null) {
            this.selectTypeAndCategory[0] = type.slug;
            this.routeType = type.slug;

            if (this.$route.query.category !== undefined && this.$route.query.category !== null) {
              let category = type.categories.find(item => item.slug === this.$route.query.category);
              if (category !== undefined) {
                if (category.parent_id === 1) {
                  this.selectTypeAndCategory[1] = category.slug;
                }
                else {
                  let parentCategory = type.categories.find(item => item.id === category.parent_id);
                  this.selectTypeAndCategory[1] = parentCategory.slug;
                  this.selectTypeAndCategory[2] = category.slug;
                }

                this.routeCategory = category.slug;
              }
            }
          }
        }
      },
      emitGetProducts: function () {
        setTimeout(() => {
          this.$emit('getProducts', this.$router.currentRoute.query.page);
        }, 1000);
      },
      setTypeAndCategoryToUrl: function () {
        let typeCategoryLength = this.selectTypeAndCategory.length;

        if (typeCategoryLength > 0) {
          this.routeType = this.selectTypeAndCategory[0];
        }
        else {
          this.routeType = null;
        }
        if (typeCategoryLength > 1) {
          this.routeCategory = this.selectTypeAndCategory[typeCategoryLength - 1];
        }
        else {
          this.routeCategory = null;
        }


        this.routerPushData();
        this.emitGetProducts();
      },
      changeTypeOrCategory: function (value, selectedOptions) {
        this.selectTypeAndCategory = value;
      },
      handleCollapseFilter: function () {
        this.htmlBtnCollapse = '';

        this.activeCollapseFilter = !this.activeCollapseFilter;
        this.htmlBtnCollapse += 'Фильтр товаров ';
        this.htmlBtnCollapse += (!this.activeCollapseFilter) ? '<i class="fas fa-chevron-down"></i>'
                                                             : '<i class="fas fa-chevron-up"></i>';
      },
      changeActiveVModel: function (index) {
        this.activeVModel = index;
      },
      getActiveFilters: function (idFilters) {
        if (idFilters === null || idFilters === undefined || idFilters.length === 0) {
          return false;
        }

        return _(this.filters).keyBy('id').at(idFilters).value();
      },
      getTypeAndCategories: function () {
        let types = _.cloneDeep(this.typesStore);
        return types.filter(item => item.show_on_header === 1)
          .map((item) => {
            item.children = item.categories;
            delete item.categories;

            item.children = arrayToTree(item.children, {
              parentProperty: 'parent_id',
              customID: 'id'
            });

            return item;
          });
      },
      changeFilter: function (value) {
        this.selectFilters[this.activeVModel] = value;
        this.setFiltersToUrl();

        this.$emit('getProducts');
      },
      routerPushData: function() {
        let params = {};

        params.type = this.routeType;
        if (this.$route.query.type !== null && this.$route.query.type !== undefined
          && this.routeType === null) {
          params.type = this.$route.query.type;
        }

        params.category = this.routeCategory;
        if (this.$route.query.category !== null && this.$route.query.type !== undefined
          && this.routeCategory === null) {
          params.category = this.$route.query.category;
        }

        /*params.category = (this.$route.query.category !== null && this.$route.query.category !== undefined)
          ? this.$route.query.category : this.routeCategory;*/
        params.filters = _.filter(this.selectFilters, (item) => item !== undefined);
        params.sort = (this.$route.query.sort !== undefined
            && this.$route.query.sort !== null)
            ? this.$route.query.sort
            : 'all';

        this.$router.push({ query: Object.assign(
          {}, this.$route.query, params
        )});
      },
      setFiltersToUrl: function () {
        this.selectFilters = this.selectFilters.map((item) => {
          if (Array.isArray(item)) {
            return item.map((item) => {
              if (typeof item === 'object') {
                return (item.id !== undefined) ? item.id : item;
              }
              return item;
            })
          }
          if (typeof item === 'object') {
            return (item.id !== undefined) ? item.id : item;
          }
          return item;
        });

        this.routerPushData();
      },
      setSelectFilters: function () {
        let queryFilters = this.$router.currentRoute.query.filters;

        let filters = (queryFilters !== undefined && queryFilters.length > 0) ? queryFilters : this.mergeFilters();
        if (filters !== null && !Array.isArray(filters)) {
          filters = [filters];
        }

        let tempSelectFilters;
        tempSelectFilters = [];

        if (filters.length) {
          filters.forEach((filter, index) => {
            if (queryFilters !== undefined
              && queryFilters[index] !== undefined
              && filter.filter_id !== parseInt(queryFilters[index])) {
              if (tempSelectFilters[index] !== queryFilters[index]) {
                tempSelectFilters[index] = (Array.isArray(queryFilters[index]))
                  ? queryFilters[index].join(',')
                  : queryFilters[index];
              }
            }
            else {
              tempSelectFilters[index] = filter.filter_id;
            }
          });
        }

        tempSelectFilters = tempSelectFilters.map((item, index) => {
          if (typeof item === 'string' && item.indexOf(',') !== -1) {
            return item.split(',').map(item => parseInt(item));
          }
          else if (this.renderArraySelect[index] !== undefined && this.renderArraySelect[index].type === 2) {
            return [parseInt(item)];
          }
          else {
            return parseInt(item);
          }
        });

        if (tempSelectFilters !== this.selectFilters) {
          //this.selectFilters = tempSelectFilters.map(item => this.handleCorrectVModel(item));
          this.selectFilters = tempSelectFilters;
        }

        this.setFiltersToUrl();
      },
      correctVModel: function (id) {
        let index = this.filters.findIndex(item => item.id === id);
        if (index !== -1) {
          if (this.filters[index].parent_id !== 0) {
            return id;
          }
          return null;
        }
        return null;
      },
      handleCorrectVModel: function (value) {
        if (Array.isArray(value)) {
          return value.map((item) => {
            return this.correctVModel(item);
          }).filter(item => item !== null);
        }
        return this.correctVModel(value);
      },
      mergeFilters: function () {
        let typeFilters = [];
        let categoryFilters = [];

        if (this.currentType !== null && this.currentCategory === null) {
          typeFilters = this.sortCurrentFilters(this.currentType.filters);
        }
        else if (this.currentCategory !== null) {
          let childrenCategories = _.filter(this.currentType.categories, (item) => {
            return item.parent_id === this.currentCategory.id
          });
          let childrenCategoryFilters = _.map(childrenCategories, 'filters');
          childrenCategoryFilters = _.flatten(childrenCategoryFilters);
          categoryFilters = this.sortCurrentFilters(_.unionBy(this.currentCategory.filters, childrenCategoryFilters, 'filter_id'));
        }

        if (this.currentCategory === null && this.currentType === null) {
          typeFilters = this.filters.map((filter) => {
            if (filter.type === 1 || filter.type === 2) {
              return {
                filter_id: filter.id
              };
            }
          });
          typeFilters = _.filter(typeFilters, (item) => item !== undefined);
        }

        return _.unionBy(typeFilters, categoryFilters, 'filter_id')
      },
      setRenderArray: function () {
        this.renderArraySelect = [];

        let filters = this.mergeFilters();

        filters.forEach((filter) => {
          filter = this.filters.find((item) => {
            return item.id === filter.filter_id
          });
          let index = this.renderArraySelect.findIndex((item) => {
            return item.id === filter.id;
          });
          if (index === -1) {
            this.renderArraySelect.push(filter);
          }
        });

        this.renderArraySelect = _.orderBy(this.renderArraySelect, 'sorting_order', 'asc');
      },
      getChildrenFilters: function (filter, index) {
        let tempFilters = [];
        let tempItem = _.cloneDeep(filter);
        tempItem.name = 'Выберите фильтр';
        tempFilters.push(tempItem);
        this.$store.getters.filters.forEach((item) => {
          if (filter.id === item.parent_id) {
            tempFilters.push(item);
          }
        });

        let activeFilters = this.activeFilters.find((item) => {
          let type = (this.currentType !== null) ? this.currentType.id : null;
          let category = (this.currentCategory !== null) ? this.currentCategory.id : null;
          return item.type_id === type && item.category_id === category
        });
        if (activeFilters !== undefined) {
          tempFilters = _.filter(tempFilters, (item) => {
            return activeFilters.filters.indexOf(item.id) !== -1;
          });
        }

        return _.orderBy(tempFilters, 'sorting_order', 'asc');
      },
      sortCurrentFilters: function (filters) {
        return _.sortBy(filters, x => _.findIndex(this.filters, y => x.id === y.id))
      }
    },
    watch: {
      '$route': function () {
        this.routeType = this.routeCategory = [];
        this.selectTypeAndCategory = [];
      },
      'selectTypeAndCategory': function () {
        this.setTypeAndCategoryToUrl();
      },
      watchProps: function () {
        _.delay(() => {
          this.setRenderArray();
          this.setSelectFilters();
        }, 1000);
      },
      watchTypeCategory: function () {
        if (this.currentType !== null
          && this.currentType.slug !== undefined
          && this.currentType.slug !== this.routeType) {
          this.routeType = this.currentType.slug;
        }
        else {
          this.routeType = null;
        }

        if (this.currentCategory !== null
          && this.currentCategory.slug !== undefined
          && this.currentCategory.slug !== this.routeCategory) {
          this.routeCategory = this.currentCategory.slug;
        }
        else {
          this.routeCategory = null;
        }

        this.takeTypeAndCategoryFromUrl();
      },
      'typesStore': function () {
        this.typesAndCategories = this.getTypeAndCategories();
      }
    },
  }
</script>

<style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
    opacity: 0;
  }

  span .ant-cascader-picker {
    min-height: 40px;
  }
</style>