<template>
  <div>
    <div class="row filter_wrapper">
      <div v-if="searchByText !== null && searchByText !== undefined && searchByText.length" class="col-12 search_word">
        <h4>
          Результат поиска: <span class="words">{{searchByText}}</span>
          <i type="button" @click="clearSearchByText" class="fas fa-times close_btn"></i>
        </h4>
      </div>

      <template v-if="this.renderArraySelect.length || typesAndCategories.length">
        <div class="col-12 filter_title text-center">
          <h3>
            <a @click="handleCollapseFilter" v-html="htmlBtnCollapse"></a>
          </h3>
          <transition name="fade">
            <div class="row" v-show="activeCollapseFilter">
              <div class="col-md-3" style="padding-bottom: 5px">
                <a-cascader :options="typesAndCategories"
                            size="large"
                            :changeOnSelect="true"
                            expandTrigger="hover"
                            v-model="selectTypeAndCategories"
                            @change="changeTypeOrCategories"
                            :fieldNames="typesAndCategoriesFieldNames"
                            placeholder="Выберите категорию">
                  <i slot="suffixIcon" class="fas fa-caret-down"style="font-size: 16px;color: #999;padding-right: 15px;"></i>
                </a-cascader>
              </div>

              <template v-for="(filterRender, index) in this.renderArraySelect">
                <div class="col-md-3" v-if="getChildrenFilters(filterRender, index) !== undefined && getChildrenFilters(filterRender, index).length > 1" style="padding-bottom: 5px">
                  <multiselect :value="getSelectFilters(selectFilters[index])"
                               @open="changeActiveVModel(index)"
                               @input="changeSelectFilters"
                               :options="getChildrenFilters(filterRender, index)"
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
      </template>
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
      if (this.filters.length) {
        this.waitFilters = false;
      }

      this.activeCollapseFilter = (!isMobileOnly);

      this.searchByText = this.searchByTextStore;

      this.typesAndCategories = this.getTypeAndCategories();
      let timeout = (this.urlPrevious !== this.removeLoadActiveFilters(this.$router.currentRoute.fullPath)) ? 700 : 0;
      _.delay(() => {
        this.emitGetProducts();
      }, timeout);
    },
    computed: {
      eventApp: function () {
        return this.$store.getters.eventApp;
      },
      searchByTextStore: function () {
        return this.$store.getters.searchByText;
      },
      parentFilters: function () {
        return this.filters.filter(item => item.parent_id === 0);
      },
      typesStore: function () {
        return this.$store.getters.types;
      },
      activeFilters: function () {
        return this.$store.getters.activeFilters;
      },
      'filters': function () {
        return this.$store.getters.filters;
      },
      watchTypeCategoryProps: function () {
        return [
          this.currentCategory,
          this.currentType,
        ].join();
      },
      watchRouteTypeRouteCategory: function () {
        return [
          this.routeCategory,
          this.routeType,
        ].join();
      },
      urlPrevious: function () {
        return this.$store.getters.urlPrevious;
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
        selectTypeAndCategories: [],
        routeType: null,
        routeCategory: null,
        searchByText: null,
        waitFilters: true
      }
    },
    methods: {
      removeLoadActiveFilters: function (str) {
        return str.replace('load_active_filter=1', '').replace('load_active_filter=0', '');
      },
      selectFiltersToArrayIds: function () {
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
      },
      changeSelectFilters: function (filters) {
        if (filters.findIndex(filter => filter.parent_id === 0) === -1) {
          return false;
        }

        this.selectFilters[this.activeVModel] = filters;
        this.selectFiltersToArrayIds();



        this.setDataInUrl();
        this.emitGetProducts();
      },
      changeActiveVModel: function (index) {
        this.activeVModel = index;
      },
      getSelectFilters: function (idFilters) {
        if (idFilters === null || idFilters === undefined || idFilters.length === 0) {
          return false;
        }

        return _(this.filters).keyBy('id').at(idFilters).value();
      },
      getChildrenFilters: function (filter) {
        let tempFilters = [];

        let activeFilters = this.getActiveFilters();

        if (activeFilters !== undefined) {
          this.filters.forEach((item) => {
            if (filter.id === item.parent_id) {
              tempFilters.push(item);
            }
          });

          tempFilters = _.filter(tempFilters, (filter) => {
            return filter.parent_id === 0 || activeFilters.filters.indexOf(filter.id) !== -1;
          });

          tempFilters = _.orderBy(tempFilters, 'sorting_order', 'asc');
          tempFilters.unshift(_.cloneDeep(filter));

          return tempFilters;
        }
      },
      sortCurrentFilters: function (filters) {
        return _.sortBy(filters, x => _.findIndex(this.filters, y => x.id === y.id))
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

        return _.sortBy(_.unionBy(typeFilters, categoryFilters, 'filter_id'), (x) => {
          return _.findIndex(this.filters, y => x.filter_id === y.id);
        });
      },
      setRenderArray: function () {
        if (!this.waitFilters) {
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
        }
      },
      handleCollapseFilter: function () {
        this.htmlBtnCollapse = '';

        this.activeCollapseFilter = !this.activeCollapseFilter;
        this.htmlBtnCollapse += 'Фильтр товаров ';
        this.htmlBtnCollapse += (!this.activeCollapseFilter) ? '<i class="fas fa-chevron-down"></i>'
          : '<i class="fas fa-chevron-up"></i>';
      },
      setSelectTypeAndCategories: function () {
        let type = null;
        let tempTypeAndCategories = [];

        if (this.$route.query.type !== undefined && this.$route.query.type !== null) {
          type = this.typesStore.find((item) => item.slug === this.$route.query.type);
          if (type !== undefined && type !== null) {
            tempTypeAndCategories[0] = type.slug;
            this.routeType = type.slug;
            if (this.$route.query.category !== undefined && this.$route.query.category !== null) {
              let category = type.categories.find(item => item.slug === this.$route.query.category);
              if (category !== undefined) {
                if (category.parent_id === 1) {
                  tempTypeAndCategories[1] = category.slug;
                }
                else {
                  let parentCategory = type.categories.find(item => item.id === category.parent_id);
                  tempTypeAndCategories[1] = parentCategory.slug;
                  tempTypeAndCategories[2] = category.slug;
                }
                this.routeCategory = category.slug;
              }
            }
          }
        }

        if (this.selectTypeAndCategories !== tempTypeAndCategories) {
          this.selectTypeAndCategories = tempTypeAndCategories;
        }
      },
      getSort: function () {
        return (this.$route.query.sort !== undefined
          && this.$route.query.sort !== null)
          ? this.$route.query.sort
          : 'all';
      },
      setDataInUrl: function () {
        let params = {};

        if (this.$route.query.type !== null && this.$route.query.type !== undefined
          && this.routeType === null) {
          params.type = this.$route.query.type;
        }
        else {
          params.type = this.routeType;
        }

        if (this.$route.query.category !== null && this.$route.query.type !== undefined
          && this.routeCategory === null) {
          params.category = this.$route.query.category;
        }
        else {
          params.category = this.routeCategory;
        }

        params.filters = this.selectFilters;
        params.sort = this.getSort();
        params.page = 1;

        this.$router.push({ query: Object.assign(
          {}, this.$route.query, params
        )});
      },
      emitGetProducts: function () {
        let timeout = (this.urlPrevious !== this.removeLoadActiveFilters(this.$router.currentRoute.fullPath)) ? 700 : 0;

        setTimeout(() => {
          this.$emit('getProducts', this.$router.currentRoute.query.page);
        }, timeout);
      },
      handleTypeOrCategoryGetProducts: function () {
        let typeCategoriesLength = this.selectTypeAndCategories.length;

        if (typeCategoriesLength > 0) {
          this.routeType = this.selectTypeAndCategories[0];
        }
        else {
          this.routeType = null;
        }
        if (typeCategoriesLength > 1) {
          this.routeCategory = this.selectTypeAndCategories[typeCategoriesLength - 1];
        }
        else {
          this.routeCategory = null;
        }

        this.renderArraySelect = [];
        this.selectFilters = [];
        return this.$router.push({ name: 'catalog', query: { type: this.routeType, category: this.routeCategory, filters: null } });
      },
      changeTypeOrCategories: function (value, selectedOptions) {
        this.selectTypeAndCategories = value;

        let lengthTypeAndCategories = this.selectTypeAndCategories.length;
        if (lengthTypeAndCategories === 1) {
          this.$router.push({ query: Object.assign(
              {}, this.$route.query, { category: null, sort: this.getSort() }
            )});
        }
        if (lengthTypeAndCategories === 0) {
          this.$router.push({ query: Object.assign(
            {}, this.$route.query, { type: null, category: null, sort: this.getSort() }
          )});
        }

        this.handleTypeOrCategoryGetProducts();
      },
      getTypeAndCategories: function () {
        let types = _.cloneDeep(this.typesStore);
        return types.map((item) => {
          item.children = item.categories;
          delete item.categories;

          item.children = arrayToTree(item.children, {
            parentProperty: 'parent_id',
            customID: 'id'
          });

          return item;
        });
      },
      sortArrayIdFiltersByParent: function (tempSelectFilters) {
        let result = [];

        this.parentFilters.forEach(function(key) {
          let found = false;
          tempSelectFilters = tempSelectFilters.filter(function(item) {
            let tempItem = item;
            if (Array.isArray(item)) {
              tempItem = item[0];
            }

            if(!found && tempItem === key.id) {
              result.push(item);
              found = true;
              return false;
            } else
              return true;
          })
        });

        return result;
      },
      checkCorrectFilter: function (item, activeFilters) {
        let filter = this.filters.find(filter => filter.id === item);
        if (filter !== undefined) {
          if (filter.parent_id === 0) {
            return item;
          }
          else {
            let index = activeFilters.findIndex(activeFilter => activeFilter === filter.id);
            if (index !== -1) {
              return item;
            }
          }
        }
      },
      getActiveFilters: function () {
        let type =  (this.currentType !== null) ? this.currentType.id : null;
        let category =  (this.currentCategory !== null) ? this.currentCategory.id : null;

        return this.activeFilters.find((item) => {
          return item.type_id === type && item.category_id === category && item.sort === this.getSort();
        });
      },
      selectCorrectFiltersId: function (idFilters, activeFilters = null) {
        if (activeFilters === null) {
          activeFilters = this.getActiveFilters();
        }

        if (activeFilters !== undefined) {
          activeFilters = activeFilters.filters;

          return idFilters.map((item) => {
            if (!Array.isArray(item)) {
              return this.checkCorrectFilter(item, activeFilters);
            }
            else {
              return item.map((item) => {
                return this.checkCorrectFilter(item, activeFilters);
              }).filter(item => item !== undefined);
            }
          }).filter(item => item !== undefined);
        }

        return idFilters;
      },
      setFiltersToUrl: function () {
        let activeFilters = this.getActiveFilters();

        let filters;
        let urlFilters;

        filters = [];
        urlFilters = [];

        let queryFilters = this.$router.currentRoute.query.filters;
        if (queryFilters !== null && queryFilters !== undefined) {
          urlFilters = queryFilters.map((item) => {
            if (typeof item === 'string' && item.indexOf(',') !== -1) {
              return item.split(',').map(item => parseInt(item));
            }
            else {
              return parseInt(item);
            }
          });
        }
        this.renderArraySelect.forEach((item, index) => {
          let urlFilter = (urlFilters[index]) !== undefined ? urlFilters[index] : null;
          if (urlFilter !== null && !isNaN(urlFilter)) {
            urlFilter = (item.type === 2 && !Array.isArray(urlFilter)) ? [urlFilter] : urlFilter;

            urlFilter = this.selectCorrectFiltersId(urlFilter, activeFilters);
          }

          let itemFilter = (item.type === 2) ? [item.id] : item.id;
          if (Array.isArray(urlFilter) && Array.isArray(itemFilter)) {
            itemFilter = _.uniq([].concat(itemFilter, urlFilter));
          }
          else if (urlFilter !== null && !isNaN(urlFilter)) {
            itemFilter = urlFilter;
          }

          this.selectFilters[index] = itemFilter;

          filters.push(itemFilter);
        });

        this.$router.push({ query: Object.assign(
          {}, this.$route.query, { filters: filters, sort: this.getSort() }
        )});
      },
      clearSearchByText: function () {
        this.$store.commit('updateSearchByText', null);
        this.$router.push({ query: Object.assign(
          {}, this.$route.query, {
            text: null
          }
        )});
        this.emitGetProducts();
      },
    },
    watch: {
      '$route': function () {
        this.routeType = (this.currentType !== null) ? this.currentType.slug : this.currentType;
        this.routeCategory = (this.currentCategory !== null) ? this.currentCategory.slug : this.currentCategory;

        if (this.renderArraySelect.length === 0) {
          this.setRenderArray();
        }

        this.setSelectTypeAndCategories();
      },
      'eventApp': function (eventApp) {
        if (eventApp) {
          this.renderArraySelect = [];
          this.selectFilters = [];
          this.$store.commit('updateEventApp', false);
        }
      },
      watchRouteTypeRouteCategory: function () {
        if (this.urlPrevious !== null) {
          this.renderArraySelect = [];
          this.selectFilters = [];

          this.$store.commit('updateSearchByText', null);
          this.$router.push({ query: Object.assign({}, this.$route.query, {text: null})});
        }
      },
      renderArraySelect: function () {
        this.setFiltersToUrl();
      },
      watchTypeCategoryProps: function () {
        this.routeType = (this.currentType !== null) ? this.currentType.slug : this.currentType;
        this.routeCategory = (this.currentCategory !== null) ? this.currentCategory.slug : this.currentCategory;

        this.setSelectTypeAndCategories();
        this.setRenderArray();
      },
      'typesStore': function () {
        this.typesAndCategories = this.getTypeAndCategories();
      },
      'searchByTextStore': function (value) {
        this.searchByText = value;
      },
      'filters': function () {
        this.waitFilters = false;
      },
      'waitFilters': function () {
        if (!this.waitFilters && this.renderArraySelect.length === 0) {
          this.setRenderArray();
        }
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