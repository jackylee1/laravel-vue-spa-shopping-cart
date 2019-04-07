<template>
    <div>
        <Breadcrumbs :items="breadcrumbs"/>

        <section class="wrapper">
            <div class="container">

                <Sort :currentCategory="currentCategory"/>

                <Filters/>

                <div class="row category_items">

                    <Products/>

                </div>
                <div class="row paginations">
                    <div class="col-lg-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Страницы:</a>
                                </li>
                                <li class="page-item disabled"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Показать все</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import Breadcrumbs from "../Breadcrumbs";
    import Products from "./Products";
    import Sort from "./Sort";
    import Filters from "../index/Filters";

    export default {
        name: 'CatalogLayout',
        computed: {
            types: function () {
                return this.$store.getters.types;
            }
        },
        data() {
            return {
                breadcrumbs: [],
                currentCategory: {},
                currentType: {},
            }
        },
        components: {
            Filters,
            Sort,
            Products,
            Breadcrumbs,
        },
        watch: {
            'types': function (types) {
                types.forEach((type) => {
                    if (type.slug === this.$route.query.type) {
                        this.currentType = type;
                    }
                    type.categories.forEach((category) => {
                        if (category.slug === this.$route.query.category) {
                            this.currentCategory = category;
                        }
                    });
                });

                this.breadcrumbs = [
                    {title: this.currentType.name, route: `{ "name": "catalog", "query": { "type": "${this.currentType.slug}"} }`},
                    {title: this.currentCategory.name},
                ];
            },
            '$route' (to, from){
                console.log('change');
            }
        }
    }
</script>