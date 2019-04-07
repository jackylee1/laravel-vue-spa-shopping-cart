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
                                <li class="menu_link menu_brands"><a class="menu_tab last" href="#">Бренды</a>
                                    <ul>
                                        <li class="first_column brands">
                                            <ul>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/abr.png" alt="">Abercrombie & Fitch
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/nike.png" alt="">Nike
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/guess.png" alt="">Guess
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/npc.png" alt="">NPC
                                                </a></li>
                                            </ul>
                                        </li>
                                        <li class="second_column brands">
                                            <ul>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/pws.png" alt="">Power System
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/ua.png" alt="">Under Armour
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/harb.png" alt="">Harbinger
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/holl.png" alt="">Hollister
                                                </a></li>
                                            </ul>
                                        </li>
                                        <li class="third_column brands">
                                            <ul>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/rb.png" alt="">Ray Ban
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/tb.png" alt="">Title Boxing
                                                </a></li>
                                            </ul>
                                        </li>
                                        <li class="fourth_column brands">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="fifth_column brands">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="sixth_column brands">
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <img class="menu_img" src="/assets/public/images/banners/womans-1.jpg" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
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
    let arrayToTree = require('array-to-tree');

    export default {
        name: 'Header',
        computed: {
            'typesStore': function () {
                return this.$store.getters.types;
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
            }
        },
        watch: {
            'typesStore': function (types) {
                this.types = types;
            }
        }
    }
</script>