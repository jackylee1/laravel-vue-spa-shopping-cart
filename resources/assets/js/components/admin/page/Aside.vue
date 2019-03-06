<template>
    <el-aside width="250px" style="background-color: rgb(238, 241, 246)">
        <el-menu :default-active="$route.path" router>
            <template v-for="route in $router.options.routes" v-if="!route.meta.hidden">
                <el-menu-item v-if="!route.children" :index="route.path" :key="route.path">
                    <template v-if="route.name === 'main'">
                        <home-icon class="custom-class"></home-icon>
                    </template>
                    <template v-if="route.name === 'filters'">
                        <filter-icon class="custom-class"></filter-icon>
                    </template>
                    <template v-if="route.name === 'file-manager'">
                        <inbox-icon class="custom-class"></inbox-icon>
                    </template>
                    {{ route.meta.name }}
                </el-menu-item>
                <el-submenu v-else :index="route.path">
                    <template slot="title">
                        <template v-if="route.name === 'users' || route.name === 'user-groups'">
                            <users-icon class="custom-class"></users-icon>
                        </template>
                        <template v-if="route.name === 'types'">
                            <box-icon class="custom-class"></box-icon>
                        </template>
                        <template v-if="route.name === 'promotional-codes'">
                            <tag-icon class="custom-class"></tag-icon>
                        </template>
                        <template v-if="route.name === 'products'">
                            <shopping-bag-icon class="custom-class"></shopping-bag-icon>
                        </template>
                        <template v-if="route.name === 'sliders'">
                            <image-icon class="custom-class"></image-icon>
                        </template>
                        <template v-if="route.name === 'text-block'">
                            <align-left-icon class="custom-class"></align-left-icon>
                        </template>
                        {{ route.meta.name }}
                    </template>
                    <el-menu-item v-for="child in route.children" v-if="!child.meta.hidden" :index="route.path + '/' + child.path" :key="route.path + '/' + child.path">{{ child.meta.name }}</el-menu-item>
                </el-submenu>
            </template>
        </el-menu>
    </el-aside>
</template>

<script>
    import {
        HomeIcon, ShoppingBagIcon, UsersIcon,
        BoxIcon, TagIcon, FilterIcon, InboxIcon,
        ImageIcon, AlignLeftIcon
    } from 'vue-feather-icons'

    export default {
        name: 'page-aside',
        computed: {
            menuLevelOne: function () {
                return this.$router.options.routes.map(function (route) {
                    return route.path;
                });
            }
        },
        components: {
            HomeIcon,
            UsersIcon,
            BoxIcon,
            TagIcon,
            FilterIcon,
            ShoppingBagIcon,
            InboxIcon,
            ImageIcon,
            AlignLeftIcon
        }
    }
</script>
