<template>
    <div>
        <section class="utp" v-if="utfRecords.length">
            <div class="container d-flex justify-content-center">
                <div class="row">
                    <div class="col-12 utp_items centered">
                        <template v-for="(utf, index) in utfRecords">
                            <template v-if="index === 0">
                                <div class="checked">
                                    <div class="utp_text"><p>{{utf.description}}</p></div>
                                </div>
                            </template>
                            <template v-else-if="index === utfRecords.length - 1">
                                <div class="ruller">
                                    <div class="utp_text"><p>{{utf.description}}</p></div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="delivery">
                                    <div class="utp_text"><p>{{utf.description}}</p></div>
                                </div>
                            </template>
                        </template>
                    </div>
                    <div class="col-12 utp_items_mobile">
                        <div id="carouselExampleControls5" class="carousel slide my-carousel" data-ride="carousel">
                            <div class="carousel-inner">
                                <template v-for="(utf, index) in utfRecords">
                                    <div :class="(index === 0) ? 'carousel-item active' : 'carousel-item'">
                                        <div class="utp_text"><p>{{utf.description}}</p></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'UTP',
        computed: {
            utfRecordsStore: function () {
                return this.$store.getters.utfRecords;
            },
        },
        mounted() {
            if (this.utfRecordsStore.length) {
                this.utfRecords = this.utfRecordsStore;
            }
        },
        data() {
            return {
                utfRecords: []
            }
        },
        watch: {
            'utfRecordsStore': function (records) {
                this.utfRecords = records;
            }
        }
    }
</script>