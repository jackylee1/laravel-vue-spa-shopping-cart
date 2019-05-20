<template>
  <div>
    <div v-if="this.renderArraySelect.length" class="row filter_wrapper">
      <template v-for="(filterRender, index) in this.renderArraySelect">
        <div class="col-md-2">
          <p class="text-center">{{filterRender.name}}</p>
          <select @click="changeFilter"
                  v-model="selectFilters[index]"
                  class="form-control-sm custom-select">
            <option value=""></option>
            <template v-for="filterChildren in getChildrenFilters(filterRender)">
              <option :value="filterChildren.id">{{filterChildren.name}}</option>
            </template>
          </select>
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
      this.setRenderArray();
      this.setSelectFilters();

      this.$emit('getProducts', this.$router.currentRoute.query.page);
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
        this.selectFilters = [];
        let queryFilters = this.$router.currentRoute.query.filters;

        let filters = (queryFilters !== undefined && queryFilters.length > 0) ? queryFilters : this.mergeFilters();

        if (filters.length) {
          filters.forEach((filter, index) => {
            if (queryFilters !== undefined
              && queryFilters[index] !== undefined
              && filter.filter_id !== parseInt(queryFilters[index])) {
              this.selectFilters.push(queryFilters[index]);
            }
            else {
              this.selectFilters.push(filter.filter_id);
            }
          });
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
          categoryFilters = this.sortCurrentFilters(this.currentCategory.filters);
        }

        if (this.currentCategory === null && this.currentType === null) {
          typeFilters = this.filters.map((filter) => {
            if (filter.type === 1) {
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
        this.setRenderArray();
        this.setSelectFilters();
      }
    },
  }
</script>