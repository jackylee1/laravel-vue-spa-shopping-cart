<template>
    <div class="col-md-4 col-lg-3 col-12 account_sf">
        <ul>
            <template v-if="this.isLoggedIn">
                <li>
                    <router-link :to="{ name: 'user_information' }">
                        <a href="javascript:void(0)"><i class="fas fa-pen"></i>Информация</a>
                    </router-link>
                </li>
                <li><a href="javascript:void(0)"> <i class="fas fa-clipboard-list"></i>Заказы</a></li>
            </template>
            <li>
                <router-link :to="{ name: 'user_favorite' }">
                    <a href="javascript:void(0)"> <i class="fas fa-list-alt"></i>Список избранного</a>
                </router-link>
            </li>
            <template v-if="this.isLoggedIn">
                <li>
                    <a @click="logout"
                       href="javascript:void(0)"><i class="fas fa-sign-out-alt"></i>Выйти</a>
                </li>
            </template>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'Sidebar',
        computed: {
            isLoggedIn: function () {
                return this.$store.getters.isLoggedIn;
            }
        },
        methods: {
            logout: function () {
                this.$store.commit('logout');
                this.$router.push({ name: 'login' });
            }
        }
    }
</script>