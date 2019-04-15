let arrayToTree = require('array-to-tree');

export default {
    computed: {
        treeAllFilters: function () {
            return arrayToTree(this.$store.getters.filters, {
                parentProperty: 'parent_id',
                customID: 'id'
            });
        }
    },
    methods: {
        productsByFilter: function (filter) {
            let filters;
            filters = [];
            this.treeAllFilters.forEach((item) => {
                if (item.type === 1) {
                    if (filter.parent_id === item.id) {
                        filters.push(filter.id);
                    }
                    else {
                        filters.push(item.id);
                    }
                }
            });

            this.$router.push({name: 'catalog', query: { filters: filters }});
        }
    }
}