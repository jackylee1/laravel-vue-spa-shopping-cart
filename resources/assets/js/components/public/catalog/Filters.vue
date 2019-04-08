<template>
    <div>
        <div v-if="this.renderArraySelect.length" class="row filter_wrapper">
            <template v-for="(filterRender, index) in this.renderArraySelect">
                <div class="col-md-2">
                    <p class="text-center">{{filterRender.name}}</p>
                    <select v-model="selectFilters[index]"
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
        computed: {
            'filters': function () {
                return this.$store.getters.filters;
            }
        },
        data() {
            return {
                selectFilters: [],
                renderArraySelect: []
            }
        },
        methods: {
            setSelectFilters: function () {
                if (this.$route.query.filters !== undefined && this.$route.query.filters.length) {
                    this.selectFilters = _.uniqBy(this.$route.query.filters);
                }
                else {
                    let filters = this.mergeFilters();
                    filters.forEach((filter) => {
                        this.selectFilters.push(filter.filter_id);
                    });
                    this.selectFilters = _.uniqBy(this.selectFilters);
                }
            },
            mergeFilters: function () {
                let typeFilters = this.sortCurrentFilters(this.currentType.filters);
                let categoryFilters = this.sortCurrentFilters(this.currentCategory.filters);

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
            'currentCategory': function () {
                this.setRenderArray();
                this.setSelectFilters();
            },
            'selectFilters': function () {
                if (this.selectFilters.length) {
                    this.$emit('updateSelectFilters', this.selectFilters);
                    this.$emit('getProducts', 1)
                }
            },
        },
    }
</script>