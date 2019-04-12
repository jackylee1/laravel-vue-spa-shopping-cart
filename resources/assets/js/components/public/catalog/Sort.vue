<template>
    <div>
        <div class="row titles_top">
            <div class="col-md-6 col-12 my-auto">
                <template v-if="currentCategory !== null">
                    <h1 class="category_title">{{currentCategory.name}}</h1>
                </template>
                <template v-else-if="currentType !== null">
                    <h1 class="category_title">{{currentType.name}}</h1>
                </template>
            </div>
            <div class="col-md-3 col-6 sort righted my-auto">
                <p>Сортировка:</p>
            </div>
            <div class="col-md-3 col-6 my-auto">
                <select v-model="sort" class="form-control-sm custom-select">
                    <template v-for="option in options">
                        <option :value="option.value">{{option.name}}</option>
                    </template>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Sort',
        props: ['currentCategory', 'currentType', 'selectFilters'],
        data() {
            return {
                sort: (this.$route.query.sort !== undefined && this.$route.query.sort !== null) ? this.$route.query.sort : 'all',
                options: [
                    {value: 'all', name: 'все товары'},
                    {value: 'from_cheap_to_expensive', name: 'от дешевых к дорогим'},
                    {value: 'from_expensive_to_cheap', name: 'от дорогих к дешевым'},
                    {value: 'popular', name: 'популярные'},
                    {value: 'new', name: 'новинки'},
                    {value: 'promotional', name: 'акционные'},
                ]
            }
        },
        watch: {
            'sort': function (value) {
                this.$router.push({ query: Object.assign({}, this.$route.query, { sort: value }) });

                this.$emit('updateSort', value);
            }
        }
    }
</script>