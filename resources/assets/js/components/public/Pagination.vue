<template>
    <div v-if="metaData.last_page > 1" class="row paginations">
        <div class="col-lg-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li :class="(metaData.prev_page_url === null) ? 'page-item disabled' : 'page-item'">
                        <a href="javascript:void(0)"
                           class="page-link"
                           @click="handleClick(metaData.current_page-1)">
                            Предыдущая
                        </a>
                    </li>
                    <li class="page-item"
                        v-for="page in metaData.last_page"
                        :key="page"
                        :class="(metaData.current_page === page) ? 'page-item disabled' : 'page-item'">
                        <a class="page-link"
                           @click.prevent="handleClick(page)"
                           href="javascript:void(0)">
                            {{ page }}
                        </a>
                    </li>
                    <li :class="(metaData.current_page === metaData.last_page) ? 'page-item disabled': 'page-item'">
                        <a href="javascript:void(0)"
                           class="page-link"
                           @click="handleClick(metaData.current_page+1)">
                            Следующая
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'Pagination',
        props: ['metaData'],
        methods: {
            handleClick(page) {
                this.$emit('getProducts', page);
                this.$scrollTo('#top_line', 450);
            }
        }
    }
</script>