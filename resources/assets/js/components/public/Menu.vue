<template>
    <div>
        <section class="megamenu">
            <div class="container">
                <div class="row">
                    <div class="menu-container d-flex justify-content-center">
                        <div class="menu">
                            <ul>
                                <li class="menu_link"><a class="menu_tab" href="#">Новинки</a></li>
                                <template v-for="type in this.types">
                                    <li class="menu_link">
                                        <a class="menu_tab" href="javascript:void(0)">{{type.name}}</a>
                                        <ul v-if="type.categories.length">
                                            <template v-for="(category, index) in getTreeCategories(type.categories)">
                                                <li :class="(index === 0) ? 'first_column' : 'second_column'">
                                                    <ul>
                                                        <template v-if="category.children !== undefined && category.children.length">
                                                            <li v-if="category.show_on_header"><p>{{category.name}}</p></li>
                                                            <template v-for="categoryChildren in sortCategories(category.children)">
                                                                <li>
                                                                    <router-link :to="{ name: 'catalog', query: { type: type.slug, category: categoryChildren.slug } }">
                                                                        <a href="">
                                                                            {{categoryChildren.name}}
                                                                        </a>
                                                                    </router-link>
                                                                </li>
                                                            </template>
                                                        </template>
                                                    </ul>
                                                </li>
                                            </template>
                                            <li v-if="type.image_preview !== null" class="sixth_column">
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <img class="menu_img"
                                                                 :src="`/app/public/images/type/${type.image_preview}`"
                                                                 :alt="type.name">
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </template>
                                <li class="menu_link"><a class="menu_tab" href="#">Распродажа</a></li>
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
                                                                        <a href="#">
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    let arrayToTree = require('array-to-tree');

    export default {
        name: 'Header',
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
                types: this.typesStore
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
                console.log(arrayToTree(tempFilters, {
                    parentProperty: 'parent_id',
                    customID: 'id'
                }));
                return arrayToTree(tempFilters, {
                    parentProperty: 'parent_id',
                    customID: 'id'
                });
            }
        },
        watch: {
            'typesStore': function (types) {
                this.types = types;
            }
        }
    }
</script>