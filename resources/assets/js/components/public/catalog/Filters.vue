<template>
  <div>
    <div v-if="this.renderArraySelect.length" class="row filter_wrapper">
      <div class="col-12 filter_title text-center">
        <h3>
          <a @click="handleCollapseFilter" v-html="htmlBtnCollapse"></a>
        </h3>
        <transition name="fade">
          <div class="row" v-show="activeCollapseFilter">
            <template v-for="(filterRender, index) in this.renderArraySelect">
              <div class="col-md-4">
                <p class="text-center">{{filterRender.name}}</p>
                  <multiselect :value="getActiveFilters(selectFilters[index])"
                               :options="getChildrenFilters(filterRender)"
                               @input="changeFilter"
                               @open="changeActiveVModel(index)"
                               label="name"
                               :closeOnSelect="false"
                               track-by="id"
                               :multiple="filterRender.type === 2"
                               selectLabel=""
                               deselectLabel=""
                               placeholder=""
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
  import { isMobile } from 'mobile-device-detect';

  export default {
    name: 'Filters',
    props: ['currentType', 'currentCategory'],
    mounted() {
      this.activeCollapseFilter = (!isMobile);

      _.delay(() => {
        this.setRenderArray();
        this.setSelectFilters();

        setTimeout(() => {
          this.$emit('getProducts', this.$router.currentRoute.query.page);
        }, 1000);
      }, 550);
    },
    computed: {
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
      }
    },
    data() {
      return {
        selectFilters: [],
        renderArraySelect: [],
        intervalData: [],
        activeVModel: null,
        activeCollapseFilter: true,
        htmlBtnCollapse: 'Фильтр товаров <i class="fas fa-chevron-down"></i>'
      }
    },
    methods: {
      handleCollapseFilter: function () {
        this.activeCollapseFilter = !this.activeCollapseFilter;
        this.htmlBtnCollapse = (!this.activeCollapseFilter) ? 'Фильтр товаров <i class="fas fa-chevron-down"></i>'
          : 'Фильтр товаров <i class="fas fa-chevron-up"></i>';
      },
      changeActiveVModel: function (index) {
        this.activeVModel = index;
      },
      getActiveFilters: function (idFilters) {
        if (idFilters === null || idFilters.length === 0) {
          return false;
        }

        return _(this.filters).keyBy('id').at(idFilters).value();
      },
      changeFilter: function (value) {
        this.selectFilters[this.activeVModel] = value;
        this.setFiltersToUrl();
        console.log('change filter get products');
        this.$emit('getProducts');
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
        this.$router.push({ query: Object.assign(
            {},
            this.$route.query, {
              filters: _.filter(this.selectFilters, (item) => item !== undefined),
              sort: (this.$route.query.sort !== undefined
                && this.$route.query.sort !== null)
                ? this.$route.query.sort
                : 'all',
            }
          )});
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
      getChildrenFilters: function (filter) {
        let tempFilters = [];
        let tempItem = _.cloneDeep(filter);
        tempItem.name = 'Выберите фильтр';
        tempFilters.push(tempItem);
        this.$store.getters.filters.forEach((item) => {
          if (filter.id === item.parent_id) {
            tempFilters.push(item);
          }
        });

        return _.orderBy(tempFilters, 'sorting_order', 'asc');
      },
      sortCurrentFilters: function (filters) {
        return _.sortBy(filters, x => _.findIndex(this.filters, y => x.id === y.id))
      }
    },
    watch: {
      watchProps: function () {
        _.delay(() => {
          this.setRenderArray();
          this.setSelectFilters();
        }, 1000);
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
</style>