<template>
    <div v-if="textPage !== null">
        <Breadcrumbs :items="breadcrumbs"/>

        <section class="item_card">
            <div class="container">
                <h1 class="text-center">{{textPage.title}}</h1>
                <div v-html="textPage.description"></div>
            </div>
        </section>
    </div>
</template>

<script>
    import Breadcrumbs from "../Breadcrumbs";

    export default {
        name: 'TextPageLayout',
        mounted() {
            this.setData();
        },
        computed: {
            loadCommon: function () {
                return this.$store.getters.loadCommon;
            },
            textPages: function () {
                return this.$store.getters.textPages;
            }
        },
        data() {
            return {
                breadcrumbs: [],
                textPage: null
            }
        },
        components: {
            Breadcrumbs
        },
        methods: {
            setData: function () {
                if (this.loadCommon) {
                    this.$scrollTo('#top_line', 650);
                    this.textPages.forEach((page) => {
                        let index = page.data_page.findIndex(item => item.slug === this.$route.params.slug);
                        if (index !== -1) {
                            this.textPage = page.data_page[index];
                        }
                    });
                    this.breadcrumbs = [
                        {'title': this.textPage.title}
                    ];
                }
            }
        },
        watch: {
            'loadCommon': function (value) {
                this.setData();
            },
            '$route.params.slug': function () {
                this.setData();
            }
        }
    }
</script>