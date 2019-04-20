<template>
    <div>
        <div class="row comments_title">
            <div class="col-lg-12">
                <h4>Ваши отзывы</h4>
            </div>
        </div>
        <div class="row comments">
            <div id="fb-root"></div>
            <div class="col-lg-12">
                <div class="fb-comments"
                     id="fb_comments"
                     width="100%"
                     :data-href="facebookPage"
                     data-numposts="6"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Comments',
        created: function() {
            if (!this.loadFBComments) {
                this.initFBComments();
            }
            else {
                setTimeout(() => {
                    FB.XFBML.parse(document.getElementById('fb_comments'));
                }, 3000);
            }
        },
        methods: {
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
            },
            'facebookPage': function () {
                return window.location.origin + '/' + this.$router.currentRoute.fullPath;
            },
        },
    }
</script>