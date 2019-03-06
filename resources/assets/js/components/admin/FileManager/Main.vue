<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <div style="height: 550px">
            <file-manager class="filter-manager-main"></file-manager>
        </div>
    </div>
</template>

<script>
    import { PageElementsBreadcrumb } from '../page/elements';
    import * as helperRouter from '../../../app/helpers/router';

    export default {
        name: 'file-manager-main',
        mounted() {
            this.breadcrumbElements = [
                {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                {href: this.$router.resolve({name: 'file-manager'}).href, title: this.$router.currentRoute.meta.title},
            ];
        },
        data() {
            return {
                breadcrumbElements: []
            }
        },
        components: {
            PageElementsBreadcrumb
        }
    };
</script>