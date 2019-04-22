<template>
    <div>
        <Breadcrumbs :items="breadcrumbs"/>

        <section class="item_card">
            <div class="container">
                <Errors :type="typeAlerts"
                        v-on:clearAlerts="clearAlerts"
                        :alerts="alerts"/>

                <template v-if="textPage !== null">
                    <h1 class="text-center">{{textPage.title}}</h1>
                    <div v-html="textPage.description"></div>
                </template>
            </div>
        </section>
    </div>
</template>

<script>
    import Breadcrumbs from "../Breadcrumbs";
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import Errors from "../Errors";

    export default {
        name: 'TextPageLayout',
        mixins: [mixinAlerts],
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
            Errors,
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
                    if (this.textPage !== null) {
                        this.breadcrumbs = [
                            {'title': this.textPage.title}
                        ];
                    }
                    else {
                        this.alerts = 'Текстовая страницы с таким адресом не существует';
                        this.$notify({
                            type: 'error',
                            title: 'Ошибка',
                            text: 'при получении текстовой страницы'
                        });
                    }
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