<template>
    <div>
        <section id="top_line" class="top-line" >
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="telephone">
                    <span class="first">
                        <span class="code">(073) </span>
                        069-6097
                    </span>
                            <span class="second">
                        <span class="code">(068) </span>
                        225-7235
                    </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="authotisation">
                            <template v-if="isLoggedIn">
                                <router-link :to="{name: 'user_information'}">
                                    <a class="acount"
                                       href="javascript:void(0)">
                                        Мой аккаунт
                                    </a>
                                </router-link>
                            </template>
                            <template v-else>
                                <a class="login"
                                   href="#">
                                    Вход/Регистрация
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'TopLine',
        computed: {
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn;
            }
        }
    }
</script>