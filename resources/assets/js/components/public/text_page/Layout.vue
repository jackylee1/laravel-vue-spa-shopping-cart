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
                textPage: null,
                mTitle: '',
                mDescription: '',
                mKeywords: '',
            }
        },
        components: {
            Errors,
            Breadcrumbs
        },
        methods: {
            setMetaTags: function () {
                this.mTitle = this.mDescription = this.mKeywords = '';

                this.mTitle = (this.textPage.m_title !== null) ? `| ${this.textPage.m_title}` : '';
                this.mDescription = (this.textPage.m_description !== null) ? this.textPage.m_description : '';
                this.mKeywords = (this.textPage.m_keywords !== null) ? this.textPage.m_keywords : '';
            },
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
                        this.setMetaTags();
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
        },
        metaInfo() {
            return {
                title: this.mTitle,
                meta: [
                    { name: 'description', content: this.mDescription },
                    { name: 'keywords', content: this.mKeywords },

                    {property: 'og:title', content: this.mTitle},
                    {property: 'og:site_name', content: 'FitClothing'},
                    {property: 'og:type', content: 'website'},
                    {property: 'og:url', content: window.location.href},
                    {property: 'og:description', content: this.mDescription},

                    {name: 'twitter:card', content: 'summary'},
                    {name: 'twitter:site', content: window.location.href},
                    {name: 'twitter:title', content: this.mTitle},
                    {name: 'twitter:description', content: this.mDescription},
                ]
            }
        }
    }
</script>