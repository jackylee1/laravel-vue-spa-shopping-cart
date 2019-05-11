<template>
    <div>
        <div class="row comments_title">
            <div class="col-lg-12">
                <h4>Ваши отзывы</h4>
            </div>
        </div>
        <div class="row comments" v-if="href !== null">
            <div id="fb-root"></div>
            <div class="col-lg-12">
                <div class="fb-comments"
                     id="fb_comments"
                     width="100%"
                     :data-href="href"
                     data-numposts="6"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Comments',
        created: function() {
            this.href = this.facebookPage();
            if (!this.loadFBComments) {
                this.initFBComments();
            }
            else {
                setTimeout(() => {
                    FB.XFBML.parse();
                }, 2000);
            }
        },
        methods: {
            facebookPage: function () {
                return window.location.origin + '/' + this.$router.currentRoute.fullPath;
            },
            initFBComments: function () {
                window.fbAsyncInit = function() {
                    FB.init({
                        appId: '2789829927724347',
                        xfbml: true,
                        version: 'v3.2'
                    });
                };

                (function(d, s, id){
                    let js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/ru_RU/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));

                this.$store.commit('updateLoadFBComments', true);
            }
        },
        computed: {
            'loadFBComments': function () {
                return this.$store.getters.loadFBComments;
            }
        },
        data() {
            return {
                href: null
            }
        },
        watch: {
            '$route'() {
                this.href = this.facebookPage();
                setTimeout(() => {
                    FB.XFBML.parse();
                }, 2000);
            }
        }
    }
</script>