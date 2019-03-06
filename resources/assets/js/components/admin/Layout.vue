<template>
<el-container style="height: 100vh;">
    <el-header style="text-align: right; font-size: 12px">
        <el-menu class="el-menu-demo" mode="horizontal"
            @select="handleSelect"
            background-color="rgb(28, 10, 92)"
            text-color="#fff"
            active-text-color="#ffd04b">

            <el-submenu style="float: right;" index="2">
                <template slot="title">Пользователь</template>
                <template v-if="!currentUser">
                            <el-menu-item index="/admin/login">Войти</el-menu-item>
                        </template>
                <template v-if="currentUser">
                        <el-menu-item index="logout">Выйти</el-menu-item>
                    </template>
            </el-submenu>
        </el-menu>
    </el-header>

    <el-container>
        <template v-if="currentUser">
                <PageAside/>
            </template>

        <el-main>
            <router-view></router-view>
        </el-main>

    </el-container>
</el-container>
</template>

<script>
import {
    PageAside
} from './page';

export default {
    name: 'layout',
    methods: {
        logout() {
            this.$store.commit('logout');
            this.$router.push('/admin/login');
        },
        handleSelect(key, keyPath) {
            if (key === 'logout') {
                return this.logout();
            }
            this.$router.push(key);
        }
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser
        }
    },
    components: {
        PageAside
    }
}
</script>
