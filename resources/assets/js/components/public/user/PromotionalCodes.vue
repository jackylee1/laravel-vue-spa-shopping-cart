<template>
    <div>
        <section class="checkout_form sf_form ">
            <div class="container">
                <div class="row checkout_login">
                    <Sidebar/>

                    <div class="col-md-8 col-lg-9 wrapper ">
                        <div class="row">
                            <h3>Список промо-кодов</h3>
                        </div>
                        <div class="row">
                            <div class="hostory">
                                <Errors :type="typeAlerts"
                                        v-on:clearAlerts="clearAlerts"
                                        :alerts="alerts"/>

                                <template v-if="promotionalCodes.length">
                                    <div v-for="item in promotionalCodes" class="order">
                                        <div class="order_column">
                                            {{item.promotional_code.code}}
                                        </div>
                                        <div class="order_column text-center">
                                            Скидка: {{item.promotional_code.discount}} %
                                        </div>
                                        <div class="order_column text-center">
                                            <template v-if="item.promotional_code.status">
                                                <span style="color: green">Промо-код активный</span>
                                            </template>
                                            <template v-else>
                                                <span style="color: red">Промо-код был использован</span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="alert alert-info">
                                        У Вас пока нет промо-кодов
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
    import Sidebar from "./Sidebar";
    import mixinAlerts from '../../../app/public/mixins/Alerts';
    import mixinDate from '../../../app/public/mixins/Date';
    import Errors from "../Errors";

    export default {
        name: 'PromotionalCodes',
        mixins: [mixinAlerts, mixinDate],
        computed: {
            currentUser: function () {
                return this.$store.getters.currentUser;
            }
        },
        mounted() {
            if (!this.$store.getters.isLoggedIn) {
                return this.$router.push({name: '/login'});
            }

            this.promotionalCodes = this.currentUser.promotional_codes;
        },
        data() {
            return {
                promotionalCodes: [],
                isLoading: true,
                isFullPage: true,
            }
        },
        components: { Errors, Sidebar},
        metaInfo: {
            title: '| Кабинет | Список промо-кодов'
        }
    }
</script>