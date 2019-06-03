<template>
  <div>
    <div v-if="this.renderArraySelect.length" class="row filter_wrapper">
      <template v-for="(filterRender, index) in this.renderArraySelect">
        <div class="col-md-2">
          <p class="text-center">{{filterRender.name}}</p>
          <template v-if="filterRender.type === 2">
            <select @click="changeFilter"
                    v-model="selectFilters[index]"
                    multiple
                    class="form-control-sm custom-select">
              <option value=""></option>
              <template v-for="filterChildren in getChildrenFilters(filterRender)">
                <option :value="filterChildren.id">{{filterChildren.name}}</option>
              </template>
            </select>
          </template>
          <template v-else>
            <select @click="changeFilter"
                    v-model="selectFilters[index]"
                    class="form-control-sm custom-select">
              <option value=""></option>
              <template v-for="filterChildren in getChildrenFilters(filterRender)">
                <option :value="filterChildren.id">{{filterChildren.name}}</option>
              </template>
            </select>
          </template>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Filters',
    props: ['currentType', 'currentCategory'],
    mounted() {
      _.delay(() => {
        this.setRenderArray();
        this.setSelectFilters();
        this.$emit('getProducts', this.$router.currentRoute.query.page);
      }, 600);
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
      }
    },
    methods: {
      changeFilter: function () {
        this.setFiltersToUrl();
        this.$emit('getProducts');
      },
      setFiltersToUrl: function () {
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
            return item.split(',');
          }
          else if (this.renderArraySelect[index] !== undefined && this.renderArraySelect[index].type === 2) {
            return [item];
          }
          else {
            return item;
          }
        });

        if (tempSelectFilters !== this.selectFilters) {
          this.selectFilters = tempSelectFilters;
        }

        this.setFiltersToUrl();
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
        console.log(filters);

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