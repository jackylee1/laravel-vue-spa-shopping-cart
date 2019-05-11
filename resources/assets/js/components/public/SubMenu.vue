<template>
    <div>
        <section class="submenu">
            <div class="container">
                <div class="row">
                    <div class="col-4 phones my-auto">
                        <ul>
                            <li v-if="phone1 !== null">{{phone1}}</li>
                            <li v-if="phone2 !== null">{{phone2}}</li>
                        </ul>
                    </div>
                    <template v-if="this.isLoggedIn">
                        <div class="col-2 heart my-auto">
                            <router-link :to="{ name: 'user_information' }">
                                <img src="/assets/public/images/cart/home.png" alt="home">
                            </router-link>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col-2 heart my-auto">
                            <router-link :to="{ name: 'login' }">
                                <img src="/assets/public/images/cart/signin.png" alt="signin">
                            </router-link>
                        </div>
                    </template>
                    <div class="col-2 heart my-auto">
                        <router-link :to="{ name: 'user_favorite' }">
                            <img src="/assets/public/images/cart/heart.png" alt="heart">
                        </router-link>
                    </div>
                    <div class="col-2 carte my-auto">
                        <router-link :to="{ name: 'cart' }">
                            <img src="/assets/public/images/cart/cart.png" alt="cart">
                        </router-link>
                    </div>
                    <div class="col-2 search my-auto">
                        <a data-toggle="collapse" href="#collapseSubmenu" role="button"
                           aria-expanded="false" aria-controls="collapseSubmenu">
                            <img src="/assets/public/images/cart/search.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'SubMenu',
        props: ['phone1', 'phone2'],
        computed: {
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn;
            }
        }
    }
</script>