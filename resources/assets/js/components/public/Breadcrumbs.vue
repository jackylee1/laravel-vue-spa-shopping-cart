<template>
    <div>
        <section v-if="items !== undefined && items.length" class="breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <router-link :to="{ name: 'index' }">
                                        <a href="">Главная</a>
                                    </router-link>
                                </li>
                                <template v-for="(item, index) in items">
                                    <template v-if="index !== (items.length-1)">
                                        <li class="breadcrumb-item">
                                            <router-link :to="{ name: item.route_name }">
                                                <a href="">{{item.title}}</a>
                                            </router-link>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            {{item.title}}
                                        </li>
                                    </template>
                                </template>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'Breadcrumbs',
        props: ['items']
    }
</script>