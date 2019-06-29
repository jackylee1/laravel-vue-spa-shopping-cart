let arrayToTree = require('array-to-tree');

export default {
  computed: {
    treeAllFilters: function () {
      return arrayToTree(this.$store.getters.filters, {
        parentProperty: 'parent_id',
        customID: 'id'
      });
    },
    activeFilters: function () {
      return this.$store.getters.activeFilters;
    },
  },
  methods: {
    productsByFilter: function (filter) {
      let filters;
      let activeFilters;

      activeFilters  = [];
      filters = [];

      if (this.activeFilters.length) {
        let index = this.activeFilters.findIndex((item) => {
          return item.sort === 'all' && item.type_id === null && item.category_id === null;
        });
        if (index !== -1) {
          activeFilters = this.activeFilters[index].filters;
        }
      }

      let treeFilters = this.treeAllFilters.map((filter) => {
        if (filter.children !== undefined) {
          filter.children = filter.children.filter(item => activeFilters.includes(item.id));
        }
        else {
          filter.children = [];
        }

        return filter;
      });

      treeFilters.forEach((item) => {
        if (item.type === 1 || item.type === 2) {
          if (filter.parent_id === item.id) {
            filters.push([item.id, filter.id]);
          }
          else {
            filters.push([item.id]);
          }
        }
      });

      if (this.$router.currentRoute.name === 'catalog') {
        this.$router.push({name: 'catalog', query: { filters: filters, sort: 'all', per_page: this.$store.getters.perPage }});
        this.$parent.handleSetRenderArray();
        this.$parent.handleGetProducts();
      }
      else {
        this.$router.push({name: 'catalog', query: { filters: filters, sort: 'all', per_page: this.$store.getters.perPage }});
      }

      this.$scrollTo('#top_line', 650);
    }
  }
}